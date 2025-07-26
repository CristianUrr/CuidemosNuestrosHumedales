<?php
// agregar_proyecto.php

session_start();
require 'conexion_base_de_datos.php';

// Restringir acceso solo a admin, para que solo el
//pueda agregar proyectos a la base de datos.
if (empty($_SESSION['donante_email']) || $_SESSION['donante_email'] !== 'currutiae@gmail.com') {
    header('Location: index.php');
    exit;
}

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recuperar los datos enviados por POST
    $nombre       = trim($_POST['nombre']       ?? '');
    $descripcion  = trim($_POST['descripcion']  ?? '');
    $presupuesto  = intval($_POST['presupuesto'] ?? 0);
    $fecha_inicio = trim($_POST['fecha_inicio'] ?? '');
    $fecha_fin    = trim($_POST['fecha_fin']    ?? '');

    try {
        // Preparar la sentencia SQL para insertar la BD
            "INSERT INTO PROYECTO (nombre, descripcion, presupuesto, fecha_inicio, fecha_fin)
             VALUES (?, ?, ?, ?, ?)"
        );
        // Vinculamos los datos
        mysqli_stmt_bind_param($stmt, "ssiss",
            $nombre,
            $descripcion,
            $presupuesto,
            $fecha_inicio,
            $fecha_fin
        );
        // Inserta la informacion en la base de datos
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        $success = "Proyecto \"$nombre\" agregado correctamente.";

        // Limpiar variables para evitar repost
        $nombre = $descripcion = $fecha_inicio = $fecha_fin = '';
        $presupuesto = 0;
    } catch (Exception $e) {
        $error = "Error al agregar proyecto: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Agregar Proyecto</title>
  <link rel="stylesheet" href="Styles.css">
  <script src="Javascript.js" defer></script>
  <style>
    body { background: #e6f5e6; font-family: Arial, sans-serif; }
    .project-wrapper { max-width: 400px; margin: 60px auto; }
    .project-box {
      background: #fff; border-radius: 6px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
      overflow: hidden;
    }
    .project-header {
      background-color: #006400; color: #fff;
      text-align: center; padding: 15px;
      font-size: 1.3em; font-weight: bold;
    }
    .project-body { padding: 20px; }
    .project-body label {
      display: block; margin-bottom: 8px;
      color: #000; font-weight: bold;
    }
    .project-body input,
    .project-body textarea {
      width: 100%; padding: 8px; margin-bottom: 15px;
      border: 1px solid #ccc; border-radius: 4px;
      background: #eaf0f5; box-sizing: border-box;
      resize: vertical;
    }
    .project-body button {
      width: 100%; padding: 10px;
      background-color: #006400; color: #fff;
      border: none; border-radius: 4px;
      cursor: pointer; font-size: 1em;
    }
    .project-body button:hover { background-color: #004d00; }
    .msg.error {
      max-width: 400px; margin: 20px auto;
      padding: 10px; background: #ffe6e6;
      color: #900; border: 1px solid #900;
      text-align: center; border-radius: 4px;
    }
    .msg.success {
      max-width: 400px; margin: 20px auto;
      padding: 10px; background: #e6ffe6;
      color: #060; border: 1px solid #060;
      text-align: center; border-radius: 4px;
    }
    .volver {
      text-align: center; margin-top: 15px;
    }
    .volver a {
      color: #006400; text-decoration: none; font-weight: bold;
    }
    .volver a:hover { text-decoration: underline; }
  </style>
</head>
<body>

  <?php if (!empty($error)): ?>
    <div class="msg error"><?= htmlspecialchars($error) ?></div>
  <?php elseif (!empty($success)): ?>
    <div class="msg success"><?= htmlspecialchars($success) ?></div>
  <?php endif; ?>

  <div class="project-wrapper">
    <div class="project-box">
      <div class="project-header">Agregar Nuevo Proyecto</div>
      <div class="project-body">
        <form id="form_agregar_proyecto" method="POST" action="agregar_proyecto.php">
          <label for="nombre">Nombre del Proyecto:</label>
          <input type="text" id="nombre" name="nombre" required value="<?= htmlspecialchars($nombre ?? '') ?>">

          <label for="descripcion">Descripción:</label>
          <textarea id="descripcion" name="descripcion" rows="4" required><?= htmlspecialchars($descripcion ?? '') ?></textarea>

          <label for="presupuesto">Presupuesto (en $):</label>
          <input type="number" id="presupuesto" name="presupuesto" min="1" required value="<?= htmlspecialchars($presupuesto ?? '') ?>">

          <label for="fecha_inicio">Fecha de Inicio:</label>
          <input type="date" id="fecha_inicio" name="fecha_inicio" required value="<?= htmlspecialchars($fecha_inicio ?? '') ?>">

          <label for="fecha_fin">Fecha de Fin:</label>
          <input type="date" id="fecha_fin" name="fecha_fin" required value="<?= htmlspecialchars($fecha_fin ?? '') ?>">

          <button type="submit">Agregar Proyecto</button>
        </form>
      </div>
    </div>

    <div class="volver">
      <a href="index.php">&larr; Volver</a>
    </div>
  </div>

</body>
</html>
