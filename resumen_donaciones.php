<?php
// resumen_donaciones.php

// Verifica si la sesion está iniciada
session_start();

// Se incluye conexión a la base de datos
require 'conexion_base_de_datos.php';

// Consulta avanzada que trae proyectos con mas de 2 donaciones
//Hace un JOIN entre PROYECTO y DONACION, suma el total de donaciones
// y filtra para mostrar solo los proyectos con mas de 2 donaciones
$sql = "
    SELECT
      p.id_proyecto,
      p.nombre,
      p.descripcion,
      p.presupuesto,
      p.fecha_inicio,
      p.fecha_fin,
      COUNT(d.id_donacion)     AS num_donaciones,
      COALESCE(SUM(d.monto),0) AS total_donaciones
    FROM PROYECTO p
    LEFT JOIN DONACION d 
      ON p.id_proyecto = d.id_proyecto
    GROUP BY p.id_proyecto
    HAVING COUNT(d.id_donacion) > 2
";

// Ejecuta la consulta y verifica errores
$result = mysqli_query($conn, $sql);
if (!$result) {
    // Detiene la ejecucion y mostrar mensaje si falla la consulta
    die("Error al obtener resumen de donaciones: " . mysqli_error($conn));
}

// Cierra la conexion cuando ya no necesitamos mas datos tras la consulta
mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Resumen de Donaciones</title>
  <link rel="stylesheet" href="Styles.css">
  <style>

    body { background: #e6f5e6; font-family: Arial, sans-serif; }
    h1 { text-align: center; color: #006400; margin-top: 30px; }
    table {
      width: 90%; max-width: 900px; margin: 20px auto;
      border-collapse: collapse;
    }
    th, td {
      border: 2px solid #003366;
      padding: 8px;
      text-align: center;
    }
    th {
      background: #eaf0f5; color: #003366;
    }
    /* Alternar fondo en filas pares */
    tr:nth-child(even) td { background: #f9f9f9; }
    /* Enlace volver */
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

  <!-- Título de la sección -->
  <h1>Proyectos con Más de 2 Donaciones</h1>

  <!-- Tabla con los resultados de la consulta -->
  <table>
    <thead>
      <tr>
        <th>ID Proyecto</th>
        <th>Nombre</th>
        <th>Descripción</th>
        <th>Presupuesto</th>
        <th>Inicio</th>
        <th>Fin</th>
        <th># Donaciones</th>
        <th>Total Donado</th>
      </tr>
    </thead>
    <tbody>
      <?php 
      // Recorre cada fila del resultado
      while ($p = mysqli_fetch_assoc($result)): ?>
        <tr>
          <!-- Muestra cada campo -->
          <td><?= htmlspecialchars($p['id_proyecto']) ?></td>
          <td><?= htmlspecialchars($p['nombre']) ?></td>
          <td><?= htmlspecialchars($p['descripcion']) ?></td>
          <td>$<?= number_format($p['presupuesto'], 0, ',', '.') ?></td>
          <td><?= htmlspecialchars($p['fecha_inicio']) ?></td>
          <td><?= htmlspecialchars($p['fecha_fin']) ?></td>
          <td><?= $p['num_donaciones'] ?></td>
          <td>$<?= number_format($p['total_donaciones'], 0, ',', '.') ?></td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>

  <!-- Enlace para volver a la página principal -->
  <div class="volver">
    <a href="index.php">&larr; Volver al Inicio</a>
  </div>

</body>
</html>
