<?php

include 'funciones.php';

csrf();
if (isset($_POST['submit']) && !hash_equals($_SESSION['csrf'], $_POST['csrf'])) {
  die();
}

if (isset($_POST['submit'])) {
  $resultado = [
    'error' => false,
    'mensaje' => 'El paciente ' . escapar($_POST['nombres_pac']) . ' ha sido agregado con éxito'
  ];

  $config = include 'config.php';

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

    $consultaSQL = "INSERT INTO pacientes (tipo_documento_pac, no_documento_pac, nombres_pac, apellidos_pac, direccion_pac, barrio_pac, ciudad_pac, telefono_pac, email_pac, estado_pac)";
    $consultaSQL .= "values (:" . implode(", :", array_keys($pacientes)) . ")";

    $sentencia = $conexion->prepare($consultaSQL);
    $sentencia->execute($pacientes);

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
        <legend>Registrar paciente</legend>
        <div class="form">
          <div>
            <label for="tipo_documento">Tipo Documento</label>
            <select name="tipo_documento_pac" name="tipo_documento_pac" id="tipo_documento_pac">
            <option value="TI">TI</option>
            <option value="CC">CC</option>
            <option value="CE">CE</option>
            <option value="PA">PA</option>
          </select>
          <label for="documento">No. Documento</label>
            <input name="no_documento_pac" id="no_documento_pac" type="text" placeholder="No. Documento" required>
          </div>
          <div>
            <label for="nombres_pac">Nombres</label>
            <input name="nombres_pac" id="nombres_pac" type="text" placeholder="Nombres" required>
            <label for="apellidos_pac">Apellidos</label>
            <input name="apellidos_pac" id="apellidos_pac" type="text" placeholder="Apellidos" required>
          </div>
          <div>
            <label for="direccion_pac">Direccion</label>
            <input name="direccion_pac" id="correo" type="text" placeholder="Direccion" required>
            <label for="barrio_pac">Barrio</label>
            <input name="barrio_pac" id="barrio_pac" type="text" placeholder="Barrio" required>
          </div>
          <div>
            <label for="ciudad_pac">Ciudad</label>
            <input name="ciudad_pac" id="correo" type="text" placeholder="Ciudad" required>
          </div>
          <div>
            <label for="perfil">Telefono Contacto</label>
            <input type="text" name="telefono_pac" id="telefono_pac" placeholder="Telefono Contacto" required>
            <label for="email">E-mail</label>
            <input type="email" name="email_pac" id="email_pac" placeholder="Correo electronico" required>
            <select style="visibility:hidden" name="estado_pac" id="estado_pac">
              <option value="Activo">Activo</option>
              <!--<option value="activo">Inactivo</option>-->
            </select>
        </div>
        <div class="form-pie">
        <input name="csrf" type="hidden" value="<?php echo escapar($_SESSION['csrf']); ?>">
          <input type="submit" name="submit" class="btn btn-success" value="Registrar">
          <a class="btn btn-primary" href="index.php">Cancelar</a>
      </div>
        </fieldset>
    </form>
    </div>
  </div>
</div>

<?php include '../../templates/footer.php'; ?>