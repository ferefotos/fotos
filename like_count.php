<?php 
//Like és kedvenc lekérdezések

//Like és kedvencnek jelölő gombok alap ikonja
$like_img=$like_img_off;
$kedvenc_img=$kedvenc_img_off;

//1. Jelölve van a felhasználó által vagy sem
/*Ha jelölve van, akkor változik az ikon (színe)*/
if(isset($_SESSION['userid'])){
    $sql="SELECT count(*) AS db FROM kedvelesek
    WHERE kedvelo='{$_SESSION['userid']}' 
    AND foto='$file'";
    if($result = mysqli_query($dbconn, $sql)){
        $row = mysqli_fetch_assoc($result);
        if($row['db']==1){
        $like_img=$like_img_on;
        }
    } else {
        $hiba = "MySqli hiba (" . mysqli_errno($dbconn) . "): " . mysqli_error($dbconn) . "\n";
    } 
        
    $sql="SELECT count(*) AS db FROM kedvencek
    WHERE jelolo='{$_SESSION['userid']}' 
    AND filename='$file'";
    if($result = mysqli_query($dbconn, $sql)){
        $row = mysqli_fetch_assoc($result);
        if($row['db']==1){
        $kedvenc_img=$kedvenc_img_on;
        }
    } else {
        $hiba = "MySqli hiba (" . mysqli_errno($dbconn) . "): " . mysqli_error($dbconn) . "\n";
    } 
}
//2. A jelölések száma:
$sql="SELECT count(*) AS db FROM kedvelesek
WHERE foto='$file'";
if($result = mysqli_query($dbconn, $sql)){
    $row = mysqli_fetch_assoc($result);  
    $db_like=$row['db'];
} else {
    $hiba = "MySqli hiba (" . mysqli_errno($dbconn) . "): " . mysqli_error($dbconn) . "\n";
} 

$sql="SELECT count(*) AS db FROM kedvencek
WHERE filename='$file'";
if($result = mysqli_query($dbconn, $sql)){
    $row = mysqli_fetch_assoc($result);
    $db_kedvenc=$row['db'];
} else {
    $hiba = "MySqli hiba (" . mysqli_errno($dbconn) . "): " . mysqli_error($dbconn) . "\n";
} 

?>