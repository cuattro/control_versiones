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
    $consultaSQL = "SELECT * FROM pacientes WHERE no_documento_pac LIKE '%" . $_POST['no_documento_pac'] . "%'";
  } else {
    $consultaSQL = "SELECT * FROM pacientes order by created_at desc";
  }

  $sentencia = $conexion->prepare($consultaSQL);
  $sentencia->execute();

  $pacientes = $sentencia->fetchAll();

} catch(PDOException $error) {
  $error= $error->getMessage();
}

$titulo = isset($_POST['no_documento_pac']) ? 'Lista de pacientes (' . $_POST['no_documento_pac'] . ')' : 'Lista de pacientes';
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
      <a href="crear.php"  class="btn btn-success mt-4">Agregar paciente</a>
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
            <!--<th>#</th>-->
            <th>Tipo documento</th>
            <th>Documento No.</th>
            <th>Nombres</th>
            <th>Apellidos</th>
            <th>Direccion</th>
            <th>Barrio</th>
            <th>Ciudad</th>
            <th>Telefono</th>
            <th>E-mail</th>
            <th>Estado</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php
          if ($pacientes && $sentencia->rowCount() > 0) {
            foreach ($pacientes as $fila) {
              ?>
              <tr>
                <!--<td><?php echo escapar($fila["id"]); ?></td>-->
                <td><?php echo escapar($fila["tipo_documento_pac"]); ?></td>
                <td><?php echo escapar($fila["no_documento_pac"]); ?></td>
                <td><?php echo escapar($fila["nombres_pac"]); ?></td>
                <td><?php echo escapar($fila["apellidos_pac"]); ?></td> 
                <td><?php echo escapar($fila["direccion_pac"]); ?></td>
                <td><?php echo escapar($fila["barrio_pac"]); ?></td>
                <td><?php echo escapar($fila["ciudad_pac"]); ?></td>
                <td><?php echo escapar($fila["telefono_pac"]); ?></td>
                <td><?php echo escapar($fila["email_pac"]); ?></td>
                <td>
                <?php
                  $activo = "Activo";
                  $inactivo = "Inactivo";
                  if (($fila["estado_pac"]) == $activo)
                  {
                    echo "<span class=\"activo\">".$activo."</span";
                  }
                    else
                    {
                      echo "<span class=\"inactivo\">".$inactivo."</span>";
                    }  
               ?>
                </td>
                <td>
                  <a href="<?= 'editar.php?no_documento_pac='.escapar($fila["no_documento_pac"]) ?>"><i class="fas fa-edit"></i></a>
                  <a href="<?= 'borrar.php?no_documento_pac='.escapar($fila["no_documento_pac"]) ?>"><i class="fas fa-trash-alt"></i></a>
                  
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