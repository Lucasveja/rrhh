function formatYear(input) {
    // Función para formatear el campo de periodo (aaaa)
    input.value = input.value.replace(/\D/g, '').slice(0, 4);
}

function verTabla() {
    // Función para mostrar la tabla
    const empresaId = document.getElementById('empresa').value;
    const periodo = document.getElementById('periodo').value;
     
     // Mostrar indicador de carga
     document.getElementById('loadingIndicator').style.display = 'block';
     document.getElementById('tabla_asignar').innerHTML =``;

    if (empresaId === "-1") {
        alert("Debe seleccionar una Empresa.");
        return;
    }

    if (!periodo || !validateYear(periodo)) {
        alert("El periodo debe tener un formato válido (aaaa).");
        return;
    }

    // Cargar la tabla mediante AJAX o fetch aquí
    const url = `asignacion.php?id_emp=${empresaId}&periodo=${periodo}`;
    fetch(url)
        .then(response => response.text())
        .then(html => {
            document.getElementById('loadingIndicator').style.display = 'none';
            document.getElementById('tabla_asignar').innerHTML = html;
        })
        .catch(function(error) {
            console.error('Error en la solicitud fetch:', error);

            // Ocultar indicador de carga en caso de error
            document.getElementById('loadingIndicator').style.display = 'none';
        });
}

function cancelar() {
    // Función para limpiar los campos y ocultar la tabla
    document.getElementById('empresa').value = "-1";
    document.getElementById('periodo').value = "";
    document.getElementById('tabla_asignar').innerHTML = "";
}

function validateYear(year) {
    // Función para validar el formato del año (aaaa)
    return /^\d{4}$/.test(year);
}

function asignar(idE, idEv, id_ev, per, idAsig) {
    // Construye la URL del script PHP que manejará la solicitud
    var url = 'aasignacion.php';

    // Crea los datos que se enviarán en la solicitud
    var data = new URLSearchParams();
    data.append('idE', idE);
    data.append('idEv', idEv);
    data.append('id_ev', id_ev);
    data.append('per', per);
    data.append('idAsig', idAsig);

    // Configura la opción para la solicitud fetch
    var fetchOptions = {
        method: 'POST',
        body: data,
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        }
    };

    // Realiza la solicitud fetch
    fetch(url, fetchOptions)
        .then(function(response) {
            if (!response.ok) {
                throw new Error('La solicitud fetch falló');
            }
            verTabla();
        })
        .then(function(data) {
           // console.log(data); // Maneja la respuesta del servidor
        })
        .catch(function(error) {
            console.error('Error en la solicitud fetch:', error);
        });
}

function cargarMenu() {
    fetch('../../Menu/Menu_Bootstrap.php')
        .then(function(response) {
            if (!response.ok) {
                throw new Error('La solicitud fetch falló');
            }
            return response.text(); // Devuelve el contenido de la respuesta como texto
        })
        .then(function(data) {
            // Insertar el contenido del menú en el contenedor
            document.getElementById('menu').innerHTML = data;
        })
        .catch(function(error) {
            console.error('Error en la solicitud fetch:', error);
        });

        fetch('nombreUS.php')
        .then(function(response) {
            if (!response.ok) {
                throw new Error('La solicitud fetch falló');
            }
            return response.text(); // Devuelve el contenido de la respuesta como texto
        })
        .then(function(data) {
            // Insertar el contenido del menú en el contenedor
            console.log(data);
            document.getElementById('nombreUsuario').innerHTML = data;
        })
}
