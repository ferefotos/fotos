<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>form</title>
</head>
<body>
<br>

<?php
require('file.php');

 /**/
if (isset($_POST['upload'])) {
    // itt kell vizsgálni a mezőket, majd ha minden OK, mehet az adattáblába.
    echo "név: " . $_POST['nev'] . "<br>";
    //echo "kép: " . $_POST['filename'];
    



    // a végén át kellene nevezni......
    if(isset($_POST['filename']) && $_POST['filename']!="")
    rename("users/".$_POST['filename'], "users/ujfajl.jpg");


} elseif (isset($_POST['reset'])) {
    if($_POST['filename'] != ""){
    unlink("users/" . $_POST['filename']);
    }
    //header("Location: gallery.php");
    echo "itt meg kell adni, hova kerüljön a reset megnyomása után!!!";

} else {
    //Kép feltöltés
    if (isset($_FILES['foto'])) {
        /* Ha újra választ képet, a korábban feltöltött fájlt le kell törölni, mielőtt az újat feltölti.*/
        if ($_POST['filename'] != "") {
            unlink("users/" . $_POST['filename']);
        }
                /*!!!!!! user fotók nevét be kell állítani!!!!*/
        $result = upload("users/", date("U").$_FILES['foto']['name']);
        if (!$result['error']) {
            $foto = $result['file'];
            resize("users/" . $foto, "users/" . $foto, 250);              
        } 
        else{$errors[] = $result['hiba'];}                  
    }  
}
//Hibalista előkészítése
if(isset($errors)){   
    $hibalista = "<ul>\n";
    foreach ($errors as $hiba) {
        $hibalista .= "<li>{$hiba}</li>\n";
    }
    $hibalista .= "</ul>\n";
}  

?>
<div id="kepfeltoltes">
<form action="" method="post" id="upload" enctype="multipart/form-data">
<!--A file inputnál Onchange eseményre megy a submit, nem kell gombot nyomni ahhoz, hogy feltöltse a képet.
Így az űrlapon előnézeti képet láthatunk. Mivel az űrlap elküldésekor űjra tölt az oldal,
a már feltöltött fájl nevét be kell írni egy rejtett input mezőbe. így lehet biztosítani, hogy az űrlap elküldésekor
a kép adat a többi adattal együtt kezelve kerüljön az adatbázisba.-->
<p>
    <label for="foto">Képfeltöltés:</label><br>
    <?php if (isset($hibalista)) echo $hibalista;?>
    <br>
    <input type="hidden" name="filename" value="<?php if (isset($foto)) echo $foto ?>">
    <input type="file" id="foto" name="foto" onchange="this.form.submit()"><br>
    <label for="nev">Név</label>
    <input type="text" name="nev" id="nev">
    <input type="submit" name="upload" id="upload" value="feltölt"></p>
    <input type="submit" name="reset" id="reset" value="Mégsem">   
</p>

<br>
<img src="<?php if (isset($foto)) echo "users/" . $foto ?>" alt=""><br>

</div>


</body>
</html>