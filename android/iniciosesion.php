<?php
$usuario = $_POST["username"];
$contrasena = $_POST["password"];

// SQL Server Extension Sample Code:
$connectionInfo = array("UID" => "lpalomares@cmdemo", "pwd" => "Pareto20172a", "Database" => "Demo01", "LoginTimeout" => 30, "Encrypt" => 1, "TrustServerCertificate" => 0);
$serverName = "tcp:cmdemo.database.windows.net,1433";
$conn = sqlsrv_connect($serverName, $connectionInfo);
if (!$conn){
    die(print_r( sqlsrv_errors(), true));
}

$tsql = "SELECT * FROM tbusers WHERE fsusername = '$usuario' AND fspassword = '$contrasena'";
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