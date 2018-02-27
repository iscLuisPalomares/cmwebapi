<?php
$data = json_decode(file_get_contents('php://input'), true);	
if ($data["key"] != "kreation"){
	die();
}
$msku = $data["sku"];
$mdesc = $data["desc"];
$msnap = $data["snap"];
$muser = $data["user"];
// SQL Server Extension Sample Code:
$connectionInfo = array("UID" => "lpalomares@cmdemo", "pwd" => "Pareto20172a", "Database" => "Demo01", "LoginTimeout" => 30, "Encrypt" => 1, "TrustServerCertificate" => 0);
$serverName = "tcp:cmdemo.database.windows.net,1433";
$conn = sqlsrv_connect($serverName, $connectionInfo);
if (!$conn){
    die(print_r( sqlsrv_errors(), true));
}
$tsql = "
DECLARE @identidad int
INSERT INTO tbreports (fsskuid, fsdescription, fsdate, fsuserid) 
	VALUES (
		(SELECT TOP 1 fsid FROM tbsku WHERE fssku = '$msku')
		, '$mdesc'
		, GETDATE()
		, (SELECT fsid FROM tbusers WHERE fsusername = '$muser')
	) 

SET @identidad = SCOPE_IDENTITY()
INSERT INTO tbsnaps (fsreportid, fsbase64snap) VALUES (@identidad, '$msnap')";
$stmt = sqlsrv_query($conn, $tsql);
if ($stmt === false) {
	$myerror = "{'success':'0'}";
    die($myerror);
}
$resultado = "{'success':'1'}";

die($resultado);

?>