<!DOCTYPE html>
<html>
<head>
    <title id="pageTitle">Registro de Kilometraje</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <img src="logo_emetel.jpg" alt="Logo Emetel">
        <h1>Registro de Kilometraje</h1>
    </header>
    <div class="main-content">
        <form id="registroForm" class="formulario">
            <div class="form-group">
                <label for="patente">Patente:</label>
                <input type="text" id="patente" name="patente" required placeholder="Ej: AA123BB">
                <div class="error-message" id="patenteError"></div>
            </div>
            <div class="form-group">
                <label for="chofer">Chofer:</label>
                <input type="text" id="chofer" name="chofer" required placeholder="Nombre del chofer">
                <div class="error-message" id="choferError"></div>
            </div>
            <div class="form-group">
                <label for="kilometraje">Kilometraje:</label>
                <input type="number" id="kilometraje" name="kilometraje" required placeholder="Kilometraje actual">
                <div class="error-message" id="kilometrajeError"></div>
            </div>
            <div class="form-group">
                <label for="tipo_movimiento">Tipo de Movimiento:</label>
                <select id="tipo_movimiento" name="tipo_movimiento">
                    <option value="entrada">Entrada</option>
                    <option value="salida">Salida</option>
                </select>
            </div>
            <input type="submit" value="Registrar">
            <div id="mensaje"></div>
        </form>
    </div>
    <footer>
        <p>&copy; 2024 Emetel. Todos los derechos reservados.</p>
    </footer>
</body>
</html>