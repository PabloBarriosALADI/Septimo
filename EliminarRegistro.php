</style>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Pagina Eliminar Registro</title>
 
  <link rel="stylesheet" href="./estilos/PHPTabla.css">  
</head>
<body>
     <h1> Eliminación de Registro</h1>
<?php
if (isset($_GET['correoelectronico'])) {
    $correoelectronico = htmlspecialchars($_GET['correoelectronico']); // Sanitiza para evitar XSS

    try {
        echo "Correo Electronico " . $correoelectronico;
        include "ConexionABase.php";
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $conn->prepare("DELETE
                                FROM WSLogin
                                WHERE WSEmail = :fEmail ");
        $stmt->bindValue(':fEmail', $correoelectronico, PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
//        $stmt = 1;
//        if ($stmt == 0) {    
//            echo '<div id="warning-message"></div>';
//            echo '<script>document.getElementById("warning-message").textContent = "Esta es una advertencia personalizada."</script>';
            echo "<script>confirm('Registro actualizado correctamente.'); window.location.href='ListarPBarrios.php';</script>";
            echo '<input type="submit" name="submit" value="Volver" onclick="history.back();">';
            //echo "<script>history.back()</script>";
            // echo "Registro actualizado correctamente.";
        } else {
            echo "<a> No se ha podido eliminar el registro para el correo" . $correoelectronico . "</a>";
            echo "<br>";
            echo '<button onclick="history.back()">Volver</button>';
            //echo "No se actualizó ningún registro.";
        }
           
        //header("Location: " . $_SERVER['HTTP_REFERER']);
    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();  
    }  
}
?>
</body>