<?php
session_start();
define("DBHOST", "localhost");
define("DBUSER", "root");
define("DBPASS", "");
define("DBNAME", "fotos");
$dbconn = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME) or die('Kapcsolódási hiba (' . mysqli_connect_errno() . ') ' . mysqli_connect_error());
mysqli_query($dbconn, "SET NAMES utf8");
define ('ROOT_PATH', realpath(dirname(__FILE__)));
?>