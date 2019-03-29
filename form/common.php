<?php
/* A bejelentkezett felhasználónak a nem publikus saját képeit is meg kell jeleníteni a galériában 
   A lekérdezésbe kell illeszteni az alábbi feltételt. */
if (isset($_SESSION['userid'])) {
    $term = "OR artist='{$_SESSION['userid']}' AND public=0";
} else { $term = "";}

//kategóriák lekérdezése a select inputhoz
function kategoriak(){
    global $dbconn;
    $sql = "SELECT * FROM kategoria ORDER BY kategoria";
    return mysqli_query($dbconn, $sql);
}

//űrlap változók tisztítása
function tisztit($text){
    global $dbconn;
    return mysqli_real_escape_string($dbconn, stripslashes(strip_tags(trim($text))));
}

// megszámolja a lájkokat
function db_like($file){
    global $dbconn;
    $sql="SELECT count(*) AS db FROM kedvelesek
      WHERE foto='$file'";
    $result = mysqli_query($dbconn, $sql);
    $row = mysqli_fetch_assoc($result);  
    return $row['db'];
}
// megszámolja hányszor jelölték kedvencnek a képet
function db_kedvenc($file){
    global $dbconn;
    $sql="SELECT count(*) AS db FROM kedvencek
      WHERE filename='$file'";
    $result = mysqli_query($dbconn, $sql);
    $row = mysqli_fetch_assoc($result);  
    return $row['db'];
}
// A bejelentkezett felhasználó lájkolta-e a képet
function kedvelt($file){
    global $dbconn;
    $sql="SELECT count(*) AS db FROM kedvelesek
          WHERE kedvelo='{$_SESSION['userid']}' 
          AND foto='$file'";
    $result = mysqli_query($dbconn, $sql);
    $row = mysqli_fetch_assoc($result);
    return $row['db'];
}
// A bejelentkezett felhasználó kedvencnek jelölte-e a képet
function kedvenc($file){
    global $dbconn;
    $sql="SELECT count(*) AS db FROM kedvencek
          WHERE jelolo='{$_SESSION['userid']}' 
          AND filename='$file'";
    $result = mysqli_query($dbconn, $sql);
    $row = mysqli_fetch_assoc($result);
    return $row['db'];    
}
//Location
function location($url){
    echo "<script type='text/javascript'>document.location.href='{$url}';</script>";
    echo '<META HTTP-EQUIV="refresh" content="0;URL=' . $url . '">';
}
?>