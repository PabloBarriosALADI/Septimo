<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Mi Página</title>
  <h1> Los barrios </h1>
  <link rel="stylesheet" href="./estilos/PHPTabla.css">  <!-- Aquí enlazas tu archivo CSS -->
</head>
<body>

<?php
  echo "<div style='display: flex; justify-content: center;'>";
  echo "<table class='tabladatos';'>";
  echo "<tr><th class='TituloTabla'>Correo</th><th class='TituloTabla'>Pais</th><th class='TituloTabla'>Nombre</th><th class='TituloTabla'>Fuente</th></tr>";

class TableRows extends RecursiveIteratorIterator {
  private $rowCount = 0;
  function __construct($it) {
    parent::__construct($it, self::LEAVES_ONLY);
  }

  function current() :string {
    return "<td style='width:150px;border:1px solid black;'>" . parent::current(). "</td>";
  }

  function beginChildren() : void {
    $rowClass = ($this->rowCount % 2 == 0) ? "even" : "odd";  // Alternar clase por fila
    echo "<tr class='$rowClass'>";
    $this->rowCount++;
  }

  function endChildren() : void {
    echo "</tr>" . "\n";
  }
}

$servername = "localhost";
$username = "root";
$password = "Aladi1980+";
$dbname = "consultaweb";

$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$porPagina = 3; // número de registros por página
$offset = ($pagina - 1) * $porPagina;

try {
  $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $totalStmt = $conn->query("SELECT COUNT(*) FROM WSLogin WHERE WSEmail LIKE 'pbar%'");
  $totalFilas = $totalStmt->fetchColumn();
  $totalPaginas = ceil($totalFilas / $porPagina);

  $stmt = $conn->prepare("SELECT WSEMail, WSPais, WSNomContacto, WSFuenteOri 
                          FROM  WSLogin 
                          WHERE WSEmail 
                          like 'pbar%' 
                          Order by WSEmail
                          LIMIT :limit  OFFSET :offset");
  $stmt->bindValue(':limit', $porPagina, PDO::PARAM_INT);
  $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);                          
  $stmt->execute();

  // set the resulting array to associative
  $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
  foreach(new TableRows(new RecursiveArrayIterator($stmt->fetchAll())) as $k=>$v) {
    echo $v;
  }
} catch(PDOException $e) {
  echo "Error: " . $e->getMessage();
}
$conn = null;
echo "</table>";
echo "</div>";
echo "<br><br>";
echo "<table style='justify-content: center;'>";
//echo "<tr><th>";
echo "<div style='text-align:center; margin-top: 20px; '>";
for ($i = 1; $i <= $totalPaginas; $i++) {
  echo "<a href='?pagina=$i' style='margin: 0 5px; text-decoration: none;'>$i</a>";
}
//echo"</tr></th>";
echo"</table>";



echo "</div>";
?>
</body>
</html>