<?php
//registro.php
session_start();

// Se incluye script de conexión segura a la base de datos
require 'conexion_base_de_datos.php';

// Si ya existe un usuario logueado se redirige a la pagina principal
if (!empty($_SESSION['donante_nombre'])) {
    header('Location: index.php');
    exit;
}

//Procesa el formulario llenado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre    = trim($_POST['nombre']    ?? '');
    $password  = trim($_POST['password']  ?? '');
    $email     = trim($_POST['email']     ?? '');
    $direccion = trim($_POST['direccion'] ?? '');
    $telefono  = trim($_POST['telefono']  ?? '');

    if ($nombre === '' || $password === '' || $email === '' || $direccion === '' || $telefono === '') {
        $error = "Por favor completa todos los campos.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "El correo no tiene un formato válido.";
    } else {
        try {
            $stmt = mysqli_prepare($conn, 
              "INSERT INTO DONANTE (nombre, contrasena, email, direccion, telefono)
               VALUES (?, ?, ?, ?, ?)"
            );
            mysqli_stmt_bind_param($stmt, "sssss",
                $nombre, $password, $email, $direccion, $telefono
            );
            mysqli_stmt_execute($stmt);

            $success = "¡Registro exitoso! Ya puedes <a href='iniciar_sesion.php'>iniciar sesión</a>.";
            mysqli_stmt_close($stmt);
        } catch (Exception $e) {
            $error = "Error al registrar: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Registro de Donante</title>
  <link rel="stylesheet" href="Styles.css">
  <style>
    body { background:#e6f5e6; font-family:Arial,sans-serif; }
    .box { max-width:400px; margin:50px auto; background:#fff; border-radius:6px;
      box-shadow:0 2px 8px rgba(0,0,0,0.1); overflow:hidden; }
    .hdr { background:#006400; color:#fff; text-align:center; padding:15px;
      font-size:1.3em; font-weight:bold; }
    .bd { padding:20px; }
    label { display:block; margin-top:10px; font-weight:bold; }
    input { width:100%; padding:8px; margin-top:5px; border:1px solid #ccc;
      border-radius:4px; background:#eaf0f5; }
    button { width:100%; padding:10px; margin-top:20px; background:#006400;
      color:#fff; border:none; border-radius:4px; cursor:pointer; }
    button:hover { background:#004d00; }
    .msg { max-width:400px; margin:20px auto; padding:10px; text-align:center;
      border-radius:4px; }
    .msg.error { background:#ffe6e6; color:#900; border:1px solid #900; }
    .msg.success { background:#e6ffe6; color:#060; border:1px solid #060; }
    .links { text-align:center; margin-top:15px; }
    .links a { color:#006400; text-decoration:none; font-weight:bold; }
    .links a:hover { text-decoration:underline; }
  </style>
</head>
<body>

  <?php if (!empty($error)): ?>
    <div class="msg error"><?= htmlspecialchars($error) ?></div>
  <?php elseif (!empty($success)): ?>
    <div class="msg success"><?= $success ?></div>
  <?php endif; ?>

  <div class="box">
    <div class="hdr">Registro de Donante</div>
    <div class="bd">
      <form method="POST" action="registro.php">
        <label for="nombre">Nombre completo:</label>
        <input type="text" id="nombre" name="nombre" required value="<?= htmlspecialchars($nombre ?? '') ?>">

        <label for="password">Contraseña:</label>
        <input type="text" id="password" name="password" required value="<?= htmlspecialchars($password ?? '') ?>">

        <label for="email">Correo electrónico:</label>
        <input type="email" id="email" name="email" required value="<?= htmlspecialchars($email ?? '') ?>">

        <label for="direccion">Dirección:</label>
        <input type="text" id="direccion" name="direccion" required value="<?= htmlspecialchars($direccion ?? '') ?>">

        <label for="telefono">Teléfono:</label>
        <input type="tel" id="telefono" name="telefono" required value="<?= htmlspecialchars($telefono ?? '') ?>">

        <button type="submit">Registrarme</button>
      </form>
    </div>
  </div>

  <div class="links">
    ¿Ya tienes cuenta? <a href="iniciar_sesion.php">Inicia Sesión</a><br>
    <a href="index.php">&larr; Volver al Inicio</a>
  </div>

</body>
</html>
