<?

require_once("../cnx/swgc-mysql.php");
require_once("../cls/cls-sistema.php");
include("../inc/fun-ini.php");


$clSistema = new clSis();
session_start();

$bAll = $_SESSION['bAll'];
$bDelete = $_SESSION['bDelete'];

$response = array();

if($_POST['search'] || $_GET['search']){
    $search = $_POST['search'] ? $_POST['search'] : $_GET['search'];
    
    $terms = explode(" ",$search);
    
    $termino = "";
    
    for($i=0;$i<sizeof($terms);$i++)
    {
        $termino .= " AND cc.tNombres like '%".$terms[$i]."%' ";
    }

    $select =   "	SELECT * FROM ( ".
			" 	SELECT  ".
			" 	cc.eCodCliente,  ".
			" 	cc.tNombres tCliente,  ".
			" 	bLibre ".
			" FROM ".
			" 	CatClientes cc ".
            " WHERE 1=1 ".
            $termino.
            " )N1 ".
            " WHERE 1=1 ".
			" ORDER BY N1.eCodCliente ASC";
    
            $result = mysql_query($select);
    
    while($row = mysql_fetch_array($result)){
        $response[] = array("value"=>$row['eCodCliente'],"label"=>$row['tCliente'],"query"=>$select);
    }

    echo json_encode($response);
}
 
?>