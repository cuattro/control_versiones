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

  if (isset($_POST['id_paciente'])) {
    $consultaSQL = "SELECT * FROM citas WHERE id_paciente LIKE '%" . $_POST['id_paciente'] . "%'";
  } else {
    $consultaSQL = "SELECT * FROM citas order by fecha_cita asc";
  }

  $sentencia = $conexion->prepare($consultaSQL);
  $sentencia->execute();

  $citas = $sentencia->fetchAll();

} catch(PDOException $error) {
  $error= $error->getMessage();
}

$titulo = isset($_POST['id_paciente']) ? 'Lista de citas (' . $_POST['id_paciente'] . ')' : 'Lista de citas';
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
      <a href="crear.php"  class="btn btn-success mt-4">Agendar cita</a>
      <hr>
      <form method="post" class="form-inline">
        <div class="form-group mr-3">
          <input type="text" id="id_paciente" name="id_paciente" placeholder="Buscar por Numero de documento" class="form-control">
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
            <th>Id. Cita</th>
            <th>Id. Paciente</th>
            <th>Fecha</th>
            <th>Hora</th>
            <th>Id. Medico</th>
            <th>Estado Cita</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php
          if ($citas && $sentencia->rowCount() > 0) {
            foreach ($citas as $fila) {
              ?>
              <tr>
                <td><?php echo escapar($fila["id_cita"]); ?></td>
                <td><?php echo escapar($fila["id_paciente"]); ?></td>
                <td><?php echo escapar($fila["fecha_cita"]); ?></td>
                <td><?php echo escapar($fila["hora_cita"]); ?></td> 
                <td><?php echo escapar($fila["id_medico"]); ?></td>
                <td>
                <?php
                  $asignada  = "Asignada";
                  $atendida  = "Atendida";
                  $cancelada = "Cancelada";
                  if (($fila["estado_cita"]) == $asignada) 
                  {
                    echo "<span class=\"asignada\">".$asignada."</span";
                  } 
                  elseif (($fila["estado_cita"]) == $atendida) 
                  {
                    echo "<span class=\"atendida\">".$atendida."</span";
                  } else 
                  {
                    echo "<span class=\"cancelada\">".$cancelada."</span";
                  }
                ?>
                </td>
                <td>
                  <a href="<?= 'editar.php?id_paciente=' . escapar($fila["id_paciente"]) ?>"><i class="fas fa-edit"></i></a>
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