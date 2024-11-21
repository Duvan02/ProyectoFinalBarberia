const textSearch = document.getElementById('search-text');
const btnGuardarForm = document.getElementById('btn-guardar');
const btnSearch = document.getElementById('btn-search');
let idUsuario = 0

document.addEventListener('DOMContentLoaded', async () => {
    await ObtenerUsuarios();
});

btnSearch.addEventListener('click', async () => {
    await ObtenerUsuarios();
});

btnGuardarForm.addEventListener('click', async () => {
    if (titleForm.innerHTML == "Editar"){
        await UpdateUser();
    }else{
        await GuardarUsuario();
    }
})

// Manejar envío del formulario a API
async function GuardarUsuario() {
    const formData = new FormData(form);
    const usuarioData = {
        nombre: formData.get('nombres'),
        email: formData.get('email'),
        password: formData.get('password')
    };

    try {
        // Llamada a la API REST con fetch
        const response = await fetch(`${apiUrl}administradores.php`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(usuarioData)
        });

        if (response.ok) {
            alert("Usuario registrado correctamente")
            cerrarModal();
            await ObtenerUsuarios();
        } else {
            alert("Ocurrió un error al registrar el usuario")
        }
    } catch (error) {
        alert("Ocurrió un error al registrar el usuario, póngase en contacto con el administrador")
    }
}

async function ObtenerUsuarios(){
    try {
        const response = await fetch(`${apiUrl}administradores.php?nombre=${textSearch.value}`);
        const data = await response.json();
        
        const tableBody = document.querySelector("#dataTable tbody");
        tableBody.innerHTML = ""; // Limpia la tabla antes de llenarla de nuevo

        data.forEach(item => {
            const row = document.createElement("tr");
            row.innerHTML = `
                <td>${item.idAdministrador}</td>
                <td>${item.nombre}</td>
                <td>${item.email}</td>
                <td>
                    <button class='btn-accion' onclick='editItem(${JSON.stringify(item)})'>Editar</button>
                    <button class='btn-accion' onclick="deleteItem(${item.idAdministrador})">Eliminar</button>
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
    idUsuario = item.idAdministrador;
    document.getElementById("nombres").value = item.nombre;
    document.getElementById("email").value = item.email;
    abrirModal()
}

async function UpdateUser(){
    const formData = new FormData(form);
    const usuarioData = {
        idAdministrador: idUsuario,
        nombre: formData.get('nombres'),
        email: formData.get('email'),
        password: formData.get('password')
    };

    try {
        // Llamada a la API REST con fetch
        const response = await fetch(`${apiUrl}administradores.php`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(usuarioData)
        });

        if (response.ok) {
            alert("Usuario actualizado correctamente")
            cerrarModal();
            await ObtenerUsuarios();
        } else {
            alert("Ocurrió un error al actualizar el usuario")
        }
    } catch (error) {
        alert("Ocurrió un error al actualizar el usuario, póngase en contacto con el administrador")
    }
}

async function deleteItem(id) {
    try {
        if (confirm("¿Está seguro que desea eliminar el registro?")){
            await fetch(`${apiUrl}administradores.php?idAdministrador=${id}`, {
                method: 'DELETE'
            });
            await ObtenerUsuarios();
        }
    } catch (error) {
        console.error("Error al eliminar el elemento:", error);
    }
}

function cerrarModal(){
    cerrarVentanaModal();
}