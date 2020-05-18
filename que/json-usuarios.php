<?

require_once("../cnx/swgc-mysql.php");
require_once("../cls/cls-sistema.php");
session_start();

$response = array();

if($_POST['search'] || $_GET['search']){
    $search = $_POST['search'] ? $_POST['search'] : $_GET['search'];
    $eCodPerfil = $_POST['eCodPerfil'] ? $_POST['eCodPerfil'] : ($_GET['eCodPerfil'] ? $_GET['eCodPerfil'] : false);
    $eCodCliente = $_POST['eCodUsuarioCliente'] ? $_POST['eCodUsuarioCliente'] : ($_GET['eCodUsuarioCliente'] ? $_GET['eCodUsuarioCliente'] : false);
    
    $terms = explode(" ",$search);
    
    $termino = "";
    
    for($i=0;$i<sizeof($terms);$i++)
    {
        $termino .= " AND tNombre like '%".$terms[$i]."%' ";
    }
    
    $select = "	SELECT * ".
					" FROM SisUsuarios".
					" WHERE ".
					" 1=1 ".
                    $termino.
                    ($eCodPerfil ? " AND eCodPerfil = ".$eCodPerfil : "").
                    ($eCodCliente ? " AND eCodCliente = ".$eCodCliente : "").
                    " ORDER BY eCodUsuario ASC";

    
            $result = mysql_query($select);
    
    while($row = mysql_fetch_array($result)){
        
        $response[] = array(
                            "value"=>$row{'eCodUsuario'},
                            "label"=>$row{'tNombre'}.' '.$row{'tApellidos'}
                            );
    }

    echo json_encode($response);
    
}
 
//30 puff

?>