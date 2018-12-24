<?php 
  define('AJAX_REQUEST', isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
  if(!AJAX_REQUEST) {die();}

  //--------------------------------------------------------------------------
  // Example php script for fetching data from mysql database
  //--------------------------------------------------------------------------
  $host = "sql9.pcextreme.nl";
  $user = "69432website";
  $pass = "w3bs1t3";

  $databaseName = "69432cyclingcols";

  //--------------------------------------------------------------------------
  // 1) Connect to mysql database
  //--------------------------------------------------------------------------
  //include 'DB.php';
  $con = mysql_connect($host,$user,$pass);
  $dbs = mysql_select_db($databaseName, $con);
  mysql_query("SET character_set_results=utf8", $con);

  //--------------------------------------------------------------------------
  // 2) Query database for data
  //--------------------------------------------------------------------------
  $result = mysql_query("SELECT ColID,ColIDString,Col,Latitude,Longitude,Height FROM cols");          //query
  $cols = array();
  while($res = mysql_fetch_array($result)) {
	$cols[] = $res;
  }

  //--------------------------------------------------------------------------
  // 3) echo result as json 
  //--------------------------------------------------------------------------
  echo json_encode($cols);
?>