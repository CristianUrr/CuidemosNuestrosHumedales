<?php
// iniciar_sesion.php

session_start();

// Mostrar errores en desarrollo (quítalo en producción)
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

require 'conexion_base_de_datos.php';

// Si ya está logueado, redirige al inicio
if (!empty($_SESSION['donante_nombre'])) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recuperar y sanear inputs
    $email    = trim($_POST['email']    ?? '');
    $password = trim($_POST['password'] ?? '');

    // Validación básica
    if ($email === '' || $password === '') {
        $error = "Por favor completa ambos campos.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "El correo no tiene un formato válido.";
    } else {
        try {
            // Preparar consulta
            $stmt = mysqli_prepare(
                $conn,
                "SELECT nombre 
                 FROM DONANTE 
                 WHERE email = ? AND contrasena = ?"
            );
            mysqli_stmt_bind_param($stmt, "ss", $email, $password);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);

            if (mysqli_stmt_num_rows($stmt) === 1) {
                mysqli_stmt_bind_result($stmt, $nombre);
                mysqli_stmt_fetch($stmt);

                // Guardar en sesión
                $_SESSION['donante_nombre'] = $nombre;
                $_SESSION['donante_email']  = $email;

                mysqli_stmt_close($stmt);
                header('Location: index.php');
                exit;
            } else {
                $error = "Email o contraseña incorrectos.";
            }

            mysqli_stmt_close($stmt);
        } catch (Exception $e) {
            $error = "Error al conectar: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Iniciar Sesión</title>
  <link rel="stylesheet" href="Styles.css">
  <style>
    body { background: #e6f5e6; font-family: Arial, sans-serif; }
    .login-wrapper { max-width: 400px; margin: 60px auto; }
    .login-box {
      background: #fff; border-radius: 6px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
      overflow: hidden;
    }
    .login-header {
      background-color: #006400; color: #fff;
      text-align: center; padding: 15px;
      font-size: 1.3em; font-weight: bold;
    }
    .login-body { padding: 20px; }
    .login-body label {
      display: block; margin-bottom: 8px;
      color: #000; font-weight: bold;
    }
    .login-body input {
      width: 100%; padding: 8px; margin-bottom: 15px;
      border: 1px solid #ccc; border-radius: 4px;
      background: #eaf0f5; box-sizing: border-box;
    }
    .login-body button {
      width: 100%; padding: 10px;
      background-color: #006400; color: #fff;
      border: none; border-radius: 4px;
      cursor: pointer; font-size: 1em;
    }
    .login-body button:hover { background-color: #004d00; }
    .msg {
      max-width: 400px; margin: 15px auto;
      padding: 10px; text-align: center;
      border-radius: 4px;
    }
    .msg.error {
      background: #ffe6e6; color: #900;
      border: 1px solid #900;
    }
    .links, .volver {
      text-align: center; margin-top: 15px;
    }
    .links a, .volver a {
      color: #006400; text-decoration: none; font-weight: bold;
    }
    .links a:hover, .volver a:hover { text-decoration: underline; }
  </style>
</head>
<body>

  <?php if (!empty($error)): ?>
    <div class="msg error"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <div class="login-wrapper">
    <div class="login-box">
      <div class="login-header">Iniciar Sesión</div>
      <div class="login-body">
        <form method="POST" action="iniciar_sesion.php">
          <label for="email">Correo electrónico:</label>
          <input type="email" id="email" name="email" required value="<?= htmlspecialchars($email ?? '') ?>">

          <label for="password">Contraseña:</label>
          <input type="password" id="password" name="password" required>

          <button type="submit">Entrar</button>
        </form>
      </div>
    </div>

    <div class="links">
      ¿No tienes cuenta? <a href="registro.php">Regístrate</a>
    </div>
    <div class="volver">
      <a href="index.php">&larr; Volver al Inicio</a>
    </div>
  </div>

</body>
</html>
