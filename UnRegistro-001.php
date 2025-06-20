<!DOCTYPE html>
<html lang="es">
<link rel="stylesheet" href="./estilos/PHPTabla.css">
<head>
  <meta charset="UTF-8">
  <title>Segunda Página</title>
</head>
<body>

<?php
include "ConexionABase.php";

if (isset($_GET['correoelectronico'])) {
    $correoelectronico = htmlspecialchars($_GET['correoelectronico']); // Sanitiza para evitar XSS

try {
  $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $stmt = $conn->prepare("SELECT WSEMail, WSPais, WSNomContacto, WSFuenteOri 
                          FROM  WSLogin 
                          WHERE WSEmail = :correoelectronico");
  $stmt->bindValue(':correoelectronico', $correoelectronico);
  $stmt->execute();
  
  $filas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($filas) > 0) {
            foreach ($filas as $fila) {
                $email = $fila['WSEMail'];
                $pais = $fila['WSPais'];
                $nombre = $fila['WSNomContacto'];
                $fuente = $fila['WSFuenteOri'];
                echo "<table class='tabladatos';'>";
                echo "<tr><th class='TituloCampo'>Correo</th><th>$email</th></tr>";
                echo "<tr><th class='TituloCampo'>Pais</th><th>$pais</th></tr>";
                echo "<tr><th class='TituloCampo'>Nombre</th><th>$nombre</th></tr>";
                echo "<tr><th class='TituloCampo'>Fuente</th><th>$fuente</th></tr>";
                echo "</table>";
            }
    } else {
        echo "No hay resultados.";
    }

} catch(PDOException $e) {
  echo "Error: " . $e->getMessage();  
}
} else {
    echo "No se recibió ningún nombre.";
}
?>

</body>