<?php
session_start();
require("connect.php");

//Bejelentkezés
if (isset($_POST['login'])) {
 // Változók tisztítása
    $userid = mysqli_real_escape_string($dbconn, strip_tags(trim($_POST['userid'])));
    $jelszo = sha1($_POST['jelszo']);

 // Beléptetés
    $sql = "SELECT userid, nev, pkep
            FROM user
            WHERE userid = '{$userid}'
            AND jelszo = '{$jelszo}'
            LIMIT 2";
    if ($eredmeny = mysqli_query($dbconn, $sql)) {
    // Sikeres
        if (mysqli_num_rows($eredmeny) == 1) {
            $_SESSION['userid'] = $userid;
            $sor = mysqli_fetch_assoc($eredmeny);
            $_SESSION['nev'] = $sor['nev'];
            $_SESSION['keresztnev'] = mb_substr($sor['nev'], mb_strpos($sor['nev'], " "));
            $_SESSION['pkep'] = $sor['pkep'];
        }
	// Sikertelen
        else {
            $hiba = "Hibás felhasználónevet vagy jelszót adtál meg!";
        }
    } else {
        $hiba = "MySqli hiba (" . mysqli_errno($dbconn) . "): " . mysqli_error($dbconn) . "\n";
    }        
//Kijelentkezés        
} elseif (isset($_POST['logout'])) {
    $_SESSION = array();
    session_destroy();
}

//Válasz az ajaxnak
echo json_encode(
    array(
        "status" => isset($hiba) ? "ERR" : "OK",
        "error" => isset($hiba) ? $hiba : ""
    )
);

?>