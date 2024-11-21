const textSearch = document.getElementById('search-text');
const btnGuardarForm = document.getElementById('btn-guardar');
const btnSearch = document.getElementById('btn-search');
let idServicio = 0

document.addEventListener('DOMContentLoaded', async () => {
    await ObtenerServicios();
});

btnSearch.addEventListener('click', async () => {
    await ObtenerServicios();
});

btnGuardarForm.addEventListener('click', async () => {
    if (titleForm.innerHTML == "Editar"){
        await UpdateServicio();
    }else{
        await GuardarServicio();
    }
})

// Manejar envío del formulario a API
async function GuardarServicio() {
    const formData = new FormData(form);
    const servicioData = {
        nombre: formData.get('nombre'),
        descripcion: formData.get('descripcion'),
        precio: formData.get('precio'),
        tiempoDuracion: formData.get('tiempo'),
        imagen: formData.get('url-imagen')
    };

    try {
        // Llamada a la API REST con fetch
        const response = await fetch(`${apiUrl}servicios.php`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(servicioData)
        });

        if (response.ok) {
            alert("Servicio registrado correctamente")
            cerrarModal();
            await ObtenerServicios();
        } else {
            alert("Ocurrió un error al registrar el servicio")
        }
    } catch (error) {
        console.error(error)
        alert("Ocurrió un error al registrar el servicio, póngase en contacto con el administrador")
    }
}

async function ObtenerServicios(){
    try {
        const response = await fetch(`${apiUrl}servicios.php?descripcion=${textSearch.value}`);
        const data = await response.json();

        console.log(data);
        
        const tableBody = document.querySelector("#dataTable tbody");
        tableBody.innerHTML = ""; // Limpia la tabla antes de llenarla de nuevo

        data.forEach(item => {
            const row = document.createElement("tr");
            row.innerHTML = `
                <td>${item.idServicio}</td>
                <td>${item.nombre}</td>
                <td>${item.tiempoDuracion} min.</td>
                <td>$ ${item.precio}</td>
                <td><img height='100' src='${item.imagen}'/></td>
                <td>
                    <button class='btn-accion' onclick='editItem(${JSON.stringify(item)})'>Editar</button>
                    <button class='btn-accion' onclick="deleteItem(${item.idServicio})">Eliminar</button>
                </td>
            `;
            tableBody.appendChild(row);
        });
    } catch (error) {
        console.error("Error al cargar los datos:", error);
    }
}

function editItem(item) {
    titleForm.innerHTML = "Editar"
    idServicio = item.idServicio;
    document.getElementById("nombre").value = item.nombre;
    document.getElementById("descripcion").value = item.descripcion;
    document.getElementById("precio").value = item.precio;
    document.getElementById("tiempo").value = item.tiempoDuracion;
    document.getElementById("url-imagen").value = item.imagen;
    abrirModal()
}

async function UpdateServicio(){
    const formData = new FormData(form);
    const servicioData = {
        idServicio: parseInt(idServicio),
        nombre: formData.get("nombre"),
        descripcion: formData.get('descripcion'),
        tiempoDuracion: parseInt(formData.get('tiempo')),
        precio:  parseFloat(formData.get('precio')),
        imagen: formData.get('url-imagen')
    };

    try {
        // Llamada a la API REST con fetch
        const response = await fetch(`${apiUrl}servicios.php`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(servicioData)
        });

        console.log(response)

        if (response.ok) {
            alert("Servicio actualizado correctamente")
            cerrarModal();
            await ObtenerServicios();
        } else {
            alert("Ocurrió un error al actualizar el servicio")
        }
    } catch (error) {
        alert("Ocurrió un error al actualizar el servicio, póngase en contacto con el administrador")
    }
}

async function deleteItem(id) {
    try {
        if (confirm("¿Está seguro que desea eliminar el registro?")){
            await fetch(`${apiUrl}servicios.php?idServicio=${id}`, {
                method: 'DELETE'
            });
            await ObtenerServicios();
        }
    } catch (error) {
        console.error("Error al eliminar el elemento:", error);
    }
}

function cerrarModal(){
    cerrarVentanaModal();
}