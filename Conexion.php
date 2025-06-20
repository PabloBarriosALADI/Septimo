<?php
$servername = "localhost:3306";
$username = "root";
$password = "Aladi1980+";
$dbname = "consultaweb";

try {
  $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
  // set the PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  echo "Connected successfully";
} catch(PDOException $e) {
  echo "Connection failed: " . $e->getMessage();
}  

try{  
  // prepare sql and bind parameters
  $stmt = $conn->prepare("INSERT INTO Usuario (contrasenia, usuario)
  VALUES (:contrasenia, :usuario)");
  $stmt->bindParam(':contrasenia', $contrasenia);
  $stmt->bindParam(':usuario', $usuario);

  // insert a row
  $contrasenia = "John Contrasenia";
  $usuario = "Doe Usuario";
  $stmt->execute();

  echo "New records created successfully";
} catch(PDOException $e) {
  echo "Error: " . $e->getMessage();
}
  

$conn = null;
?>