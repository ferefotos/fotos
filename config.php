<?php
session_start();
//header("Content-Type: text/html; charset=utf-8");
define("DBHOST", "localhost");
define("DBUSER", "root");
define("DBPASS", "");
define("DBNAME", "fotos");
$dbconn = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME) or die('Kapcsolódási hiba (' . mysqli_connect_errno() . ') ' . mysqli_connect_error());
//$dbconn = mysqli_connect("localhost", "root", "", "fotos") or die('Kapcsolódási hiba (' . mysqli_connect_errno() . ') ' . mysqli_connect_error());
mysqli_query($dbconn, "SET NAMES utf8");

define ('ROOT_PATH', realpath(dirname(__FILE__)));
//define('DEFAULT_URL', 'http://iskola/_fotos/');
//define('DEFAULT_URL', 'http://localhost/fotos/');
?>