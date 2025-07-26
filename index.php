<?php
// index.php

session_start();
require 'conexion_base_de_datos.php';

// Logout si se accede con ?logout=1
if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header('Location: index.php');
    exit;
}

// 1) Proyectos para el formulario de donación
$proyectos = [];
if (!empty($_SESSION['donante_nombre'])) {
    $res = mysqli_query($conn, "SELECT id_proyecto, nombre FROM PROYECTO");
    while ($fila = mysqli_fetch_assoc($res)) {
        $proyectos[] = $fila;
    }
    mysqli_free_result($res);
}

// 2) Resumen simple de todos los proyectos
$resumenProyectos = mysqli_query($conn,
    "SELECT id_proyecto, nombre, descripcion, presupuesto, fecha_inicio, fecha_fin
     FROM PROYECTO"
);
if (!$resumenProyectos) {
    die("Error al obtener proyectos: " . mysqli_error($conn));
}

mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Cuidemos nuestros Humedales</title>
  <link rel="stylesheet" href="Styles.css">
  <style>
    body { background: #e6f5e6; font-family: Arial, sans-serif; margin:0; padding:0; }
    nav { background: #006400; padding: 10px; text-align: right; }
    nav a, nav span { color: #fff; margin-left: 15px; text-decoration: none; font-weight: bold; }
    nav a:hover { opacity: 0.8; }
    h1, h2 { text-align: center; color: #006400; margin-top: 30px; }
    section { max-width: 900px; margin: 20px auto; padding: 0 10px; }
    ul { list-style: circle; margin: 1rem 0; padding-left: 20px; color: #333; font-size: 1em; }
    table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
    th, td { border: 2px solid #003366; padding: 8px; text-align: center; }
    th { background: #eaf0f5; color: #003366; }
    tr:nth-child(even) td { background: #f9f9f9; }
    /* Formulario de donación */
    .form-wrapper { max-width: 400px; margin: 20px auto; }
    .form-box { background:#fff; border-radius:6px; box-shadow:0 2px 8px rgba(0,0,0,0.1); overflow:hidden; }
    .form-header { background:#006400; color:#fff; text-align:center; padding:15px; font-size:1.3em; font-weight:bold; }
    .form-body { padding:20px; }
    .form-body label { display:block; margin-top:10px; font-weight:bold; color:#000; }
    .form-body select, .form-body input { width:100%; padding:8px; margin-top:5px; border:1px solid #ccc; border-radius:4px; background:#eaf0f5; box-sizing:border-box; }
    .form-body input[type="submit"] { width:100%; padding:10px; margin-top:20px; background:#006400; color:#fff; border:none; border-radius:4px; cursor:pointer; font-size:1em; }
    .form-body input[type="submit"]:hover { background:#004d00; }
    /* Volver */
    .volver { text-align:center; margin:20px; }
    .volver a { color:#006400; text-decoration:none; font-weight:bold; }
    .volver a:hover { text-decoration:underline; }
  </style>
</head>
<body>

  <nav>
    <?php if (!empty($_SESSION['donante_nombre'])): ?>
      <span>Bienvenido, <?= htmlspecialchars($_SESSION['donante_nombre']) ?></span>
      <?php if ($_SESSION['donante_email'] === 'currutiae@gmail.com'): ?>
        <a href="agregar_proyecto.php">Agregar Proyecto</a>
      <?php endif; ?>
      <a href="?logout=1">Cerrar Sesión</a>
    <?php else: ?>
      <a href="iniciar_sesion.php">Iniciar Sesión</a>
      <a href="registro.php">Registrarme</a>
    <?php endif; ?>
  </nav>

  <h1>Cuidemos nuestros Humedales</h1>

  <section id="quienes-somos">
    <h2>Quiénes Somos</h2>
    <p><strong>Misión:</strong> Promover la conciencia ambiental y proteger los humedales a través de actividades comunitarias y educativas.</p>
    <p><strong>Visión:</strong> Ser una organización líder en la conservación de ecosistemas húmedos, fomentando la participación ciudadana y el respeto por la naturaleza.</p>
  </section>

  <section id="actividades">
    <h2>Actividades Realizadas</h2>
    <ul>
      <li><strong>Limpieza del Humedal Batuco:</strong> Más de 50 voluntarios participaron en una jornada de recolección de desechos.</li>
      <li><strong>Cicletada por el Río Maipo:</strong> Evento deportivo para reunir fondos y crear conciencia sobre la contaminación de los ríos.</li>
      <li><strong>Talleres Educativos en Escuelas:</strong> Charlas sobre el valor ecológico de los humedales y cómo protegerlos.</li>
      <li><strong>Plantación de especies nativas:</strong> Actividad en el Humedal Mantagua con familias y estudiantes.</li>
    </ul>
  </section>

  <section id="eventos">
    <h2>Próximos Eventos</h2>
    <input type="text" id="filtro-eventos" onkeyup="filtrarTabla()"
           placeholder="Buscar eventos..." style="width:80%;padding:5px;margin:10px auto;display:block;">
    <table id="tabla-eventos">
      <thead>
        <tr>
          <th>Evento</th><th>Lugar</th><th>Fecha</th><th>Hora</th><th>Detalles</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>Festival del Agua</td>
          <td>Parque Humedal Salinas Chica</td>
          <td>12 de septiembre de 2025</td>
          <td>10:00 AM</td>
          <td><span style="color:blue;">Entrada gratuita ¡Todos invitados!</span></td>
        </tr>
        <tr>
          <td>Marcha por los Humedales</td>
          <td>Plaza Central, Boca Maule</td>
          <td>22 de septiembre de 2025</td>
          <td>9:00 AM</td>
          <td><span style="color:red;">¡Trae tu cartel y súmate a la causa!</span></td>
        </tr>
        <tr>
          <td>Feria Verde Comunitaria</td>
          <td>Humedal Quebrada de Córdova</td>
          <td>5 de octubre de 2025</td>
          <td>11:00 AM</td>
          <td><span style="color:green;">Talleres, comida saludable y juegos ecológicos</span></td>
        </tr>
        <tr>
          <td>Jornada de Plantación Nativa</td>
          <td>Humedal del Río Maipo</td>
          <td>15 de octubre de 2025</td>
          <td>10:30 AM</td>
          <td><span style="color:green;">¡Ayúdanos a reforestar con especies autóctonas!</span></td>
        </tr>
        <tr>
          <td>Seminario sobre Humedales Urbanos</td>
          <td>Centro Cultural de Valdivia</td>
          <td>2 de noviembre de 2025</td>
          <td>3:00 PM</td>
          <td><span style="color:blue;">Expertos nacionales compartirán avances</span></td>
        </tr>
        <tr>
          <td>Caminata Educativa Familiar</td>
          <td>Humedal de Mantagua</td>
          <td>17 de noviembre de 2025</td>
          <td>9:30 AM</td>
          <td><span style="color:brown;">Dinámicas para niños y adultos</span></td>
        </tr>
        <tr>
          <td>Encuentro Nacional de Voluntarios</td>
          <td>Reserva Natural Batuco</td>
          <td>7 de diciembre de 2025</td>
          <td>2:00 PM</td>
          <td><span style="color:purple;">Reconocimientos a voluntarios</span></td>
        </tr>
      </tbody>
    </table>
  </section>

  <section id="resumen-proyectos">
    <h2>Resumen de Proyectos</h2>
    <table id="tabla-resumen-proyectos">
      <thead>
        <tr>
          <th>ID Proyecto</th><th>Nombre</th><th>Descripción</th>
          <th>Presupuesto</th><th>Inicio</th><th>Fin</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($p = mysqli_fetch_assoc($resumenProyectos)): ?>
        <tr>
          <td><?= htmlspecialchars($p['id_proyecto']) ?></td>
          <td><?= htmlspecialchars($p['nombre']) ?></td>
          <td><?= htmlspecialchars($p['descripcion']) ?></td>
          <td>$<?= number_format($p['presupuesto'],0,',','.') ?></td>
          <td><?= htmlspecialchars($p['fecha_inicio']) ?></td>
          <td><?= htmlspecialchars($p['fecha_fin']) ?></td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </section>

  <section id="donaciones">
  <h2>Haz tu donación</h2>

  <?php if (!empty($_SESSION['donante_nombre'])): ?>
    <!-- Si el usuario ha iniciado sesion muestra el formulario -->
    <div class="form-wrapper">
      <div class="form-box">
        <div class="form-header">Formulario de Donación</div>
        <div class="form-body">
          <!-- Formulario que envia los datos a procesar_donacion.php a travez de POST -->
          <form method="POST" action="procesar_donacion.php">
            
            <!-- Selector para llenar el menu que permite seleccionar proyecto, a travez PHP -->
            <label for="proyecto">Proyecto:</label>
            <select name="id_proyecto" id="proyecto" required>
              <option value="">-- Selecciona un proyecto --</option>
              <?php foreach ($proyectos as $pj): ?>
                <!-- Cada opción muestra nombre y guarda el id del proyecto -->
                <option value="<?= $pj['id_proyecto'] ?>">
                  <?= htmlspecialchars($pj['nombre']) ?>
                </option>
              <?php endforeach; ?>
            </select>

            <!-- Para ingresar el monto de la donacion -->
            <label for="monto">Monto de la donación:</label>
            <input
              type="number"
              name="monto"
              id="monto"
              min="1"
              step="0.01"
              placeholder="Ej: 5000"
              required
            >

            <!-- Botón de envio del formulario -->
            <input type="submit" value="Donar">
          </form>
        </div>
      </div>
    </div>

  <?php else: ?>
    <!-- Verifica que la sesion está iniciada -->
    <p style="text-align:center; color:#333;">
      Para donar, por favor
      <a href="iniciar_sesion.php" style="color:#006400; font-weight:bold;">
        inicia sesión
      </a>.
    </p>
  <?php endif; ?>

</section>


  <section id="patrocinador">
    <h2 style="text-align:center; color:#006400;">Patrocinador Oficial</h2>
    <p style="text-align:center;">Gracias al apoyo de:</p>
    <p style="text-align:center;">
      <img src="https://upload.wikimedia.org/wikipedia/commons/f/f9/Instituto_Profesional_IACC.png" alt="Logo IACC" width="200">
    </p>
  </section>

  <footer style="text-align:center; margin:30px 0; color:#555;">
    © 2025 Cuidemos nuestros Humedales. Todos los derechos reservados.
  </footer>

  <script>
    function filtrarTabla() {
      const filtro = document.getElementById('filtro-eventos').value.toLowerCase();
      document.querySelectorAll('#tabla-eventos tbody tr').forEach(tr => {
        tr.style.display = tr.textContent.toLowerCase().includes(filtro) ? '' : 'none';
      });
    }
  </script>

</body>
</html>
