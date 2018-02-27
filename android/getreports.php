<?php
$data = json_decode(file_get_contents('php://input'), true);	
$objeto = $data[0]["sku"];

// SQL Server Extension Sample Code:
$connectionInfo = array("UID" => "lpalomares@cmdemo", "pwd" => "Pareto20172a", "Database" => "Demo01", "LoginTimeout" => 30, "Encrypt" => 1, "TrustServerCertificate" => 0);
$serverName = "tcp:cmdemo.database.windows.net,1433";
$conn = sqlsrv_connect($serverName, $connectionInfo);

if (!$conn){
    die(print_r( sqlsrv_errors(), true));
}
$tsql = "SELECT TOP 5 tbs.fssku AS 'SKU', tbr.fsdescription AS 'DESCRIPTION'
	, tbf.fsbase64snap AS 'SNAP', CONVERT(DATETIME, tbr.fsdate, 101) AS 'FECHA' 
	FROM tbreports tbr 
        LEFT JOIN tbsku tbs ON tbs.fsid = tbr.fsskuid 
        LEFT JOIN tbsnaps tbf ON tbf.fsreportid = tbr.fsid 
	WHERE tbr.fsskuid = (SELECT fsid FROM tbsku WHERE fssku = '$objeto') ORDER BY tbr.fsid DESC";
$stmt = sqlsrv_query($conn, $tsql);
if ($stmt === false) {
	$myerror = $tsql;
    die($myerror);
}
$resultado = array();
while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
	array_push($resultado, $row);
}
die(json_encode($resultado));
?>