<!DOCTYPE html>
<html lang="es">
<link rel="stylesheet" href="./estilos/PHPTabla.css">
<head>
  <meta charset="UTF-8">
  <title>Segunda Página</title>
</head>
<body>

<?php

function test_input($data) {
  $data = trim((string)$data);
  $data = stripslashes((string)$data);
  $data = htmlspecialchars((string)$data);
  return $data;
}


if (isset($_GET['correoelectronico'])) {
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
                    echo "<table class='tabladatos';'>";
                    echo "<tr><th class='TituloCampo'>País </th><th><input type='text' name='fEmail' value=\"" . (!empty($email)?htmlspecialchars($email): '') . "\"></th></tr>";
                    echo "<tr><th class='TituloCampo'>País </th><th><input type='text' name='fPais' value=\"" . (!empty($pais)?htmlspecialchars($pais): '') . "\"></th></tr>";
                    echo "<tr><th class='TituloCampo'>Nombre </th><th><input type='text' name='fNombre' value=\"" . (!empty($nombre)?htmlspecialchars($nombre): '') . "\"></th></tr>";
                    echo "<tr><th class='TituloCampo'>Fuente: </th><th><input type='text' name='fFuente' value=\"" . (!empty($fuente)?htmlspecialchars($fuente): '') . "\"></th></tr>";
                    echo "</table>";
                    echo '<input type="submit" name="submit" value="Submit">';
                    echo '</form>';      
                }

        } else {
            echo "No hay resultados.";
        }
    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();  
    }   
} else {
    

    $fEmail = $fNombre = $fPais = $fFuente = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fEmail = test_input($_POST["fEmail"]);
    $fPais = test_input($_POST["fPais"]);
    $fNombre = test_input($_POST["fNombre"]);
    $fFuente = test_input($_POST["fFuente"]);
        

    try {
    include "ConexionABase.php";
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

    }
}

?>

</body>