<?php

include 'funciones.php';

csrf();
if (isset($_POST['submit']) && !hash_equals($_SESSION['csrf'], $_POST['csrf'])) {
  die();
}

if (isset($_POST['submit'])) {
  $resultado = [
    'error' => false,
    'mensaje' => 'La historia #' . escapar($_POST['no_documento_pac']) . ' ha sido agregado con éxito'
  ];

  $config = include 'config.php';

  try {
    $dsn = 'mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['name'];
    $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $config['db']['options']);

    $historias = [
      "no_documento_pac" => $_POST['no_documento_pac'],
      "fecha_mod"    => $_POST['fecha_mod'],
      "no_documento_doc"     => $_POST['no_documento_doc'],
      "observaciones"   => $_POST['observaciones'],
    ];

    $consultaSQL = "INSERT INTO historias (no_documento_pac, fecha_mod, no_documento_med, observaciones)";
    $consultaSQL .= "values (:" . implode(", :", array_keys($historias)) . ")";

    $sentencia = $conexion->prepare($consultaSQL);
    $sentencia->execute($historias);

  } catch(PDOException $error) {
    $resultado['error'] = true;
    $resultado['mensaje'] = $error->getMessage();
  }
}
?>

<?php include '../../templates/header.php'; ?>

<?php
if (isset($resultado)) {
  ?>
  <div class="container mt-3">
    <div class="row">
      <div class="col-md-12">
        <div class="alert alert-<?= $resultado['error'] ? 'danger' : 'success' ?>" role="alert">
          <?= $resultado['mensaje'] ?>
        </div>
      </div>
    </div>
  </div>
  <?php
}
?>

<div class="container">
  <div class="row">
    <div class="col-md-12">
      <h2 class="mt-4">Agregar historia</h2>
      <hr>
      <form method="post">
        <div class="form-group">
        <div class="form-group">
          <label for="no_documento_pac">Numero documento Paciente</label>
          <input type="text" name="no_documento_pac" id="no_documento_pac" class="form-control">
        </div>
        <div class="form-group">
          <label for="fecha_mod">Fecha Modificacion</label>
          <input type="text" name="fecha_mod" id="fecha_mod" class="form-control">
        </div>
        <div class="form-group">
          <label for="no_documento_doc">Id. Medico</label>
          <input type="text" name="no_documento_doc" id="no_documento_doc" class="form-control">
        </div>
        <div class="form-group">
          <label for="observaciones">Observaciones</label>
          <input type="observaciones" name="observaciones" id="observaciones" class="form-control">
        </div>
        <div class="form-group">
          <input name="csrf" type="hidden" value="<?php echo escapar($_SESSION['csrf']); ?>">
          <input type="submit" name="submit" class="btn btn-primary" value="Enviar">
          <a class="btn btn-primary" href="index.php">Cancelar</a>
        </div>
      </form>
    </div>
  </div>
</div>

<?php include '../../templates/footer.php'; ?>