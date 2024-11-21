const btnAddDay = document.getElementById('btnAddDay');
const scheduleContainer = document.getElementById('scheduleContainer');
const selectServicio = document.getElementById('select-servicio');
const selectUsuario = document.getElementById('select-usuario');
const selectEstado = document.getElementById('select-estado');
const btnSearch = document.getElementById('btn-search');
const fechaReserva = document.getElementById('fecha-reserva');

document.addEventListener('DOMContentLoaded', async () => {
    const hoy = new Date();
    const fechaFormateada = hoy.toISOString().split('T')[0];
    fechaReserva.value = fechaFormateada;
    console.log(fechaFormateada)
    await ObtenerUsuarios();
    await ObtenerServicios();
    await ObtenerReservas();
});

btnSearch.addEventListener('click', async () => {
    await ObtenerReservas();
});

async function ObtenerReservas(){
    try {
        const response = await fetch(`${apiUrl}reservas.php?idUsuario=${selectUsuario.value}&idServicio=${selectServicio.value}&estado=${selectEstado.value}&fecha=${fechaReserva.value}`);
        const data = await response.json();
        
        const tableBody = document.querySelector("#dataTable tbody");
        tableBody.innerHTML = ""; // Limpia la tabla antes de llenarla de nuevo

        data.forEach(item => {
            const row = document.createElement("tr");
            row.innerHTML = `
                <td>${item.idReserva}</td>
                <td>${item.fechaCreacionReserva}</td>
                <td>${item.fechaReserva}</td>
                <td>${item.horaInicio} - ${item.horaFin}</td>
                <td>${item.servicio}</td>
                <td>${item.usuario}</td>
                <td>${item.barbero}</td>
                <td>
                    ${crearEstiloEstado(item.estado)}
                </td>
                <td>
                    ${crearBotonesOpciones(item.estado, item.idReserva)}
                </td>
            `;
            tableBody.appendChild(row);
        });
    } catch (error) {
        console.error("Error al cargar los datos:", error);
    }
}

function crearEstiloEstado(estado){
    switch (estado) {
        case "PENDIENTE":
            return `<span style="background-color: #ffd367; color: white; padding: 5px; border-radius: 5px;">${estado}</span>`;
        case "CONFIRMADA":
            return `<span style="background-color: #56b2ff; color: white; padding: 5px; border-radius: 5px;">${estado}</span>`;
        case "CANCELADA":
            return `<span style="background-color: #ff5656; color: white; padding: 5px; border-radius: 5px;">${estado}</span>`;
        default:
            return `<span style="background-color: #54f33a; color: white; padding: 5px; border-radius: 5px;">${estado}</span>`;
    }
}

function crearBotonesOpciones(estado, idReserva){
    switch (estado) {
        case "PENDIENTE":
            return `<button class='btn-accion' onclick='confirmarReserva(${idReserva})'><span class="material-icons">check</span></button>
                    <button class='btn-accion' onclick='cancelarReserva(${idReserva})'><span class="material-icons">close</span></button>`;
        case "CONFIRMADA":
            return `<button class='btn-accion' onclick='completarReserva(${idReserva})'><span class="material-icons">check</span></button>
                    <button class='btn-accion' onclick='cancelarReserva(${idReserva})'><span class="material-icons">close</span></button>`;
        default:
            return "";
    }
}

async function confirmarReserva(idReserva){
    await actualizarReserva(idReserva,"CONFIRMADA");
}

async function completarReserva(idReserva){
    await actualizarReserva(idReserva,"COMPLETADA");
}

async function cancelarReserva(idReserva){
    await actualizarReserva(idReserva,"CANCELADA");
}

async function actualizarReserva(id, estado){
    try {
        // Llamada a la API REST con fetch
        const response = await fetch(`${apiUrl}reservas.php?idReserva=${id}&estado=${estado}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json'
            }
        });

        if (response.ok) {
            alert("Reserva actualizada correctamente")
            await ObtenerReservas();
        } else {
            alert("Ocurrió un error al actualizar la reserva")
        }
    } catch (error) {
        alert("Ocurrió un error al actualizar la reserva, póngase en contacto con el administrador")
    }
}

async function ObtenerServicios(){
    try {
        const response = await fetch(`${apiUrl}servicios.php`);
        const data = await response.json();
        data.forEach(opcion => {
            const nuevaOpcion = document.createElement('option');
            nuevaOpcion.value = opcion.idServicio;
            nuevaOpcion.textContent = opcion.nombre;
            selectServicio.appendChild(nuevaOpcion);
        });
    } catch (error) {
        console.error("Error al cargar los datos:", error);
    }
}


async function ObtenerUsuarios(){
    try {
        const response = await fetch(`${apiUrl}usuarios.php`);
        const data = await response.json();
        data.forEach(opcion => {
            const nuevaOpcion = document.createElement('option');
            nuevaOpcion.value = opcion.idUsuario;
            nuevaOpcion.textContent = opcion.nombres;
            selectUsuario.appendChild(nuevaOpcion);
        });
    } catch (error) {
        console.error("Error al cargar los datos:", error);
    }
}