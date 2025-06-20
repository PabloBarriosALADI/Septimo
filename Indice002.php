// Lógica PHP: por ejemplo, condición para mostrar el modal
$mostrar_modal = true; // Puedes cambiarlo según tu lógica
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Modal automático con PHP</title>
    <style>
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.4);
        }

        .modal-content {
            background-color: #fff;
            margin: 10% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 50%;
        }

        .close {
            float: right;
            font-size: 24px;
            font-weight: bold;
            cursor: pointer;
        }
    </style>
</head>
<body>

<h2>Página generada por PHP</h2>

<!-- Modal HTML -->
<div id="modal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="document.getElementById('modal').style.display='none'">&times;</span>
        <?php
        // Puedes cargar contenido dinámico aquí
        echo "<h3>¡Este modal se abrió automáticamente!</h3>";
        echo "<p>Contenido generado por PHP.</p>";
        ?>
    </div>
</div>

<script>
    // Ejecuta esto al cargar la página
    window.onload = function() {
        document.getElementById("modal").style.display = "block";
    };
</script>

</body>
</html>