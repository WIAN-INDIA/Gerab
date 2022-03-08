<?php
error_reporting(1);
set_time_limit(0);
 
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 1000");
header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
header("Access-Control-Allow-Methods: PUT, POST, GET, OPTIONS, DELETE");
include("conn.php");

$iType = isset($_GET['type']) ? $_GET['type'] : 0;

/* Linux folder */
$cBackendDir = "/var/www/html/backend/"; 
$cTargetDir = "/var/www/html/backend/files/";
/* Linux folder */

/* Windows folder */
//$cBackendDir = "backend/";
//$cTargetDir = "backend/files/";
/* Windows folder */

$cFileName = basename($_FILES["file"]["name"]);
$cTargetFile = $cTargetDir ."". $cFileName;
$cTmp = $_FILES['file']["tmp_name"];

move_uploaded_file($cTmp,$cTargetFile);

$cQuery = "";

if ($iType == 1) {
	$cQuery = "insert into pdf (name, path, module, uploaded) values ('$cFileName', '$cTargetDir', 'tenaris', now())";
	$cCmd = "python ".$cBackendDir."tenaris.pyc ";
} else if ($iType == 2) {
	$cQuery = "insert into pdf (name, path, module, uploaded) values ('$cFileName', '$cTargetDir', 'heng', now())";
	$cCmd = "python ".$cBackendDir."heng.pyc ";
}

if ($cQuery != "") {
	$mysqli -> query($cQuery);
	$iID = $mysqli -> insert_id;
	$cCmd .= $iID;
} else {
	$iID = 0;
}

ob_start();

$cResponse = '{"fileID": '. $iID .'}';
echo $cResponse;

header('Connection: close');
header('Content-Length: '.ob_get_length());
ob_end_flush();
@ob_flush();
flush();

$mysqli -> close();

if ($iID > 0) {
	$cCmd .= " >> logs/".$iID.".txt";
	exec($cCmd);	
}

exit;
?>