document.addEventListener('DOMContentLoaded', function() {
    const mainContent = document.getElementById('main');
    const registrarLink = document.getElementById('registrarLink');
    const verLink = document.getElementById('verLink');

    function cargarContenido(url) {
        fetch(url)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.text();
            })
            .then(html => {
                mainContent.innerHTML = html;
                if (url === 'registrar.php') {
                    inicializarRegistro();
                } else if (url === 'ver.php') {
                    inicializarVer();
                }
            })
            .catch(error => {
                console.error('Error al cargar el contenido:', error);
                mainContent.innerHTML = '<p>Error al cargar el contenido.</p>';
            });
    }

    function inicializarRegistro() {
        const registroForm = document.getElementById('registroForm');
        const mensajeDiv = document.getElementById('mensaje');
        const patenteInput = document.getElementById('patente');
        const choferInput = document.getElementById('chofer');
        const kilometrajeInput = document.getElementById('kilometraje');
        const patenteError = document.getElementById('patenteError');
        const choferError = document.getElementById('choferError');
        const kilometrajeError = document.getElementById('kilometrajeError');

        if (registroForm && mensajeDiv && patenteInput && choferInput && kilometrajeInput && patenteError && choferError && kilometrajeError) {
            registroForm.addEventListener('submit', async function(event) {
                event.preventDefault();

                mensajeDiv.textContent = "";
                mensajeDiv.className = "";
                patenteError.textContent = "";
                choferError.textContent = "";
                kilometrajeError.textContent = "";

                let isValid = true;

                if (patenteInput.value.trim() === "") {
                    patenteError.textContent = "La patente es obligatoria.";
                    isValid = false;
                }

                if (choferInput.value.trim() === "") {
                    choferError.textContent = "El chofer es obligatorio.";
                    isValid = false;
                }

                if (kilometrajeInput.value.trim() === "" || isNaN(kilometrajeInput.value) || parseFloat(kilometrajeInput.value) < 0) {
                    kilometrajeError.textContent = "El kilometraje debe ser un número positivo.";
                    isValid = false;
                }

                if (!isValid) {
                    return;
                }

                const formData = new FormData(this);

                try {
                    const response = await fetch('guardar_registro.php', {
                        method: 'POST',
                        body: formData
                    });

                    if (!response.ok) {
                      const errorText = await response.text();
                      throw new Error(`HTTP error! status: ${response.status} - ${errorText}`);
                    }

                    const data = await response.json();

                    if (data.status === 'success') {
                        mensajeDiv.textContent = data.message;
                        mensajeDiv.className = "mensaje-success";
                        registroForm.reset();
                    } else {
                        throw new Error(data.message || "Error al guardar el registro.");
                    }
                } catch (error) {
                    console.error("Error:", error);
                    mensajeDiv.textContent = "Ocurrió un error: " + error.message;
                    mensajeDiv.className = "mensaje-error";
                }
            });
        } else {
            console.error("Elementos del formulario de registro no encontrados.");
        }
    }


    function inicializarVer() {
        const tablaRegistros = document.getElementById('tablaRegistros');
        const mensajeVer = document.getElementById('mensajeVer');
        const filtroPatenteInput = document.getElementById('filtroPatente');
        const filtrarBtn = document.getElementById('filtrarBtn');
        const paginationContainer = document.getElementById('pagination');
        let registros = [];
        let currentPage = 1;
        const registrosPorPagina = 15;

        if (!tablaRegistros || !mensajeVer || !filtroPatenteInput || !filtrarBtn || !paginationContainer) {
            console.error("Elementos de la tabla de registros no encontrados.");
            return;
        }


        function cargarRegistros(patente = '') {
            mensajeVer.className = "mensaje-cargando";
            let url = 'obtener_registros.php';
            if (patente) {
                url += `?patente=${patente}`;
            }

            fetch(url)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    registros = data;
                    currentPage = 1; // Reiniciar la página al cargar nuevos registros/filtrar
                    mostrarRegistrosPaginados(currentPage);
                })
                .catch(error => {
                    console.error('Error al obtener registros:', error);
                    mensajeVer.textContent = "Error al obtener los registros.";
                    mensajeVer.className = "mensaje-error";
                })
                .finally(() => {
                    cargando = false; // Marcar como no cargando, independientemente del resultado
                    mensajeVer.classList.remove("mensaje-cargando");
                });
        }

        function mostrarRegistrosPaginados(pagina) {
            const inicio = (pagina - 1) * registrosPorPagina;
            const fin = inicio + registrosPorPagina;
            const registrosPagina = registros.slice(inicio, fin);

            const tbody = tablaRegistros.querySelector('tbody');
            tbody.innerHTML = ''; // Limpiar la tabla antes de mostrar nuevos registros

            if (registrosPagina.length === 0) {
                tbody.innerHTML = "<tr><td colspan='5' class='text-center'>No se encontraron registros.</td></tr>";
                paginationContainer.innerHTML = ''; // Limpiar la paginación si no hay registros
                return;
            }

            registrosPagina.forEach(registro => {
                let row = tbody.insertRow();
                row.insertCell().textContent = registro.patente;
                row.insertCell().textContent = registro.chofer;
                row.insertCell().textContent = registro.hora;
                row.insertCell().textContent = registro.kilometraje;
                row.insertCell().textContent = registro.fecha;
            });
            generarPaginacion(pagina);
        }

        function generarPaginacion(pagina) {
            const totalPaginas = Math.ceil(registros.length / registrosPorPagina);
            paginationContainer.innerHTML = '';

            if (totalPaginas <= 1) return;

            let maxPagesToShow = 5;
            let startPage = pagina - Math.floor(maxPagesToShow / 2);
            let endPage = pagina + Math.floor(maxPagesToShow / 2);

            if (startPage < 1) {
                startPage = 1;
                endPage = Math.min(maxPagesToShow, totalPaginas);
            }

            if (endPage > totalPaginas) {
                endPage = totalPaginas;
                startPage = Math.max(1, totalPaginas - maxPagesToShow + 1);
            }

            // Botón "Anterior"
            if (pagina > 1) {
                const prevLink = document.createElement('a');
                prevLink.href = '#';
                prevLink.textContent = 'Anterior';
                prevLink.addEventListener('click', (event) => {
                    event.preventDefault();
                    currentPage--;
                    mostrarRegistrosPaginados(currentPage);
                });
                paginationContainer.appendChild(prevLink); // Añadir "Anterior" al principio
            }

            for (let i = startPage; i <= endPage; i++) {
                const pageLink = document.createElement('a');
                pageLink.href = '#';
                pageLink.textContent = i;
                if (i === pagina) pageLink.classList.add('active');
                pageLink.addEventListener('click', (event) => {
                    event.preventDefault();
                    currentPage = i;
                    mostrarRegistrosPaginados(currentPage);
                });
                paginationContainer.appendChild(pageLink);
            }

            // Botón "Siguiente"
            if (pagina < totalPaginas) {
                const nextLink = document.createElement('a');
                nextLink.href = '#';
                nextLink.textContent = 'Siguiente';
                nextLink.addEventListener('click', (event) => {
                    event.preventDefault();
                    currentPage++;
                    mostrarRegistrosPaginados(currentPage);
                });
                paginationContainer.appendChild(nextLink);
            }
        }

        cargarRegistros();

        filtrarBtn.addEventListener('click', () => {
            const patenteFiltro = filtroPatenteInput.value.trim();
            cargarRegistros(patenteFiltro);
        });

        filtroPatenteInput.addEventListener('keyup', (event) => {
            if (event.key === 'Enter') {
                const patenteFiltro = filtroPatenteInput.value.trim();
                cargarRegistros(patenteFiltro);
            }
        });
    }


    function openNav() {
        document.getElementById("mySidebar").classList.add("abierto");
        document.getElementById("main").classList.add("sidebar-abierto");
    }

    function closeNav() {
        document.getElementById("mySidebar").classList.remove("abierto");
        document.getElementById("main").classList.remove("sidebar-abierto");
    }

    registrarLink.addEventListener('click', function(event) {
        event.preventDefault();
        cargarContenido('registrar.php');
        closeNav();
    });

    verLink.addEventListener('click', function(event) {
        event.preventDefault();
        cargarContenido('ver.php');
        closeNav();
    });

    cargarContenido('registrar.php');
});


document.addEventListener('DOMContentLoaded', function() {
    //Ejemplo de como se podria implementar.
      fetch('obtener_estadisticas.php') // Archivo PHP que obtiene las estadísticas
          .then(response => response.json())
          .then(data => {
              document.getElementById('kilometraje-total').textContent = data.kilometrajeTotal;
              document.getElementById('registros-mes').textContent = data.registrosMes;
          })
          .catch(error => {
              console.error('Error al obtener las estadísticas:', error);
              document.getElementById('kilometraje-total').textContent = "Error al cargar";
              document.getElementById('registros-mes').textContent = "Error al cargar";
          });
  });