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

if (!isset($_GET['no_documento_med'])) {
  $resultado['error'] = true;
  $resultado['mensaje'] = 'El medico no existe';
}

if (isset($_POST['submit'])) {
  try {
    $dsn = 'mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['name'];
    $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $config['db']['options']);

    $medicos = [
      "tipo_documento_med" => $_POST['tipo_documento_med'],
      "no_documento_med"   => $_POST['no_documento_med'],
      "nombres_med"        => $_POST['nombres_med'],
      "apellidos_med"      => $_POST['apellidos_med'],
      "direccion_med"      => $_POST['direccion_med'],
      "barrio_med"         => $_POST['barrio_med'],
      "ciudad_med"         => $_POST['ciudad_med'],
      "telefono_med"       => $_POST['telefono_med'],
      "email_med"          => $_POST['email_med'],
      "estado_med"         => $_POST['estado_med'],
    ];
    
$consultaSQL = "UPDATE medicos SET
      tipo_documento_med = :tipo_documento_med,
      no_documento_med   = :no_documento_med,
      nombres_med        = :nombres_med,
      apellidos_med      = :apellidos_med,
      direccion_med      = :direccion_med,
      barrio_med         = :barrio_med,
      ciudad_med         = :ciudad_med,
      telefono_med       = :telefono_med,
      email_med          = :email_med,
      estado_med         = :estado_med,
      updated_at = NOW()
      WHERE no_documento_med = :no_documento_med";
    $consulta = $conexion->prepare($consultaSQL);
    $consulta->execute($medicos);
  } catch(PDOException $error) {
    $resultado['error'] = true;
    $resultado['mensaje'] = $error->getMessage();
  }
}

try {
  $dsn = 'mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['name'];
  $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $config['db']['options']);
    
  $id = $_GET['no_documento_med'];
  $consultaSQL = "SELECT * FROM medicos WHERE no_documento_med =" . $id;

  $sentencia = $conexion->prepare($consultaSQL);
  $sentencia->execute();

  $medicos = $sentencia->fetch(PDO::FETCH_ASSOC);

  if (!$medicos) {
    $resultado['error'] = true;
    $resultado['mensaje'] = 'No se ha encontrado el medico';
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
          El medico ha sido actualizado correctamente
        </div>
      </div>
    </div>
  </div>
  <?php
}
?>

<?php
if (isset($medicos) && $medicos) {
  ?>
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <h2 class="mt-4">¡PRECAUCION! Se encuentra editando los datos correspondientes al medico <?= escapar($medicos['nombres_med']) . ' ' . escapar($medicos['apellidos_med'])  ?></h2>
        <hr>

        <form method="post">
      <fieldset>
        <legend>Actualizar medico</legend>
        <div class="form">
          <div>
            <label for="tipo_documento">Tipo Documento</label>
            <select name="tipo_documento_med" name="tipo_documento_med" id="tipo_documento_med" value="<?= escapar($medicos['tipo_documento_med']) ?>">
            <option value="CC">CC</option>
            <option value="CE">CE</option>
            <option value="PA">PA</option>
          </select>
          <label for="documento">No. Documento</label>
            <input name="no_documento_med" id="no_documento_med" type="text" value="<?= escapar($medicos['no_documento_med']) ?>">
          </div>
          <div>
            <label for="nombres_med">Nombres</label>
            <input name="nombres_med" id="nombres_med" type="text" value="<?= escapar($medicos['nombres_med']) ?>">
            <label for="apellidos_med">Apellidos</label>
            <input name="apellidos_med" id="apellidos_med" type="text" value="<?= escapar($medicos['apellidos_med']) ?>"
          </div>
          <div>
            <label for="direccion_med">Direccion</label>
            <input name="direccion_med" id="correo" type="text" value="<?= escapar($medicos['direccion_med']) ?>">
            <label for="barrio_med">Barrio</label>
            <input name="barrio_med" id="barrio_med" type="text" value="<?= escapar($medicos['barrio_med']) ?>" >
          </div>
          <div>
            <label for="ciudad_med">Ciudad</label>
            <input name="ciudad_med" id="correo" type="text" value="<?= escapar($medicos['ciudad_med']) ?>">
            <label for="perfil">Telefono Contacto</label>
            <input type="text" name="telefono_med" id="telefono_med" value="<?= escapar($medicos['telefono_med']) ?>">
          </div>
          <div>
            <label for="email">E-mail</label>
            <input type="email" name="email_med" id="email_med" value="<?= escapar($medicos['email_med']) ?>">
            <label for="estado_med">Estado</label>
              <select name="estado_med" id="estado_med" value="<?= escapar($medicos['estado_med']) ?>">
              <option value="Activo">Activo</option>
              <option value="Inactivo">Inactivo</option>
            </select>
        </div>
        <div class="form-pie">
        <input name="csrf" type="hidden" value="<?php echo escapar($_SESSION['csrf']); ?>">
            <input type="submit" name="submit" class="btn btn-warning" value="Actualizar">
            <a class="btn btn-primary" href="index.php">Cancelar edicion</a>
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