<!DOCTYPE html>
<html lang="es">
<link rel="stylesheet" href="./estilos/PHPTabla.css">
<head>
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
  <meta charset="UTF-8">
  <title>Segunda Página</title>
</head>
<body>
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
<?php
//primero compartida
//segundo septimo
//terceri compartida
//cuarto septimo
function test_input($data) {
  $data = trim((string)$data);
  $data = stripslashes((string)$data);
  $data = htmlspecialchars((string)$data);
  return $data;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include "ConexionABase.php";
    $submit = $fEmail = $fNombre = $fPais = $fFuente = "";
    $submit = test_input($_POST["submit"]);
    $fEmail = test_input($_POST["fEmail"]);
    $fPais = test_input($_POST["fPais"]);
    $fNombre = test_input($_POST["fNombre"]);
    $fFuente = test_input($_POST["fFuente"]);
        
    echo "<h2>" .$submit . "</h2><br>";

    if ($submit == 'Editar'){ //Editar
        try {
        
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $conn->prepare("UPDATE WSLogin
                                    SET WSPais = :fPais,
                                        WSNomContacto = :fNombre,
                                        WSFuenteOri = :fFuente
                                    WHERE WSEmail = :fEmail ");
            $stmt->bindValue(':fPais', $fPais, PDO::PARAM_STR);
            $stmt->bindValue(':fNombre', $fNombre, PDO::PARAM_STR);
            $stmt->bindValue(':fFuente', $fFuente, PDO::PARAM_STR);
            $stmt->bindValue(':fEmail', $fEmail, PDO::PARAM_STR);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                echo "Registro actualizado correctamente.";
            } else {
                echo "No se actualizó ningún registro.";
            }
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();  
        }   
    }else{ //Insertar
        try {
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $conn->prepare("INSERT INTO WSLogin (WSEmail, WSPais, WSRegion, WSUsuid, WSNomContacto, WSFuenteOri, WSEmpMujeresYN)
                                    VALUES (:fEmail, :fPais, '', 0, :fNombre, :fFuente, 'N')");
            $stmt->bindValue(':fPais', $fPais, PDO::PARAM_STR);
            $stmt->bindValue(':fNombre', $fNombre, PDO::PARAM_STR);
            $stmt->bindValue(':fFuente', $fFuente, PDO::PARAM_STR);
            $stmt->bindValue(':fEmail', $fEmail, PDO::PARAM_STR);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                echo "Registro agregado correctamente.";
            } else {
                echo "No se agrego ningún registro.";
            }
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();  
        }
    } 
    ?>   
        <script>
        // Ejecuta esto al cargar la página
        window.onload = function() {
        document.getElementById("modal").style.display = "block";
        };
        </script>
    <?php // ver que pasa si se elimina
    header("Location: ./ListarPBarrios.php");
    //header("Location: ./VentanaModal.html" );

    echo "<h2>Your Input:</h2>";
        //echo "<pre>";
            var_dump($stmt);
        //echo "</pre>";
        echo "<br>";
        echo $fEmail;
        echo "<br>";
        echo $fPais;
        echo "<br>";
        echo $fNombre;
        echo "<br>";
        echo $fFuente;

}else {

    if (isset($_GET['correoelectronico'])) { //Recibo correo del contacto a Editar
        $correoelectronico = htmlspecialchars($_GET['correoelectronico']); // Sanitiza para evitar XSS

        try {
            include "ConexionABase.php";
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $conn->prepare("SELECT WSEMail, WSPais, WSNomContacto, WSFuenteOri 
                                FROM  WSLogin 
                                WHERE WSEmail = :correoelectronico");
        $stmt->bindValue(':correoelectronico', $correoelectronico);
        $stmt->execute();
        
        $filas = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (count($filas) > 0) {
            echo '<form method="post" action='. htmlspecialchars($_SERVER["PHP_SELF"]) . '>';
                    foreach ($filas as $fila) {
                        $email = $fila['WSEMail'];
                        $pais = $fila['WSPais'];
                        $nombre = $fila['WSNomContacto'];
                        $fuente = $fila['WSFuenteOri'];
                        echo "<h1>Editar</h1>";
                        echo "<table class='tabladatos';'>";
                        echo "<tr><th class='TituloCampo'>Correo </th><th><input type='text' name='fEmail' value=\"" . (!empty($email)?htmlspecialchars($email): '') . "\"></th></tr>";

                        echo "<tr><th class='TituloCampo'><label for='fPais'>País:</label></th><th>";
                        echo "<select id='fPais' name='fPais'>";
                        $sql = "SELECT p.PaisNombreEsp CodigoPais, p.PaisNombreEsp NombrePais FROM areageoecopaises aep, Paises p where aep.areaGeoEcoCod = 11 and aep.paiscod = p.paiscod";
                        $resultado = $conn->query($sql);

                        // Generar opciones del combo
                        if ($resultado->RowCount() > 0) {
                            while($fila = $resultado->fetch(PDO::FETCH_ASSOC)) {
                                
                                if (trim($fila["CodigoPais"]) == $pais){
                                    echo "<option value='" . $fila["CodigoPais"] . "' selected>" . $fila["NombrePais"] . "</option>";
                                }else{
                                    echo "<option value='" . $fila["CodigoPais"] . "'>" . $fila["NombrePais"] . "</option>";
                                }
                            }
                        } else {
                            echo "<option value=''>No hay datos disponibles</option>";
                        }
                        echo "</select>";
                        echo "<tr><th class='TituloCampo'>Nombre </th><th><input type='text' name='fNombre' value=\"" . (!empty($nombre)?htmlspecialchars($nombre): '') . "\"></th></tr>";
                        echo "<tr><th class='TituloCampo'>Fuente: </th><th><input type='text' name='fFuente' value=\"" . (!empty($fuente)?htmlspecialchars($fuente): '') . "\"></th></tr>";
                        echo "</table>";
                        echo '<input type="submit" name="submit" value="Editar">';
                        echo '<input type="submit" name="submit" value="Cancelar" onclick="history.back();">';
                        echo '</form>';      
                    }

            } else {
                echo "No hay resultados.";
            }
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();  
        }   
    }else{ //No recibo correo y estoy en el modo Insertar
        include "ConexionABase.php";
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        echo '<form method="post" action='. htmlspecialchars($_SERVER["PHP_SELF"]) . '>';
        $email = "";
        $pais = "";
        $nombre = "";
        $fuente = "";
        echo "<h1>Agregar</h1>";
        echo "<table class='tabladatos';'>";
        echo "<tr><th class='TituloCampo'>Correo </th><th><input type='text' name='fEmail' value=\"" . (!empty($email)?htmlspecialchars($email): '') . "\"></th></tr>";
        echo "<tr><th class='TituloCampo'><label for='fPais'>País:</label></th><th>";
        echo "<select id='fPais' name='fPais'>";
        $sql = "SELECT p.PaisNombreEsp CodigoPais, p.PaisNombreEsp NombrePais FROM areageoecopaises aep, Paises p where aep.areaGeoEcoCod = 11 and aep.paiscod = p.paiscod";
        $resultado = $conn->query($sql);

        // Generar opciones del combo
        if ($resultado->RowCount() > 0) {
            while($fila = $resultado->fetch(PDO::FETCH_ASSOC)) {
                echo "<option value='" . $fila["CodigoPais"] . "'>" . $fila["NombrePais"] . "</option>";
            }
        } else {
            echo "<option value=''>No hay datos disponibles</option>";
        }
        echo "<option value=''Selected>Elija una opción</option>";
        echo "</select>";
        echo "<tr><th class='TituloCampo'>Nombre </th><th><input type='text' name='fNombre' value=\"" . (!empty($nombre)?htmlspecialchars($nombre): '') . "\"></th></tr>";
        echo "<tr><th class='TituloCampo'>Fuente: </th><th><input type='text' name='fFuente' value=\"" . (!empty($fuente)?htmlspecialchars($fuente): '') . "\"></th></tr>";
        echo "</table>";
        echo '<input type="submit" name="submit" value="Agregar">';
        echo '<input type="submit" name="submit" value="Cancelar" onclick="history.back();">';
        echo '</form>';      
    }        
}

?>

</body>