<?php 
require("../config.php");
/*******************************************************
 * Kép hozzászólások feldolgozása, adatbázisba mentése *
 *******************************************************/
if(isset($_POST['hozzaszol'])){
    $komment = str_replace("\r\n", "<br>", $_POST['komment']);
    $komment = mysqli_real_escape_string($dbconn, stripslashes(strip_tags(trim($komment), "<br>")));
    $datetime = date("Y-m-d")." ".date("H:i:s");
    if(!empty($komment)){
        $sql="INSERT INTO kommentek
              (ertekelo, kep, komment, datum)
              VALUES ('{$_SESSION['userid']}', '{$_POST['kep']}', '$komment', '$datetime')";
        if(!mysqli_query($dbconn, $sql)){
            $hiba = "MySqli hiba (" . mysqli_errno($dbconn) . "): " . mysqli_error($dbconn) . "\n";
        }
     }
}
mysqli_close($dbconn);
/* AJAX válasz. Visszaküldjük a hozzászólás adatait, és az oldal frissítése nélkül jelenítjük meg*/ 
echo json_encode(
    array(
        "status" => isset($hiba) ? "ERR" : "KOMMENT_OK",
        "komment" => !isset($hiba) ? $komment : "",
        "date" => !isset($hiba) ? $datetime : "",
        "userid" => !isset($hiba) ? $_SESSION['userid'] : "",
        "nev" => !isset($hiba) ? $_SESSION['nev'] : "",
        "pkep" => !isset($hiba) ? $_SESSION['pkep'] : "",
        "error" => isset($hiba) ? $hiba : ""
    )
);
?>