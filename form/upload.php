<?php
require("../config.php");
require("uploadform.php");
/********************************************************
 *           Képfeltöltő űrlap feldolgozása             *
 ********************************************************/
if (isset($_POST['feltolt'])) {
    $cim = tisztit($_POST['cim']);
    $leiras = tisztit($_POST['leiras']);
    $kategoria = $_POST['kategoria'];
    $zar = tisztit($_POST['zar']);
    $blende = tisztit($_POST['blende']);
    $iso = tisztit($_POST['iso']);
    $fokusz = tisztit($_POST['fokusz']);
    $cam = tisztit($_POST['cam']);
    $obi = tisztit($_POST['obi']);
    $datum = $_POST['datum'];
    if (isset($_POST['public']))
        $public = 0;
    else 
        $public = 1;
    
    // kép oldalarány szerinti osztálybesorolása a formázáshoz
    $class = getClass("../photos/" . $_POST['file']);
    // Egyedi fájlnév létrehozása: időbélyeg + random szám
    $newfilename = substr_replace($_POST['file'], date('U') . rand(100,999), 0, -4);
    if (empty($kategoria)) {
        $hiba = "Válassz kategóriát!";
    } else {
       //sql kérés összeállítása 
       $fields="INSERT INTO foto (file, artist, katid, cim, story, blende, zarido, ";
       $values="VALUES ('$newfilename', '{$_SESSION['userid']}', $kategoria, '$cim', '$leiras', '$blende', '$zar',";
       //Az integer tipusú mezőket csak akkor adjuk hozzá, ha nem üresek
       if(!empty($iso)){
            $fields .= "iso, ";
            $values .= $iso.", ";
       }     
       if(!empty($fokusz)){
            $fields .= "focus, ";
            $values .= $fokusz.", ";
       } 
       $fields .="kamera, obi, date, class, public)";
       $values .="'$cam', '$obi', '$datum', '$class', $public)";
       $sql = $fields . $values;
        if (mysqli_query($dbconn, $sql)) {
            //Miután sikeresen felvittük az adatbázisba, átnevezi a fájlt a korábban létrehozott egyedi névre
            rename("../photos/thumbs/" . $_POST['file'], "../photos/thumbs/" . $newfilename);
            rename("../photos/" . $_POST['file'], "../photos/" . $newfilename);
        } else {
            $hiba = "MySqli hiba (" . mysqli_errno($dbconn) . "): " . mysqli_error($dbconn) . "\n";
        }
    }
}
mysqli_close($dbconn);

//A mégsem gombra letörli a fájlokat
if (isset($_POST['reset'])) {
    $hiba=NULL;
    @unlink("../photos/thumbs/" . $_POST['file']);
    @unlink("../photos/" . $_POST['file']);
}

// Válasz az AJAX scriptnek
echo json_encode(
    array(
        "status" => isset($hiba) ? "ERR" : "OK",
        "error" => isset($hiba) ? $hiba : ""
    )
);
?>