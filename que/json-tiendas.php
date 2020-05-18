<?

require_once("../cnx/swgc-mysql.php");
require_once("../cls/cls-sistema.php");
include("../inc/fun-ini.php");
include("../inc/cot-clc.php");


$clSistema = new clSis();
session_start();

$response = array();

if($_POST['search'] || $_GET['search']){
    $search = $_POST['search'] ? $_POST['search'] : $_GET['search'];
    
    $terms = explode(" ",$search);
    
    $termino = "";
    
    for($i=0;$i<sizeof($terms);$i++)
    {
        $termino .= " AND tNombre like '%".$terms[$i]."%' ";
    }
    
    $select = "	SELECT *
					FROM CatTiendas".
					" WHERE
					1=1 ".
                    $termino.
                    " ORDER BY eCodTienda ASC";

    
            $result = mysql_query($select);
    
    while($row = mysql_fetch_array($result)){
        
        $response[] = array(
                            "value"=>$row{'eCodTienda'},
                            "label"=>$row{'tNombre'}
                            );
    }

    echo json_encode($response);
}
 
//30 puff

?>