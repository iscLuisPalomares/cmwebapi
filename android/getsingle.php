<?php
$data = json_decode(file_get_contents('php://input'), true);	

$msku = $data[0]["sku"];
// SQL Server Extension Sample Code:
$connectionInfo = array("UID" => "lpalomares@cmdemo", "pwd" => "Pareto20172a", "Database" => "Demo01", "LoginTimeout" => 30, "Encrypt" => 1, "TrustServerCertificate" => 0);
$serverName = "tcp:cmdemo.database.windows.net,1433";
$conn = sqlsrv_connect($serverName, $connectionInfo);
if (!$conn){
    die(print_r( sqlsrv_errors(), true));
}
$tsql = "SELECT * FROM tbsku WHERE fssku LIKE '%$msku%'";
$stmt = sqlsrv_query($conn, $tsql);  
if ($stmt === false) {
	$myerror = $tsql;
    die($myerror);
}
$resultado = array();
while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
	array_push($resultado, $row);
}
if (count($resultado) == 0){
	die("");
}
die(json_encode($resultado));

?>