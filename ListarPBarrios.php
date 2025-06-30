<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Mi Página</title>
  <h1> Los Manolos 20250630</h1>
  <link rel="stylesheet" href="./estilos/PHPTabla.css">  
</head>
<body>

<?php
  function test_input($data) {
    $data = trim((string)$data);
    $data = stripslashes((string)$data);
    $data = htmlspecialchars((string)$data);
    return $data;
  }

  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $submit = $bNombre = "";
    $submit = test_input($_POST["submit"]);
    $bNombre = test_input($_POST["bNombre"]);
  }else{
    $bNombre = isset($_GET['bNombre2']) ? test_input($_GET['bNombre2']) : "";
  }
  

  include "ConexionABase.php";
  echo "<a href='UnRegistro.php' style='display: block; width: fit-content; margin: auto;'><img src='./Resources/ActionInsert.gif' alt='Agregar un Registro'> </a>";
  echo '<form method="post" action="' . htmlspecialchars($_SERVER["PHP_SELF"]) . '">';
  //echo "<tr><th class='TituloCampo'>Nombre buscado</th><th><input type='text' name='bNombre' value=''>
  echo "Nombre buscado<input type='text' name='bNombre' value='$bNombre'>
        <input type='submit' name='submit' value='ir'>";
        // </th></tr>";
  echo '</form>';        
  echo "<div style='display: flex; justify-content: center;'>";
  echo "<table class='tabladatos';'>";
  echo "<tr><th class='TituloTabla'>Correo</th><th class='TituloTabla'>Pais</th><th class='TituloTabla'>Nombre</th><th class='TituloTabla columna-opcional'>Fuente</th><th class='TituloTabla'>Borrar</th></tr>"; //Borrar -TituloTabla

// <a href='ListarPBarrios.php' style='display: block; width: fit-content; margin: auto;'><img src='./Resources/ir.jpeg' alt='Recargar'> </a>

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
          return "<td style='width:150px;border:1px solid red;'> <a href='EliminarRegistro.php?correoelectronico=" . parent::current() . "'onclick=\"return confirm('¿Estás seguro de eliminar el registro?');\"style='display: block; width: fit-content; margin: auto;'><img src='./Resources/DeleteRow.gif' alt='Eliminar el Registro'></a></td>";
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

$porPagina = 5; // número de registros por página
$offset = ($pagina - 1) * $porPagina;

try {
  $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

//  $totalStmt = $conn->query("SELECT COUNT(*) FROM WSLogin WHERE WSEmail LIKE concat(".$bNombre. ",'%'");
//  $totalFilas = $totalStmt->fetchColumn();

  if (!empty($bNombre)){
    $bNombreParam = $bNombre . '%';
    $stmt = $conn->prepare("SELECT COUNT(*) FROM WSLogin WHERE WSEmail LIKE :nombre");
    $stmt->bindValue(':nombre', $bNombreParam, PDO::PARAM_STR);
    $stmt->execute();
    $totalFilas = $stmt->fetchColumn();


    $totalPaginas = ceil($totalFilas / $porPagina);
    $stmt = $conn->prepare("SELECT WSEMail, WSPais, WSNomContacto, WSFuenteOri, WSEMail As WSEmail2
                            FROM  WSLogin 
                            WHERE WSEmail 
                            like :bNombre
                            Order by WSEmail
                            LIMIT :limit  OFFSET :offset");
    $stmt->bindvalue(':bNombre', $bNombreParam, PDO::PARAM_STR);
    $stmt->bindValue(':limit', $porPagina, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);                          
    $stmt->execute();

    // set the resulting array to associative
    $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
    foreach(new TableRows(new RecursiveArrayIterator($stmt->fetchAll())) as $k=>$v) {
      echo $v;
    }
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
if (isset($totalPaginas) && $totalPaginas>0){
  for ($i = 1; $i <= $totalPaginas; $i++) {
    echo "<a href='?pagina=$i&bNombre2=$bNombre' style='margin: 0 5px; text-decoration: none;'>$i</a>";
  }
}
//echo"</tr></th>";
echo"</table>";



echo "</div>";
?>
</body>
</html>