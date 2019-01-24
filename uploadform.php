<?php
session_start();
require("upload.php");
require("connect.php");

if (isset($_POST['feltolt'])) {
    function tisztit($dbconn, $text){
        return mysqli_real_escape_string($dbconn, stripslashes(strip_tags(trim($text))));
    }
    $cim = tisztit($dbconn, $_POST['cim']);
    $leiras = tisztit($dbconn, $_POST['leiras']);
    $kategoria = $_POST['kategoria'];
    $zar = tisztit($dbconn, $_POST['zar']);
    $blende = tisztit($dbconn, $_POST['blende']);
    $iso = tisztit($dbconn, $_POST['iso']);
    $fokusz = tisztit($dbconn, $_POST['fokusz']);
    $cam = tisztit($dbconn, $_POST['cam']);
    $obi = tisztit($dbconn, $_POST['obi']);
    $datum = $_POST['datum'];
    if (isset($_POST['public'])) {
        $public = 0;
    } else {
        $public = 1;
    }
    $class = rate("kepek/L/" . $_POST['file']);
    //$newfilename = substr_replace($_POST['file'], strtok($_POST['file'], ".") . date('U'), 0, -4);
    $newfilename = substr_replace($_POST['file'], date('U') . rand(100,999), 0, -4);

    if ($kategoria == "") {
        $hiba = "Válassz kategóriát!";
    } else {
        $sql = "INSERT INTO foto 
                VALUES ('{$newfilename}', '{$_SESSION['userid']}', {$kategoria}, '{$cim}', '{$leiras}', '{$blende}',
                 '{$zar}', {$iso}, {$fokusz}, '{$cam}', '{$obi}', '{$datum}', '{$class}', {$public}) ";

        if (mysqli_query($dbconn, $sql)) {
            rename("kepek/" . $_POST['file'], "kepek/" . $newfilename);
            rename("kepek/L/" . $_POST['file'], "kepek/L/" . $newfilename);
        } else {
            $hiba = "MySqli hiba (" . mysqli_errno($dbconn) . "): " . mysqli_error($dbconn) . "\n";
        }
    }
}

if (isset($_POST['reset'])) {
    @unlink("kepek/" . $_POST['file']);
    @unlink("kepek/L/" . $_POST['file']);
}

echo json_encode(
    array(
        "status" => isset($hiba) ? "ERR" : "OK",
        "error" => isset($hiba) ? $hiba : ""
    )
);

?>