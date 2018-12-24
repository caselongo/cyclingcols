<?php 
 // define('AJAX_REQUEST', isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
 // if(!AJAX_REQUEST) {die();}
  
header("Content-Type: application/json; charset=UTF-8");

$conn = new mysqli("localhost", "root", "", "cyclingcols");
$conn->set_charset("utf8");

$outp = array();

$stmt = $conn->prepare("CALL getColsForSearch()");
$stmt->execute();
$result = $stmt->get_result();
$outp = $result->fetch_all(MYSQLI_ASSOC);

echo json_encode($outp);
?>