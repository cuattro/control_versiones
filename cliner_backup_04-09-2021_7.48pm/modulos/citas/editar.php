<?php
include 'funciones.php';

csrf();
if (isset($_POST['submit']) && !hash_equals($_SESSION['csrf'], $_POST['csrf'])) {
  die();
}

$config = include 'config.php';

$resultado = [
  'error' => false,
  'mensaje' => ''
];

if (!isset($_GET['id_paciente'])) {
  $resultado['error'] = true;
  $resultado['mensaje'] = 'La cita no existe';
}

if (isset($_POST['submit'])) {
  try {
    $dsn = 'mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['name'];
    $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $config['db']['options']);

    $citas = [
      "id_cita"     => $_POST['id_cita'],
      "id_paciente" => $_POST['id_paciente'],
      "fecha_cita"  => $_POST['fecha_cita'],
      "hora_cita"   => $_POST['hora_cita'],
      "id_medico"   => $_POST['id_medico'],
      "estado_cita" => $_POST['estado_cita']
    ];
    
$consultaSQL = "UPDATE citas SET
      id_cita     = :id_cita,
      id_paciente = :id_paciente,
      fecha_cita  = :fecha_cita,
      hora_cita   = :hora_cita,
      id_medico   = :id_medico,
      estado_cita = :estado_cita,
      updated_at  = NOW()
      WHERE id_paciente = :id_paciente";
    $consulta = $conexion->prepare($consultaSQL);
    $consulta->execute($citas);
  } catch(PDOException $error) {
    $resultado['error'] = true;
    $resultado['mensaje'] = $error->getMessage();
  }
}

try {
  $dsn = 'mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['name'];
  $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $config['db']['options']);
    
  $id = $_GET['id_paciente'];
  $consultaSQL = "SELECT * FROM citas WHERE id_paciente =" . $id;

  $sentencia = $conexion->prepare($consultaSQL);
  $sentencia->execute();

  $citas = $sentencia->fetch(PDO::FETCH_ASSOC);

  if (!$citas) {
    $resultado['error'] = true;
    $resultado['mensaje'] = 'No se ha encontrado la cita';
  }

} catch(PDOException $error) {
  $resultado['error'] = true;
  $resultado['mensaje'] = $error->getMessage();
}
?>

<?php require "../../templates/header.php"; ?>

<?php
if ($resultado['error']) {
  ?>
  <div class="container mt-2">
    <div class="row">
      <div class="col-md-12">
        <div class="alert alert-danger" role="alert">
          <?= $resultado['mensaje'] ?>
        </div>
      </div>
    </div>
  </div>
  <?php
}
?>

<?php
if (isset($_POST['submit']) && !$resultado['error']) {
  ?>
  <div class="container mt-2">
    <div class="row">
      <div class="col-md-12">
        <div class="alert alert-success" role="alert">
          La cita ha sido modificada correctamente
        </div>
      </div>
    </div>
  </div>
  <?php
}
?>

<?php
if (isset($citas) && $citas) {
  ?>
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <h2 class="mt-4">¡PRECAUCION! Se encuentra modificando la cita agedada al paciente <?= escapar($citas['id_paciente'])?></h2>
        <hr>
        <form method="post">
          <fieldset>
      <legend>Modificar Cita</legend>
        <div class="form">
          <div>
            <label for="id_cita">Id. Cita</label>
            <input type="text" name="id_cita" id="id_cita" value="<?= escapar($citas['id_cita']) ?>" required>
            <label for="id_paciente">Id. Paciente</label>
            <input type="text" name="id_paciente" id="id_paciente" value="<?= escapar($citas['id_paciente']) ?>" required>
          </div>
          <div>
            <label for="fecha_cita">Fecha Cita</label>
            <input type="date" id="fecha_cita" name="fecha_cita" required
                value="<?= escapar($citas['fecha_cita']) ?>"
                min="2021-09-01" max="2021-12-31">
            <label for="hora_cita">Hora Cita</label>
          <input type="time" value="<?= escapar($citas['hora_cita']) ?>" name="hora_cita" id="hora_cita"  required>
          </div>
          <div>
            <label for="id_medico">Id. Medico</label>
            <input type="id_medico" name="id_medico" id="id_medico" value="<?= escapar($citas['id_medico']) ?>" required>
            <label for="estado_cita">Estado Cita</label>
              <select name="estado_cita" id="estado_cita" value="<?= escapar($citas['estado_cita']) ?>">
              <option value="Asignada">Asignada</option>
              <option value="Atendida">Atendida</option>
              <option value="Cancelada">Cancelada</option>
            </select>
        </div>
        <div class="form-pie">
          <input name="csrf" type="hidden" value="<?php echo escapar($_SESSION['csrf']); ?>">
          <input type="submit" name="submit" class="btn btn-success" value="Modificar">
          <a class="btn btn-primary" href="index.php">Cancelar</a>
        </div>
    </fieldset>
        </form>
      </div>
    </div>
  </div>
  <?php
}
?>

<?php require "../../templates/footer.php"; ?>