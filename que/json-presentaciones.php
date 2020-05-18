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
    $fhFecha = $_POST['fhfecha'] ? $_POST['fhfecha'] : ($_GET['fhfecha'] ? $_GET['fhfecha'] : false);
    
    
    $terms = explode(" ",$search);
    
    $termino = "";
    
    for($i=0;$i<sizeof($terms);$i++)
    {
        $termino .= " AND tNombre like '%".$terms[$i]."%' ";
    }
    
    $select = "	SELECT * FROM CatPresentaciones WHERE 1=1 ".$termino." ORDER BY eCodPresentacion ASC";

    
            $result = mysql_query($select);
    
    while($row = mysql_fetch_array($result)){
        $response[] = array(
                            "value"=>$row{'eCodPresentacion'},
                            "label"=>$row{'tNombre'}
                            );
    }

    echo json_encode($response);
}
 
//30 puff

?>