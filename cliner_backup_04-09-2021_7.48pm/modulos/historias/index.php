<?php
include 'funciones.php';

csrf();
if (isset($_POST['submit']) && !hash_equals($_SESSION['csrf'], $_POST['csrf'])) {
  die();
}

$error = false;
$config = include 'config.php';

try {
  $dsn = 'mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['name'];
  $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $config['db']['options']);

  if (isset($_POST['no_documento_pac'])) {
    $consultaSQL = "SELECT * FROM historias WHERE no_documento_pac LIKE '%" . $_POST['no_documento_pac'] . "%'";
  } else {
    $consultaSQL = "SELECT * FROM historias";
  }

  $sentencia = $conexion->prepare($consultaSQL);
  $sentencia->execute();

  $historias = $sentencia->fetchAll();

} catch(PDOException $error) {
  $error= $error->getMessage();
}

$titulo = isset($_POST['no_documento_pac']) ? 'Lista de historias (' . $_POST['no_documento_pac'] . ')' : 'Lista de historias';
?>

<?php include "../../templates/header.php"; ?>

<?php
if ($error) {
  ?>
  <div class="container mt-2">
    <div class="row">
      <div class="col-md-12">
        <div class="alert alert-danger" role="alert">
          <?= $error ?>
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
      <a href="crear.php"  class="btn btn-success mt-4">Agregar historia</a>
      <hr>
      <form method="post" class="form-inline">
        <div class="form-group mr-3">
          <input type="text" id="no_documento_pac" name="no_documento_pac" placeholder="Buscar por Numero de documento" class="form-control">
        </div>
        <input name="csrf" type="hidden" value="<?php echo escapar($_SESSION['csrf']); ?>">
        <button type="submit" name="submit" class="btn btn-primary">Buscar</button>
      </form>
    </div>
  </div>
</div>

<div class="container">
  <div class="row">
    <div class="col-md-12">
      <h2 class="mt-3"><?= $titulo ?></h2>
      <table class="table">
        <thead>
          <tr>
            <th>#</th>
            <th>Historia No.</th>
            <th>Fecha modificación</th>
            <th>Id. Medico</th>
            <th>Observaciones</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php
          if ($historias && $sentencia->rowCount() > 0) {
            foreach ($historias as $fila) {
              ?>
              <tr>
                <td><?php echo escapar($fila["id"]); ?></td>
                <td><?php echo escapar($fila["no_documento_pac"]); ?></td>
                <td><?php echo escapar($fila["fecha_mod"]); ?></td>
                <td><?php echo escapar($fila["no_documento_med"]); ?></td> 
                <td><?php echo escapar($fila["observaciones"]); ?></td>
                <td>
                  <a href="<?= 'borrar.php?id=' . escapar($fila["id"]) ?>">🗑️Borrar</a>
                  <a href="<?= 'editar.php?id=' . escapar($fila["id"]) ?>">✏️Editar</a>
                </td>
              </tr>
              <?php
            }
          }
          ?>
        <tbody>
      </table>
    </div>
  </div>
</div>

<?php include "../../templates/footer.php"; ?>