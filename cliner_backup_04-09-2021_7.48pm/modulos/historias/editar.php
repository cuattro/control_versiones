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

if (!isset($_GET['id'])) {
  $resultado['error'] = true;
  $resultado['mensaje'] = 'El paciente no existe';
}

if (isset($_POST['submit'])) {
  try {
    $dsn = 'mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['name'];
    $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $config['db']['options']);

    $pacientes = [
      "id"        => $_GET['id'],
      "tipo_documento_pac"   => $_POST['tipo_documento_pac'],
      "no_documento_pac" => $_POST['no_documento_pac'],
      "nombres_pac"    => $_POST['nombres_pac'],
      "apellidos_pac"     => $_POST['apellidos_pac'],
      "direccion_pac"   => $_POST['direccion_pac'],
      "barrio_pac" => $_POST['barrio_pac'],
      "ciudad_pac"    => $_POST['ciudad_pac'],
      "telefono_pac"     => $_POST['telefono_pac'],
      "email_pac"     => $_POST['email_pac']
    ];
    
$consultaSQL = "UPDATE pacientes SET
      tipo_documento_pac   = :tipo_documento_pac,
      no_documento_pac = :no_documento_pac,
      nombres_pac    = :nombres_pac,
      apellidos_pac     = :apellidos_pac,
      direccion_pac   = :direccion_pac,
      barrio_pac = :barrio_pac,
      ciudad_pac    = :ciudad_pac,
      telefono_pac     = :telefono_pac,
      email_pac     = :email_pac,
      updated_at = NOW()
      WHERE id = :id";
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
    
  $id = $_GET['id'];
  $consultaSQL = "SELECT * FROM pacientes WHERE id =" . $id;

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
          <div class="form-group">
          <label for="tipo_documento_pac">Tipo documento</label>
          <select name="tipo_documento_pac" id="tipo_documento_pac" value="<?= escapar($pacientes['tipo_documento_pac']) ?>" class="form-control">
            <option>TI</option>
            <option>CC</option>
            <option>CE</option>
            <option>PA</option>
          </select>


          </div>
          <div class="form-group">
            <label for="no_documento_pac">Numero documento</label>
            <input type="text" name="no_documento_pac" id="no_documento_pac" value="<?= escapar($pacientes['no_documento_pac']) ?>" class="form-control">
          </div>
          <div class="form-group">
            <label for="nombres_pac">Nombres</label>
            <input type="text" name="nombres_pac" id="nombres_pac" value="<?= escapar($pacientes['nombres_pac']) ?>" class="form-control">
          </div>
          <div class="form-group">
            <label for="apellidos_pac">Apellidos</label>
            <input type="text" name="apellidos_pac" id="apellidos_pac" value="<?= escapar($pacientes['apellidos_pac']) ?>" class="form-control">
          </div>
          <div class="form-group">
            <label for="direccion_pac">Direccion</label>
            <input type="text" name="direccion_pac" id="direccion_pac" value="<?= escapar($pacientes['direccion_pac']) ?>" class="form-control">
          </div>
          <div class="form-group">
            <label for="barrio_pac">Barrio</label>
            <input type="text" name="barrio_pac" id="barrio_pac" value="<?= escapar($pacientes['barrio_pac']) ?>" class="form-control">
          </div>
          <div class="form-group">
            <label for="ciudad_pac">Ciudad</label>
            <input type="text" name="ciudad_pac" id="ciudad_pac" value="<?= escapar($pacientes['ciudad_pac']) ?>" class="form-control">
          </div>
          <div class="form-group">
            <label for="telefono_pac">Telefono</label>
            <input type="text" name="telefono_pac" id="telefono_pac" value="<?= escapar($pacientes['telefono_pac']) ?>" class="form-control">
          </div>
          <div class="form-group">
            <label for="email_pac">E-mail</label>
            <input type="text" name="email_pac" id="email_pac" value="<?= escapar($pacientes['email_pac']) ?>" class="form-control">
          </div>



          <div class="form-group">
            <input name="csrf" type="hidden" value="<?php echo escapar($_SESSION['csrf']); ?>">
            <input type="submit" name="submit" class="btn btn-warning" value="Actualizar">
            <a class="btn btn-primary" href="index.php">Cancelar edicion</a>
          </div>
        </form>
      </div>
    </div>
  </div>
  <?php
}
?>

<?php require "../../templates/footer.php"; ?>