<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Modal desde PHP</title>
    <style>
        /* Estilos básicos para el modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.4);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 10% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 60%;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 24px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>
<body>

<h2>Ejemplo de modal cargando contenido HTML desde PHP</h2>

<!-- Botón que abre el modal -->
<button id="openModal">Abrir Modal</button>

<!-- Modal -->
<div id="myModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <div id="modalBody">
            <!-- Aquí se cargará el contenido HTML -->
        </div>
    </div>
</div>

<script>
// Obtener elementos del DOM
const modal = document.getElementById("myModal");
const btn = document.getElementById("openModal");
const span = document.getElementsByClassName("close")[0];

// Cuando se hace clic en el botón, se carga el HTML con fetch
btn.onclick = function() {
    fetch("contenido.html")
        .then(response => response.text())
        .then(data => {
            document.getElementById("modalBody").innerHTML = data;
            modal.style.display = "block";
        });
}

// Cerrar modal cuando se hace clic en la X
span.onclick = function() {
    modal.style.display = "none";
}

// Cerrar modal al hacer clic fuera del contenido
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}
</script>

</body>
</html>