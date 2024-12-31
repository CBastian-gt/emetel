<!DOCTYPE html>
<html>
<head>
    <title id="pageTitle">Registro de Kilometraje</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
    <div class="container">
        <button class="openbtn" onclick="openNav()">&#9776; Menú</button>
        <div id="mySidebar" class="sidebar">
            <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
            <ul>
                <!-- <li><a href="inicio.php"><i class="fas fa-home"></i> Inicio</a></li> -->
                <li><a href="#" id="registrarLink"><i class="fas fa-plus"></i> Registrar Kilometraje</a></li>
                <li><a href="#" id="verLink"><i class="fas fa-eye"></i> Ver Kilometraje</a></li>
            </ul>
        </div>
        <div id="main" class="main-content">
        </div>
    </div>

<script src="scripts.js"></script>
<script>
function openNav() {
    document.getElementById("mySidebar").classList.add("abierto");
    document.getElementById("main").classList.add("sidebar-abierto");
}

function closeNav() {
    document.getElementById("mySidebar").classList.remove("abierto");
    document.getElementById("main").classList.remove("sidebar-abierto");
}
</script>

</body>
</html>