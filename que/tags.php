<? header('Content-Type: application/json'); ?>
<?
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(0);
error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);
/* Attempt MySQL server connection. Assuming you are running MySQL
server with default setting (user 'root' with no password) */
include("../cnx/swgc-mysql.php");
include("../inc/fun-ini.php");

session_start();

$categorias = array();

$select = "SELECT * FROM CatCategorias";
$rsCategorias = mysql_query($select);
while($rCategoria = mysql_fetch_array($rsCategorias))
{ $categorias[] = base64_decode($rCategoria{'tNombre'}); }

echo json_encode($categorias);
?>