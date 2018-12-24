<?php 
 // define('AJAX_REQUEST', isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
 // if(!AJAX_REQUEST) {die();}
  
header("Content-Type: application/json; charset=UTF-8");

$conn = new mysqli("localhost", "root", "", "cyclingcols");
$conn->set_charset("utf8");

$outp = array();

if (isset($_GET["colid"])) {
	$colid = $_GET["colid"];
	$stmt = $conn->prepare("CALL getTopStats(?)");
	$stmt->bind_param("i", $colid);
	$stmt->execute();
	$result = $stmt->get_result();
	$outp = $result->fetch_all(MYSQLI_ASSOC);
}

echo json_encode($outp);
?>