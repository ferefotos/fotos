<?php
require("../config.php");

/******************************************************
 *  Like és Kedvenc jelölés vagy törlés feldolgozása  *
 ******************************************************/

/* A művelet az oldal újratöltése nélkül, az AJAX technológiával történik */ 

 //Bejelentkezett felhasználó tud lájkolni, vagy kadvencnek jelölni képet
if(isset($_SESSION['userid'])){
    if(isset($_POST['like'])){
        $file = $_POST['liked'];
        //lekérdezése hogy lájkolva van e a felhasználó által
        $sql="SELECT count(*) AS db FROM kedvelesek
              WHERE kedvelo='{$_SESSION['userid']}' 
              AND foto='$file'";
        $eredmeny = mysqli_query($dbconn, $sql);
        $sor = mysqli_fetch_assoc($eredmeny);
        //ha nincs lájkolva, akkor most lájkolta a felhasználó, ha pedig lájkolva volt, akkor visszavonta.
        if($sor['db']==0){
            $sql="INSERT INTO kedvelesek
                  (kedvelo, foto) 
                  VALUES ('{$_SESSION['userid']}', '$file')";
            mysqli_query($dbconn, $sql);
            $response="like_be";
        }
        if($sor['db']==1){
            $sql="DELETE from kedvelesek 
                  WHERE kedvelo='{$_SESSION['userid']}' 
                  AND foto='$file'";
            mysqli_query($dbconn, $sql);
            $response="like_ki";         
        }
        // hányan lájkolták a képet
        $sql="SELECT count(*) AS db FROM kedvelesek
              WHERE foto='$file'";
        $eredmeny = mysqli_query($dbconn, $sql);
        $row = mysqli_fetch_assoc($eredmeny);   
        $db=$row['db'];      
    }
    //Kedvencnek jelölés vagy törlés
    if(isset($_POST['kedvenc'])){
        $file=$_POST['liked'];
        $sql="SELECT count(*) AS db FROM kedvencek
              WHERE jelolo='{$_SESSION['userid']}' 
              AND filename='$file'";
        $eredmeny = mysqli_query($dbconn, $sql);
        $sor = mysqli_fetch_assoc($eredmeny);
        if($sor['db']==0){
            $sql="INSERT INTO kedvencek
                  (jelolo, filename) 
                  VALUES ('{$_SESSION['userid']}', '$file')";
            mysqli_query($dbconn, $sql);
            $response="kedvenc_be";          
        }
        if($sor['db']==1){
            $sql="DELETE from kedvencek 
                  WHERE jelolo='{$_SESSION['userid']}' 
                  AND filename='$file'";
            mysqli_query($dbconn, $sql);
            $response="kedvenc_ki";          
        }
        $sql="SELECT count(*) AS db FROM kedvencek
              WHERE filename='$file'";
        $eredmeny = mysqli_query($dbconn, $sql);
        $row = mysqli_fetch_assoc($eredmeny);   
        $db=$row['db'];
    }
}
mysqli_close($dbconn);

// válasz az AJAX-nak
echo json_encode(
    array(
        "status" => isset($_SESSION['userid']) ? $response : "LOGOUT",
        "db" => isset($db) ? $db : ""
    )
);
?>