<?php
require("config.php");
//Lájkolás és törlés
if(isset($_SESSION['userid'])){
    if(isset($_POST['like'])){
        $file=$_POST['liked'];
        $sql="SELECT count(*) AS db FROM kedvelesek
            WHERE kedvelo='{$_SESSION['userid']}' 
            AND foto='$file'";
        if($eredmeny = mysqli_query($dbconn, $sql)){
            $sor = mysqli_fetch_assoc($eredmeny);
            if($sor['db']==0){
                $sql="INSERT INTO kedvelesek
                    (kedvelo, foto) 
                    VALUES ('{$_SESSION['userid']}', '$file')";
                if(mysqli_query($dbconn, $sql)){
                    $response="like_be";
                } else {
                    $hiba = "MySqli hiba (" . mysqli_errno($dbconn) . "): " . mysqli_error($dbconn) . "\n";
                }   
            }
            if($sor['db']==1){
                $sql="DELETE from kedvelesek 
                    WHERE kedvelo='{$_SESSION['userid']}' 
                    AND foto='$file'";
                if(mysqli_query($dbconn, $sql)){
                    $response="like_ki";
                } else {
                    $hiba = "MySqli hiba (" . mysqli_errno($dbconn) . "): " . mysqli_error($dbconn) . "\n";
                }          
            }
        }else {
            $hiba = "MySqli hiba (" . mysqli_errno($dbconn) . "): " . mysqli_error($dbconn) . "\n";
        }    
        
        $sql="SELECT count(*) AS db FROM kedvelesek
            WHERE foto='$file'";
            if($eredmeny = mysqli_query($dbconn, $sql)){
                $row = mysqli_fetch_assoc($eredmeny);   
                $db=$row['db'];
            } else {
                $hiba = "MySqli hiba (" . mysqli_errno($dbconn) . "): " . mysqli_error($dbconn) . "\n";
            }                 
    }
    //Kedvencnek jelölés vagy törlés
    if(isset($_POST['kedvenc'])){
        $file=$_POST['liked'];
        $sql="SELECT count(*) AS db FROM kedvencek
            WHERE jelolo='{$_SESSION['userid']}' 
            AND filename='$file'";
        if($eredmeny = mysqli_query($dbconn, $sql)){
            $sor = mysqli_fetch_assoc($eredmeny);
            if($sor['db']==0){
                $sql="INSERT INTO kedvencek
                    (jelolo, filename) 
                    VALUES ('{$_SESSION['userid']}', '$file')";
                if(mysqli_query($dbconn, $sql)){
                    $response="kedvenc_be";
                } else {
                    $hiba = "MySqli hiba (" . mysqli_errno($dbconn) . "): " . mysqli_error($dbconn) . "\n";
                }         
            }
            if($sor['db']==1){
                $sql="DELETE from kedvencek 
                    WHERE jelolo='{$_SESSION['userid']}' 
                    AND filename='$file'";
                if(mysqli_query($dbconn, $sql)){
                    $response="kedvenc_ki"; 
                } else {
                    $hiba = "MySqli hiba (" . mysqli_errno($dbconn) . "): " . mysqli_error($dbconn) . "\n";
                }              
            }
        } else {
            $hiba = "MySqli hiba (" . mysqli_errno($dbconn) . "): " . mysqli_error($dbconn) . "\n";
        }      
         
        $sql="SELECT count(*) AS db FROM kedvencek
            WHERE filename='$file'";
        if($eredmeny = mysqli_query($dbconn, $sql)){
            $row = mysqli_fetch_assoc($eredmeny);   
            $db=$row['db'];
        } else {
            $hiba = "MySqli hiba (" . mysqli_errno($dbconn) . "): " . mysqli_error($dbconn) . "\n";
        }    
    }
}else{$logout="Jelentkezz be!";}

if(isset($hiba)){
    $response="ERR";
}
mysqli_close($dbconn);
echo json_encode(
    array(
        "status" => isset($logout) ? "LOGOUT" : $response,
        "db" => isset($db) ? $db : "",
        "error" => isset($hiba) ? $hiba : ""
    )
);
?>