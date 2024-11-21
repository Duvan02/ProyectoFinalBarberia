const items = document.querySelectorAll(".section-card-report");
const fechaInicio =document.getElementById("fecha-inicio");
const fechaFin =document.getElementById("fecha-fin");

document.addEventListener('DOMContentLoaded', async () => {
    const today = new Date();
    const firstDay = new Date(today.getFullYear(), today.getMonth(), 1);
    const firstDayFormatted = firstDay.toISOString().split('T')[0];
    const currentDayFormatted = today.toISOString().split('T')[0];
    fechaInicio.value = firstDayFormatted;
    fechaFin.value = currentDayFormatted;
    await getReports();
});

function objectToArray(obj) {
    const array = []; // Array donde se almacenarán los elementos

    // Recorrer el objeto usando un bucle `for...in`
    for (const key in obj) {
        if (obj.hasOwnProperty(key)) { // Asegurarse de que sea una propiedad directa
            array.push(obj[key]); // Agregar un objeto clave-valor
        }
    }
    return array;
}

async function getReports(){
    try {
        const response = await fetch(`${apiUrl}reportes.php?fechaInicio=${fechaInicio.value}&fechaFin=${fechaFin.value}`);
        const data = await response.json();
        let reports = objectToArray(data)
        for (let i = 0; i < items.length; i++) {
            items[i].querySelector(".item-value").innerHTML = reports[i]
        }
    } catch (error) {
        console.error("Error al cargar los datos:", error);
    }
}