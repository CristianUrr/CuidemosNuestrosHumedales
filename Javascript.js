// Javascript.js
/*
document.addEventListener("DOMContentLoaded", () => {
    // ******************** Validación Registro ********************
    const formRegistro = document.getElementById("form_registro");
    if (formRegistro) {
      formRegistro.addEventListener("submit", (e) => {
        const nombre    = formRegistro.querySelector("input[name='nombre']").value.trim();
        const password  = formRegistro.querySelector("input[name='password']").value;
        const email     = formRegistro.querySelector("input[name='email']").value.trim();
        const direccion = formRegistro.querySelector("input[name='direccion']").value.trim();
        const telefono  = formRegistro.querySelector("input[name='telefono']").value.trim();
  
        let errorMsg = "";
        if (!nombre || !password || !email || !direccion || !telefono) {
          errorMsg = "Por favor completa todos los campos.";
        } else if (!/^\S+@\S+\.\S+$/.test(email)) {
          errorMsg = "El correo no tiene un formato válido.";
        }
  
        if (errorMsg) {
          e.preventDefault();
          alert(errorMsg);
        }
      });
    }
  
    // ******************** Validación Donación ********************
    const formDonacion = document.getElementById("form_donacion");
    if (formDonacion) {
      formDonacion.addEventListener("submit", (e) => {
        const proyecto = formDonacion.querySelector("select[name='id_proyecto']").value;
        const monto    = parseFloat(formDonacion.querySelector("input[name='monto']").value);
  
        let errorMsg = "";
        if (!proyecto) {
          errorMsg = "Selecciona un proyecto.";
        } else if (isNaN(monto) || monto <= 0) {
          errorMsg = "Ingresa un monto válido (mayor que 0).";
        }
  
        if (errorMsg) {
          e.preventDefault();
          alert(errorMsg);
        }
        // si errorMsg está vacío, NO hay preventDefault → se envía el formulario
      });
    }
  
    // ******************** Validación Agregar Proyecto ********************
    const formProyecto = document.getElementById("form_agregar_proyecto");
    if (formProyecto) {
      formProyecto.addEventListener("submit", (e) => {
        const nombre       = formProyecto.querySelector("input[name='nombre']").value.trim();
        const descripcion  = formProyecto.querySelector("textarea[name='descripcion']").value.trim();
        const presupuesto  = parseInt(formProyecto.querySelector("input[name='presupuesto']").value, 10);
        const fechaInicio  = formProyecto.querySelector("input[name='fecha_inicio']").value;
        const fechaFin     = formProyecto.querySelector("input[name='fecha_fin']").value;
  
        let errorMsg = "";
        if (!nombre || !descripcion || isNaN(presupuesto) || presupuesto <= 0 || !fechaInicio || !fechaFin) {
          errorMsg = "Por favor completa todos los campos correctamente.";
        } else if (new Date(fechaFin) < new Date(fechaInicio)) {
          errorMsg = "La fecha de fin debe ser igual o posterior a la fecha de inicio.";
        }
  
        if (errorMsg) {
          e.preventDefault();
          alert(errorMsg);
        }
      });
    }
  });
  */
  // ******************** Filtrar tabla de eventos ********************
  function filtrarTabla() {
    const input  = document.getElementById("filtro-eventos");
    const filtro = input.value.toLowerCase();
    const filas  = document.querySelectorAll("#tabla-eventos tbody tr");
  
    filas.forEach(fila => {
      const textoFila = Array.from(fila.cells)
        .map(td => td.textContent.toLowerCase())
        .join(" ");
      fila.style.display = textoFila.includes(filtro) ? "" : "none";
    });
  }
  