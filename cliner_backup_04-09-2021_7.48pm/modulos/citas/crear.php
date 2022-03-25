<?php

include 'funciones.php';

csrf();
if (isset($_POST['submit']) && !hash_equals($_SESSION['csrf'], $_POST['csrf'])) {
  die();
}

if (isset($_POST['submit'])) {
  $resultado = [
    'error' => false,
    'mensaje' => 'La cita para el documento ' . escapar($_POST['id_paciente']) . ' ha sido registrada con éxito'
  ];

  $config = include 'config.php';

  try {
    $dsn = 'mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['name'];
    $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $config['db']['options']);

    $citas = [
      "id_cita"   => $_POST['id_cita'],
      "id_paciente" => $_POST['id_paciente'],
      "fecha_cita"    => $_POST['fecha_cita'],
      "hora_cita"     => $_POST['hora_cita'],
      "id_medico"   => $_POST['id_medico'],
      "estado_cita" => $_POST['estado_cita'],
    ];

    $consultaSQL = "INSERT INTO citas (id_cita, id_paciente, fecha_cita, hora_cita, id_medico, estado_cita)";
    $consultaSQL .= "values (:" . implode(", :", array_keys($citas)) . ")";

    $sentencia = $conexion->prepare($consultaSQL);
    $sentencia->execute($citas);

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
  <form method="post">
    <fieldset>
      <legend>Agendar Cita</legend>
        <div class="form">
          <div>
            <label for="id_cita">Id. Cita</label>
            <input type="text" name="id_cita" id="id_cita" placeholder="Id. Cita" required>
            <label for="id_paciente">Id. Paciente</label>
            <input type="text" name="id_paciente" id="id_paciente" placeholder="Id. Paciente" required>
          </div>
          <div>
            <label for="fecha_cita">Fecha Cita</label>
            <input type="date" id="fecha_cita" name="fecha_cita" required
                value="aaaa-mm-dd"
                min="2021-09-01" max="2021-12-31">
            <label for="hora_cita">Hora Cita</label>
          <input type="time" value="00:00:00" name="hora_cita" id="hora_cita" placeholder="00:00" required>
          </div>
          <div>
            <label for="id_medico">Id. Medico</label>
            <input type="id_medico" name="id_medico" id="id_medico" placeholder="Id. Medico" required>
            <select style="visibility:hidden" name="estado_cita" id="estado_cita">
              <option value="Asignada">Asignada</option>
            </select>
        </div>
        <div class="form-pie">
          <input name="csrf" type="hidden" value="<?php echo escapar($_SESSION['csrf']); ?>">
          <input type="submit" name="submit" class="btn btn-success" value="Asignar">
          <a class="btn btn-primary" href="index.php">Cancelar</a>
        </div>
    </fieldset>
  </form>
    </div>
  </div>
</div>

<?php include '../../templates/footer.php'; ?>