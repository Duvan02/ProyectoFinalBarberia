const textSearch = document.getElementById('search-text');
const btnAddDay = document.getElementById('btnAddDay');
const scheduleContainer = document.getElementById('scheduleContainer');
const btnGuardar = document.getElementById('btn-guardar');
const btnSearch = document.getElementById('btn-search');

let idBarbero = 0;



document.addEventListener('DOMContentLoaded', async () => {
    await ObtenerBarberos();
});

btnSearch.addEventListener('click', async () => {
    await ObtenerBarberos();
});

btnAddDay.addEventListener('click', () => {
    crearElementoDia()
});

btnGuardar.addEventListener('click', async () => {
    if (titleForm.innerHTML == "Editar"){
        await UpdateBarbero();
    }else{
        await GuardarBarbero();
    }
});

const diasSemana = [
    'Lunes', 'Martes', 'Miércoles', 'Jueves', 
    'Viernes', 'Sábado', 'Domingo'
];
let diasDisponibles = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 
    'Viernes', 'Sábado', 'Domingo'];

function crearElementoDia() {
    if (diasDisponibles.length === 0) {
        alert('Ya has agregado todos los días de la semana');
        return;
    }

    const diaElement = document.createElement('div');
    diaElement.className = 'schedule-day';
    
    const diaId = `dia_${Date.now()}`;

    diaElement.innerHTML = `
        <button type="button" class="btn-remove-day">
            <span class="material-icons">close</span>
        </button>
        <div class="day-header">
            <select class="day-select" name="${diaId}" required>
                <option value="">Seleccionar día</option>
                    ${diasDisponibles.map(dia => 
                    `<option value="${dia}">${dia}</option>`
                ).join('')}
            </select>
        </div>
        <div class="time-inputs">
            <input type="time" class="time-select" value="08:00" name='entrada_${diaId}' required>
            <span>a</span>
            <input type="time" class="time-select" value="16:00" name='salida_${diaId}' required>
        </div>
    `;

    // Evento para remover día
    const btnRemove = diaElement.querySelector('.btn-remove-day');
    btnRemove.addEventListener('click', () => {
        const select = diaElement.querySelector('select');
        if (select.value) {
            diasDisponibles.push(select.value);
        }
        diaElement.remove();
    });

    // Evento para el select
    const select = diaElement.querySelector('select');
    select.addEventListener('change', (e) => {
        let newValue = e.target.value;
        seleccionarDia(newValue)
        select.dataset.lastValue = newValue;
    });

    scheduleContainer.appendChild(diaElement);
    
    // Animación de entrada
    diaElement.style.opacity = '0';
    diaElement.style.transform = 'translateY(20px)';
    setTimeout(() => {
        diaElement.style.transition = 'all 0.3s ease';
        diaElement.style.opacity = '1';
        diaElement.style.transform = 'translateY(0)';
    }, 10);

    return diaElement;
}

function seleccionarDia(newValue){
    if (newValue) {
        const index = diasDisponibles.indexOf(newValue);
        if (index > -1) {
            diasDisponibles.splice(index, 1);
        }
    }
}

async function GuardarBarbero(){
    const formData = new FormData(form);
        let barberoData = {
            foto: formData.get('foto'),
            nombre: formData.get('nombres'),
            horarios: []
        };

        // Recopilar horarios
        barberoData.horarios = recopilarHorarios()
        
        console.log('Datos del barbero:', barberoData.horarios);

        try {
            // Llamada a la API REST con fetch
            const response = await fetch(`${apiUrl}barberos.php`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(barberoData)
            });
    
            if (response.ok) {
                alert("Barbero registrado correctamente")
                cerrarModal();
                await ObtenerBarberos();
            } else {
                alert("Ocurrió un error al registrar el barbero")
            }
        } catch (error) {
            alert("Ocurrió un error al registrar el barbero, póngase en contacto con el administrador")
        }
        
        // Resetear el formulario y cerrar modal
        modal.classList.remove('active');
        form.reset();
        scheduleContainer.innerHTML = '';
}

function recopilarHorarios(){
    const formData = new FormData(form);
    horarios = []
    scheduleContainer.querySelectorAll('.schedule-day').forEach(diaElement => {
        const diaId = diaElement.querySelector('select').name.split('_')[1];
        const dia = formData.get(`dia_${diaId}`);
        if (dia) {
            horarios.push({
                diaSemana: dia.toLowerCase(),
                horaIngreso: formData.get(`entrada_dia_${diaId}`),
                horaSalida: formData.get(`salida_dia_${diaId}`)
            });
        }
    });
    return horarios
}

async function ObtenerBarberos(){
    try {
        const response = await fetch(`${apiUrl}barberos.php?nombre=${textSearch.value}`);
        const data = await response.json();
        
        const tableBody = document.querySelector("#dataTable tbody");
        tableBody.innerHTML = ""; // Limpia la tabla antes de llenarla de nuevo

        data.forEach(item => {
            const row = document.createElement("tr");
            row.innerHTML = `
                <td>${item.idEstilista}</td>
                <td>${item.nombres}</td>
                <td><img height='100' src='${item.foto}'/></td>
                <td>
                    <button class='btn-accion' onclick='editItem(${JSON.stringify(item)})'>Editar</button>
                    <button class='btn-accion' onclick="deleteItem(${item.idEstilista})">Eliminar</button>
                </td>
            `;
            tableBody.appendChild(row);
        });
    } catch (error) {
        console.error("Error al cargar los datos:", error);
    }
}

async function editItem(item) {
    titleForm.innerHTML = "Editar"
    idBarbero = item.idEstilista;
    document.getElementById("nombres").value = item.nombres;
    document.getElementById("foto").value = item.foto;
    horarios = await GetHorariosBarbero(idBarbero);
    horarios.forEach((h) => {
        let container = crearElementoDia();
        let select = container.querySelector(".day-select");
        let times = container.querySelectorAll(".time-select");
        let dia = capitalizarDia(h.diaSemana)
        select.value = dia;
        times[0].value = h.horaIngreso;
        times[1].value = h.horaSalida;
        seleccionarDia(dia);
    })
    abrirModal()
}

function capitalizarDia(dia){
    const capitalizada = dia.charAt(0).toUpperCase() + dia.slice(1).toLowerCase();
    return capitalizada
}

async function UpdateBarbero(){
    const formData = new FormData(form);
    let barberoData = {
        idBarbero: idBarbero,
        nombre: formData.get('nombres'),
        foto: formData.get('foto'),
        horarios: []
    };

    barberoData.horarios = recopilarHorarios();

    try {
        // Llamada a la API REST con fetch
        const response = await fetch(`${apiUrl}barberos.php`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(barberoData)
        });

        if (response.ok) {
            alert("Barbero actualizado correctamente")
            cerrarModal();
            await ObtenerBarberos();
        } else {
            alert("Ocurrió un error al actualizar el barbero")
        }
    } catch (error) {
        alert("Ocurrió un error al actualizar el barbero, póngase en contacto con el administrador")
    }
}

async function GetHorariosBarbero(idBarbero){
    try {
        const response = await fetch(`${apiUrl}barberos.php/horarios?idBarbero=${idBarbero}`);
        const data = await response.json();
        
        return data;
    } catch (error) {
        console.error("Error al cargar los datos:", error);
        return null;
    }
}

async function deleteItem(id) {
    try {
        if (confirm("¿Está seguro que desea eliminar el barbero?")){
            await fetch(`${apiUrl}barberos.php?idBarbero=${id}`, {
                method: 'DELETE'
            });
            await ObtenerBarberos();
        }
    } catch (error) {
        console.error("Error al eliminar el elemento:", error);
    }
}

function cerrarModal(){
    diasDisponibles = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 
        'Viernes', 'Sábado', 'Domingo']
    scheduleContainer.innerHTML = "";
    cerrarVentanaModal();
}