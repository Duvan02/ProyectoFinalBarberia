document.getElementById('loginForm').addEventListener('submit', async function (e) {
    e.preventDefault(); // Evita el envío del formulario de forma tradicional

    // Obtiene los valores de los campos de entrada
    const email = document.getElementById('username').value;
    const password = document.getElementById('password').value;

    try {
        // Llamada a la API REST con fetch
        const response = await fetch('http://localhost/BarberApp_API/controllers/login.php?tipo=administrador', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ email, password })
        });

        const data = await response.json();
        console.log(response)

        if (response.ok) {
            // Login exitoso
            document.getElementById('successMessage').innerText = data.Message || "Login exitoso";
            document.getElementById('successMessage').style.display = 'block';
            document.getElementById('errorMessage').style.display = 'none';
            localStorage.setItem('isLoggedIn', true);
            window.location.href = "/barberos.html";
        } else {
            // Login fallido
            document.getElementById('errorMessage').innerText = data.Message || "Usuario o contraseña incorrectos";
            document.getElementById('errorMessage').style.display = 'block';
            document.getElementById('successMessage').style.display = 'none';
        }
    } catch (error) {
        console.error('Error al realizar el login:', error);
        document.getElementById('errorMessage').innerText = "Ocurrió un error. Inténtalo nuevamente.";
        document.getElementById('errorMessage').style.display = 'block';
        document.getElementById('successMessage').style.display = 'none';
    }
});