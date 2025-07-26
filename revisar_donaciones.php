<?php
// revisar_donaciones.php

//Revisa que esté iniciada la sesion
session_start();
//Para conectar de manera segura con la base de datos
require 'conexion_base_de_datos.php';

// Consulta simple de la tabla DONACION
$sql = "SELECT id_donacion, id_proyecto, id_donante, monto, fecha
        FROM DONACION
        ORDER BY id_donacion ASC";
$result = mysqli_query($conn, $sql);
if (!$result) {
    die("Error al consultar donaciones: " . mysqli_error($conn));
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Revisar Donaciones</title>
  <link rel="stylesheet" href="Styles.css">
  <style>
    body { background: #e6f5e6; font-family: Arial, sans-serif; }
    h1 { text-align: center; color: #006400; margin-top: 30px; }
    table {
      width: 90%; max-width: 800px;
      margin: 20px auto;
      border-collapse: collapse;
    }
    th, td {
      border: 2px solid #003366;
      padding: 8px;
      text-align: left;
    }
    th { background: #eaf0f5; color: #003366; }
    tr:nth-child(even) td { background: #f9f9f9; }
    .volver {
      text-align: center; margin: 20px;
    }
    .volver a {
      color: #006400; text-decoration: none; font-weight: bold;
    }
    .volver a:hover { text-decoration: underline; }
  </style>
</head>
<body>

  <h1>Listado de Donaciones</h1>

  <table>
    <thead>
      <tr>
        <th>ID Donación</th>
        <th>ID Proyecto</th>
        <th>ID Donante</th>
        <th>Monto</th>
        <th>Fecha</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <tr>
          <td><?= htmlspecialchars($row['id_donacion']) ?></td>
          <td><?= htmlspecialchars($row['id_proyecto']) ?></td>
          <td><?= htmlspecialchars($row['id_donante']) ?></td>
          <td>$<?= number_format($row['monto'], 0, ',', '.') ?></td>
          <td><?= htmlspecialchars($row['fecha']) ?></td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>

  <div class="volver">
    <a href="index.php">&larr; Volver al Inicio</a>
  </div>

</body>
</html>
