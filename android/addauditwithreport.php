<?php
$data = json_decode(file_get_contents('php://input'), true);	
if ($data["key"] != "kreation"){
	//die("hola");
}
$msku = $data[0]["sku"];
$mtipo = $data[0]["tipo"];
$mqty = $data[0]["piezas"];
$maql = $data[0]["aql"];
$mdesc = $data[0]["desc"];
$minspec = $data[0]["inspec"];
$msnap = $data[0]["snap"];
$muser = $data[0]["user"];
// SQL Server Extension Sample Code:
$connectionInfo = array("UID" => "lpalomares@cmdemo", "pwd" => "Pareto20172a", "Database" => "Demo01", "LoginTimeout" => 30, "Encrypt" => 1, "TrustServerCertificate" => 0);
$serverName = "tcp:cmdemo.database.windows.net,1433";
$conn = sqlsrv_connect($serverName, $connectionInfo);
if (!$conn){
    die(print_r( sqlsrv_errors(), true));
}
$tsql = "
DECLARE @idaudit int;  
DECLARE @idreport int; 
INSERT INTO tbaudits (fstype, fsskuid, fsuserid, fsqty, fsinspectqty, fsaql, fsdate) VALUES (
'$mtipo', (SELECT TOP 1 fsid FROM tbsku WHERE fssku = '$msku'), (SELECT fsid FROM tbusers WHERE fsusername = '$muser')
    , '$mqty', '$minspec', '$maql', GETDATE());
SET @idaudit = SCOPE_IDENTITY(); 
INSERT INTO tbreports (fsskuid, fsdescription, fsdate, fsuserid, fsauditid) 
VALUES (
    (SELECT TOP 1 fsid FROM tbsku WHERE fssku = '$msku')
    , '$mdesc'
    , GETDATE()
    , (SELECT fsid FROM tbusers WHERE fsusername = '$muser')
    , @idaudit
);
SET @idreport = SCOPE_IDENTITY();
INSERT INTO tbsnaps (fsreportid, fsbase64snap) VALUES (@idreport, '$msnap');";

$stmt = sqlsrv_query($conn, $tsql);
//validar primer query ejecuta correctamente
if ($stmt === false) {
    echo "{'success':'0'}";
} else {
	$tsql2 = "SELECT TOP 1 fsid FROM tbaudits ORDER BY fsid DESC";
    $stmt2 = sqlsrv_query($conn, $tsql2);
    //validar que ultimo valor insertado regresa correctamente
    if ($tsmt2 === false) {
        echo "{'success':'0'}";
    } else {
        $resultadoarr = array();
        while($row = sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_ASSOC)) {
            array_push($resultadoarr, $row);
        }
        $jres = json_encode($resultadoarr);
        die($jres);
    }
}

?>


