<?php
//procesar_donacion.php

// Inicia o reanuda la sesion para obtener datos del usuario
session_start();

//usa script de conexion segura
require 'conexion_base_de_datos.php';

//Si no ha iniciado sesion, redirige a iniciar sesion
if (empty($_SESSION['donante_nombre'])) {
    header('Location: iniciar_sesion.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_proyecto = intval($_POST['id_proyecto'] ?? 0);
    $monto       = floatval($_POST['monto'] ?? 0);

    if ($id_proyecto <= 0 || $monto <= 0) {
        $error = "Selecciona un proyecto válido e ingresa un monto positivo.";
    } else {
        try {
            // Obtiene id_donante desde el nombre en sesion
            $stmt1 = mysqli_prepare($conn, "SELECT id_donante FROM DONANTE WHERE nombre = ?");
            mysqli_stmt_bind_param($stmt1, "s", $_SESSION['donante_nombre']);
            mysqli_stmt_execute($stmt1);
            mysqli_stmt_bind_result($stmt1, $idDonante);
            if (!mysqli_stmt_fetch($stmt1)) {
                throw new Exception("Donante no encontrado.");
            }
            mysqli_stmt_close($stmt1);

            // Inserta donación
            $fecha = date('Y-m-d');
            $stmt2 = mysqli_prepare($conn,
              "INSERT INTO DONACION (monto, fecha, id_proyecto, id_donante)
               VALUES (?, ?, ?, ?)"
            );
            mysqli_stmt_bind_param($stmt2, "dsii", $monto, $fecha, $id_proyecto, $idDonante);
            mysqli_stmt_execute($stmt2);
            mysqli_stmt_close($stmt2);

            $success = "¡Gracias por tu donación de $$monto al proyecto!";
        } catch (Exception $e) {
            $error = "Error al procesar la donación: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Resultado de Donación</title>
  <link rel="stylesheet" href="Styles.css">
  <style>
    body { background:#e6f5e6; font-family:Arial,sans-serif; }
    h1 { text-align:center; color:#006400; margin-top:30px; }
    .msg { max-width:400px; margin:20px auto; padding:10px; text-align:center;
      border-radius:4px; }
    .msg.error { background:#ffe6e6; color:#900; border:1px solid #900; }
    .msg.success { background:#e6ffe6; color:#060; border:1px solid #060; }
    .links { text-align:center; margin-top:20px; }
    .links a { color:#006400; text-decoration:none; font-weight:bold; }
    .links a:hover { text-decoration:underline; }
  </style>
</head>
<body>

  <h1>Donación</h1>

  <?php if (!empty($error)): ?>
    <div class="msg error"><?= htmlspecialchars($error) ?></div>
  <?php elseif (!empty($success)): ?>
    <div class="msg success"><?= htmlspecialchars($success) ?></div>
  <?php endif; ?>

  <div class="links">
    <a href="index.php">&larr; Volver al Inicio</a>
  </div>

</body>
</html>
