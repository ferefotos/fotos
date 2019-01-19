<?php
header("Content-Type: text/html; charset=utf-8");
define("DBHOST", "localhost");
define("DBUSER", "root");
define("DBPASS", "");
define("DBNAME", "fotos");
$dbconn = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME) or die('Kapcsolódási hiba (' . mysqli_connect_errno() . ') ' . mysqli_connect_error());
mysqli_query($dbconn, "SET NAMES utf8");
?>