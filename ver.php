<!DOCTYPE html>
<html>
<head>
    <title id="pageTitle">Ver Kilometraje</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <img src="logo_emetel.jpg" alt="Logo Emetel">
        <h1>Registro de Kilometraje</h1>
    </header>
    <div class="main-content">
        <div class="filtros-exportacion-container"> <div class="filtro-container">
                <label for="filtroPatente">Filtrar por Patente:</label>
                <input type="text" id="filtroPatente" placeholder="Ingrese la patente">
                <button id="filtrarBtn" class="btn btn-filtrar">Filtrar</button>
            </div>
            <div class="exportacion-container">
                <a href="exportar_csv.php" class="btn btn-exportar">Exportar todo a CSV</a>
                <form action="exportar_csv.php" method="GET" class="exportar-form">
                    <div class="form-group">
                        <label for="fecha_inicio">Desde:</label>
                        <input type="date" id="fecha_inicio" name="fecha_inicio" required>
                    </div>
                    <div class="form-group">
                        <label for="fecha_fin">Hasta:</label>
                        <input type="date" id="fecha_fin" name="fecha_fin" required>
                    </div>
                    <button type="submit" class="btn btn-exportar-fecha">Exportar por Fecha</button>
                </form>
            </div>
        </div>
        <table id="tablaRegistros">
            <thead>
                <tr>
                    <th data-columna="patente">Patente</th>
                    <th data-columna="chofer">Chofer</th>
                    <th data-columna="hora">Hora</th>
                    <th data-columna="kilometraje">Kilometraje</th>
                    <th data-columna="fecha">Fecha</th>
                </tr>
            </thead>
            <tbody>
                <tr><td colspan='5' class='text-center'>Ingrese una patente para buscar</td></tr>
            </tbody>
        </table>
        <div id="mensajeVer"></div>
        <div id="pagination"></div>
    </div>
    <footer>
        <p>&copy; 2024 Emetel. Todos los derechos reservados.</p>
    </footer>
    <script src="scripts.js"></script>
</body>
</html>