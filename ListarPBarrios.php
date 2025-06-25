<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Mi Página</title>
  <h1> Los barrios </h1>
  <link rel="stylesheet" href="./estilos/PHPTabla.css">  
</head>
<body>

<?php
  include "ConexionABase.php";

  echo "<a href='UnRegistro.php' style='display: block; width: fit-content; margin: auto;'><img src='./Resources/ActionInsert.gif' alt='Agregar un Registro'> </a>";

  echo "<div style='display: flex; justify-content: center;'>";
  echo "<table class='tabladatos';'>";
  echo "<tr><th class='TituloTabla'>Correo</th><th class='TituloTabla'>Pais</th><th class='TituloTabla'>Nombre</th><th class='TituloTabla columna-opcional'>Fuente</th><th class='TituloTabla'>Borrar</th></tr>";

class TableRows extends RecursiveIteratorIterator {
  private $rowCount = 0;
  private $columnCount = 0;
  function __construct($it) {
    parent::__construct($it, self::LEAVES_ONLY);
  }

  function current() :string {
    $this->columnCount++;

    switch($this->columnCount){
      case 1:
          return "<td style='width:150px;border:1px solid black;'>" . "<a href=UnRegistro.php?correoelectronico=" . parent::current()  .">" . parent::current() . "</td>"; 
          break;
      case 4:
          return "<td class= 'columna-opcional'; style='width:150px;border:1px solid black;'>" . parent::current(). "</td>";
          break;
      case 5:
          //return "<td style='width:150px;border:1px solid red;'> <a href='eliminar.php?correo=valor>Borrar Registro</a></td>";  
          return "<td style='width:150px;border:1px solid red;'>  <a href='EliminarRegistro.php?correoelectronico=" . parent::current(). "'onclick=\"return confirm('¿Estás seguro de eliminar el registro?');\">" .parent::current() . "</td>";  
          break;
      default:
          return "<td style='width:150px;border:1px solid black;'>" . parent::current(). "</td>";
          break;
      }
    }

/*    if ($this->columnCount==1){
       return "<td style='width:150px;border:1px solid black;'>" . "<a href=UnRegistro.php?correoelectronico=" . parent::current()  .">" . parent::current() . "</td>"; 
    }else{
          if ($this->columnCount == 4) {
              return "<td class= 'columna-opcional'; style='width:150px;border:1px solid black;'>" . parent::current(). "</td>";
          }else{
              return "<td style='width:150px;border:1px solid black;'>" . parent::current(). "</td>";
          }  
    }

  }
*/
  function beginChildren() : void {
    $rowClass = ($this->rowCount % 2 == 0) ? "even" : "odd";  // Alternar clase por fila
    echo "<tr class='$rowClass'>";
    $this->rowCount++;
  }

  function endChildren() : void {
    echo "</tr>" . "\n";
    $this->columnCount = 0;
  }
}

$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$porPagina = 3; // número de registros por página
$offset = ($pagina - 1) * $porPagina;

try {
  $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $totalStmt = $conn->query("SELECT COUNT(*) FROM WSLogin WHERE WSEmail LIKE 'pbar%'");
  $totalFilas = $totalStmt->fetchColumn();
  $totalPaginas = ceil($totalFilas / $porPagina);

  $stmt = $conn->prepare("SELECT WSEMail, WSPais, WSNomContacto, WSFuenteOri, WSEMail As WSEmail2
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
echo "<div class=paginador style=justify-content: center;'>";
for ($i = 1; $i <= $totalPaginas; $i++) {
  echo "<a href='?pagina=$i' style='margin: 0 5px; text-decoration: none;'>$i</a>";
}
//echo"</tr></th>";
echo"</table>";



echo "</div>";
?>
</body>
</html>