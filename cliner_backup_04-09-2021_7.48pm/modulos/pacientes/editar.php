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

if (!isset($_GET['no_documento_pac'])) {
  $resultado['error'] = true;
  $resultado['mensaje'] = 'El paciente no existe';
}

if (isset($_POST['submit'])) {
  try {
    $dsn = 'mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['name'];
    $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $config['db']['options']);

    $pacientes = [
      "tipo_documento_pac" => $_POST['tipo_documento_pac'],
      "no_documento_pac"   => $_POST['no_documento_pac'],
      "nombres_pac"        => $_POST['nombres_pac'],
      "apellidos_pac"      => $_POST['apellidos_pac'],
      "direccion_pac"      => $_POST['direccion_pac'],
      "barrio_pac"         => $_POST['barrio_pac'],
      "ciudad_pac"         => $_POST['ciudad_pac'],
      "telefono_pac"       => $_POST['telefono_pac'],
      "email_pac"          => $_POST['email_pac'],
      "estado_pac"         => $_POST['estado_pac'],
    ];
    
$consultaSQL = "UPDATE pacientes SET
      tipo_documento_pac = :tipo_documento_pac,
      no_documento_pac   = :no_documento_pac,
      nombres_pac        = :nombres_pac,
      apellidos_pac      = :apellidos_pac,
      direccion_pac      = :direccion_pac,
      barrio_pac         = :barrio_pac,
      ciudad_pac         = :ciudad_pac,
      telefono_pac       = :telefono_pac,
      email_pac          = :email_pac,
      estado_pac         = :estado_pac,
      updated_at = NOW()
      WHERE no_documento_pac = :no_documento_pac";
    $consulta = $conexion->prepare($consultaSQL);
    $consulta->execute($pacientes);
  } catch(PDOException $error) {
    $resultado['error'] = true;
    $resultado['mensaje'] = $error->getMessage();
  }
}

try {
  $dsn = 'mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['name'];
  $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $config['db']['options']);
    
  $id = $_GET['no_documento_pac'];
  $consultaSQL = "SELECT * FROM pacientes WHERE no_documento_pac =" . $id;

  $sentencia = $conexion->prepare($consultaSQL);
  $sentencia->execute();

  $pacientes = $sentencia->fetch(PDO::FETCH_ASSOC);

  if (!$pacientes) {
    $resultado['error'] = true;
    $resultado['mensaje'] = 'No se ha encontrado el paciente';
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
          El paciente ha sido actualizado correctamente
        </div>
      </div>
    </div>
  </div>
  <?php
}
?>

<?php
if (isset($pacientes) && $pacientes) {
  ?>
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <h2 class="mt-4">¡PRECAUCION! Se encuentra editando los datos correspondientes al paciente <?= escapar($pacientes['nombres_pac']) . ' ' . escapar($pacientes['apellidos_pac'])  ?></h2>
        <hr>

        <form method="post">
      <fieldset>
        <legend>Actualizar paciente</legend>
        <div class="form">
          <div>
            <label for="tipo_documento">Tipo Documento</label>
            <select name="tipo_documento_pac" name="tipo_documento_pac" id="tipo_documento_pac" value="<?= escapar($pacientes['tipo_documento_pac']) ?>">
            <option value="TI">TI</option>
            <option value="CC">CC</option>
            <option value="CE">CE</option>
            <option value="PA">PA</option>
          </select>
          <label for="documento">No. Documento</label>
            <input name="no_documento_pac" id="no_documento_pac" type="text" value="<?= escapar($pacientes['no_documento_pac']) ?>">
          </div>
          <div>
            <label for="nombres_pac">Nombres</label>
            <input name="nombres_pac" id="nombres_pac" type="text" value="<?= escapar($pacientes['nombres_pac']) ?>">
            <label for="apellidos_pac">Apellidos</label>
            <input name="apellidos_pac" id="apellidos_pac" type="text" value="<?= escapar($pacientes['apellidos_pac']) ?>"
          </div>
          <div>
            <label for="direccion_pac">Direccion</label>
            <input name="direccion_pac" id="correo" type="text" value="<?= escapar($pacientes['direccion_pac']) ?>">
            <label for="barrio_pac">Barrio</label>
            <input name="barrio_pac" id="barrio_pac" type="text" value="<?= escapar($pacientes['barrio_pac']) ?>" >
          </div>
          <div>
            <label for="ciudad_pac">Ciudad</label>
            <input name="ciudad_pac" id="correo" type="text" value="<?= escapar($pacientes['ciudad_pac']) ?>">
            <label for="perfil">Telefono Contacto</label>
            <input type="text" name="telefono_pac" id="telefono_pac" value="<?= escapar($pacientes['telefono_pac']) ?>">
          </div>
          <div>
            <label for="email">E-mail</label>
            <input type="email" name="email_pac" id="email_pac" value="<?= escapar($pacientes['email_pac']) ?>">
            <label for="estado_pac">Estado</label>
              <select name="estado_pac" id="estado_pac" value="<?= escapar($pacientes['estado_pac']) ?>">
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