<?php

include 'funciones.php';

csrf();
if (isset($_POST['submit']) && !hash_equals($_SESSION['csrf'], $_POST['csrf'])) {
  die();
}

if (isset($_POST['submit'])) {
  $resultado = [
    'error' => false,
    'mensaje' => 'El medico ' . escapar($_POST['nombres_med']) . ' ha sido agregado con éxito'
  ];

  $config = include 'config.php';

  try {
    $dsn = 'mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['name'];
    $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $config['db']['options']);

    $medicos = [
      "tipo_documento_med"  => $_POST['tipo_documento_med'],
      "no_documento_med"    => $_POST['no_documento_med'],
      "nombres_med"         => $_POST['nombres_med'],
      "apellidos_med"       => $_POST['apellidos_med'],
      "direccion_med"       => $_POST['direccion_med'],
      "barrio_med"          => $_POST['barrio_med'],
      "ciudad_med"          => $_POST['ciudad_med'],
      "telefono_med"        => $_POST['telefono_med'],
      "email_med"           => $_POST['email_med'],
      "estado_med"          => $_POST['estado_med'],


    ];

    $consultaSQL = "INSERT INTO medicos (tipo_documento_med, no_documento_med, nombres_med, apellidos_med, direccion_med, barrio_med, ciudad_med, telefono_med, email_med, estado_med)";
    $consultaSQL .= "values (:" . implode(", :", array_keys($medicos)) . ")";

    $sentencia = $conexion->prepare($consultaSQL);
    $sentencia->execute($medicos);

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
        <legend>Registrar medico</legend>
        <div class="form">
          <div>
            <label for="tipo_documento">Tipo Documento</label>
            <select name="tipo_documento_med" name="tipo_documento_med" id="tipo_documento_med">
            <option value="CC">CC</option>
            <option value="CE">CE</option>
            <option value="PA">PA</option>
          </select>
          <label for="documento">No. Documento</label>
            <input name="no_documento_med" id="no_documento_med" type="text" placeholder="No. Documento" required>
          </div>
          <div>
            <label for="nombres_med">Nombres</label>
            <input name="nombres_med" id="nombres_med" type="text" placeholder="Nombres" required>
            <label for="apellidos_med">Apellidos</label>
            <input name="apellidos_med" id="apellidos_med" type="text" placeholder="Apellidos" required>
          </div>
          <div>
            <label for="direccion_med">Direccion</label>
            <input name="direccion_med" id="correo" type="text" placeholder="Direccion" required>
            <label for="barrio_med">Barrio</label>
            <input name="barrio_med" id="barrio_med" type="text" placeholder="Barrio" required>
          </div>
          <div>
            <label for="ciudad_med">Ciudad</label>
            <input name="ciudad_med" id="correo" type="text" placeholder="Ciudad" required>
          </div>
          <div>
            <label for="perfil">Telefono Contacto</label>
            <input type="text" name="telefono_med" id="telefono_med" placeholder="Telefono Contacto" required>
            <label for="email">E-mail</label>
            <input type="email" name="email_med" id="email_med" placeholder="Correo electronico" required>
            <select style="visibility:hidden" name="estado_med" id="estado_med">
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