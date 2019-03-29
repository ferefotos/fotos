<?php
require("../config.php");
require('file.php');
require('common.php');
/**************************************************************
 *             Regisztrációs űrlap feldolgozása               *
 **************************************************************/

// Új regisztráció és regisztrációs adatok módosítása: 
if (isset($_POST['elkuld']) && !isset($_POST['delete'])) {
    //változók tisztítása
    $userid = tisztit($_POST['userid']);
    $jelszo = $_POST['jelszo'];
    $jelszo2 = $_POST['jelszo2'];
    $email = strtolower(tisztit($_POST['email']));
    $nev = ucwords(mb_strtolower(tisztit($_POST['nev'])));
    $cam = tisztit($_POST['cam']);
    $lens = tisztit($_POST['lens']);
    $rolam = tisztit($_POST['rolam']);

    //változók vizsgálata
    if (empty($userid))
        $errors[] = "Nem adtál meg felhasználónevet!";
    elseif (strlen($userid) < 6)
        $errors[] = "A felhasználónév minimum 6 karakter legyen!";
    // új regisztráció esetén, vagy adatmódosításnál ha módosította a userid-t    
    elseif(!isset($_SESSION['userid']) || $userid != $_SESSION['userid']){ 
        $sql = "SELECT COUNT(*) AS db FROM user WHERE userid='{$userid}'";
        $eredmeny = mysqli_query($dbconn, $sql);
        $sor = mysqli_fetch_assoc($eredmeny);
        if ($sor['db'] != 0)
            $errors[] = "Foglalt felhasználónév!";
    }
    // Új regisztráció esetén, vagy jelszó módosításnál (ha nem hagyta üresen a jelszó mezőt)
    if (!isset($_SESSION['userid']) || !empty($jelszo) || !empty($jelszo2)) {
        if (empty($jelszo))
            $errors[] = "Nem adtál meg jelszót!";
        elseif (strlen($jelszo) < 8)
            $errors[] = "A jelszó minimum 8 karakter legyen!";
        elseif ($jelszo != $jelszo2)
            $errors[] = "A megismételt jelszó nem egyezik!";
    }    
    if (empty($email))
        $errors[] = "Nem adtál meg e-mail címet!";
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL))
        $errors[] = "Rossz e-mail címet adtál meg!";
    // új regisztráció esetén, vagy adatmódosításnál ha módosította az e-mailt     
    elseif(!isset($_SESSION['userid']) || $email != $_SESSION['email']){
        $sql = "SELECT COUNT(*) AS db FROM user WHERE email='{$email}'";
        $eredmeny = mysqli_query($dbconn, $sql);
        $sor = mysqli_fetch_assoc($eredmeny);
        if ($sor['db'] != 0)
            $errors[] = "Foglalt e-mail cím!";
    }
    if (empty($nev))
        $errors[] = "Nem adtad meg a neved!";
    if (strlen($rolam) > 500)
        $errors[] = "A bemutatkozás max. 500 karakter lehet!"; 

    // adatmódosításnál meg kell adni az eddigi jelszót is     
    if (isset($_SESSION['userid'])) {
        $sql = "SELECT * FROM user WHERE userid='{$_SESSION['userid']}'";
        $eredmeny = mysqli_query($dbconn, $sql);
        $sor = mysqli_fetch_assoc($eredmeny);
        if($sor['jelszo'] != sha1($_POST['jelszo_regi']))
            $errors[] = "A jelenlegi jelszavad nem egyezik!";
    }

    /*Kép feltöltése. Vizsgálni kell azt is hogy új képet töltött-e fel, mert hiba esetén az űrlap
        újraküldésekor az is előfordulhat hogy új képet tölt fel. Ilyenkor a korábbit le kell törölni
        A rejtett mezővel hasonlítja össze a feltöltött fájl nevét. */ 
    if ($_FILES['foto']['name'] != "" && $_FILES['foto']['name'] != $_POST['pkep_file']) {
        //ha másik képet választott, az előzőt letörli
        if ($_POST['pkep_file'] != "") {
            unlink("../users/tmp/" . $_POST['pkep_file']);
            $_POST['pkep_file'] = "";
        }
        //kép feltöltése előbb egy tmp könyvtárba
        $result = upload("../users/tmp/", strtok($_FILES['foto']['name'], "."));
        if (!$result['error']) {
            $pkep = $result['file'];
        } else {
            $errors[] = $result['hiba'];
        }
    }
    /* Ha (űrlap újraküldésnél) a rejtett input mező nem üres, akkor a már feltöltött fájl nevét 
       vissza kell írni a $pkep változóba. */ 
    if ($_POST['pkep_file'] != "") {
        $pkep = $_POST['pkep_file'];
    }
    //Hibalista előkészítése
    if (isset($errors)) {
        $hibalista = "<ul>\n";
        foreach ($errors as $hiba) {
            $hibalista .= "<li>{$hiba}</li>\n";
        }
        $hibalista .= "</ul>\n";
    } else {
    //ha nincs hiba....
        //A profilkép méretezése, átnevezése és áthelyezése a végleges helyére
        if (isset($pkep)) {
            $new_pkep = substr_replace($pkep, ekezettelen($userid), 0, -4);
            rename("../users/tmp/" . $pkep, "../users/" . $new_pkep);
            $pkep = $new_pkep;
            crop_img("../users/" . $pkep, "../users/" . $pkep, 130, $_POST['prew_height'], $_POST['top_pos'], $_POST['left_pos']);
        } else {
            if(!isset($_SESSION['userid']))
                $pkep = "avatar.png";
            else
                $pkep = $_SESSION['pkep'];
        }
        //jelszó titkosítása
        if (!isset($_SESSION['userid']) || !empty($jelszo)) { // Új regisztráció vagy jelszó módosítás esetén
            $jelszo = sha1($jelszo);
        } else {
            $jelszo =  $sor['jelszo']; //Adatmódosítás, de nem jelszó módosítás (marad a régi)
        }

        // Adatmódosítás esetén:
        if(isset($_SESSION['userid'])){
            //Ha profilképet változtatott, a régi képet törölni kell    
            if ($pkep != $_SESSION['pkep']) {
                if($_SESSION['pkep'] != "avatar.png"){
                    unlink("../users/" . $_SESSION['pkep']);
                }
            }
            // Ha változott a username, a fotót is át kell nevezni
            if ($userid != $_SESSION['userid']) {
                $new_pkep = substr_replace($pkep, ekezettelen($userid), 0, -4);
                rename("../users/" . $pkep, "../users/" . $new_pkep);
                $pkep = $new_pkep;
            }
            //adatmódosításnál
            $sql = "UPDATE user SET
                    userid='{$userid}', jelszo='{$jelszo}', email='{$email}', nev='{$nev}',
                    pkep='{$pkep}', rolam='{$rolam}', cam='{$cam}', lens='{$lens}'
                    WHERE userid='{$_SESSION['userid']}'";
        }else{
            //regisztrációnál
            $sql = "INSERT INTO user
                    (userid, jelszo, email, nev, pkep, rolam, cam, lens)
                    VALUES
                    ('$userid', '$jelszo', '$email', '$nev', '$pkep', '$rolam', '$cam', '$lens')";
        }
        // Adatok felvitele az adatbázisba
        if (mysqli_query($dbconn, $sql)) {
            //beléptetés
            $_SESSION['userid'] = $userid;
            $_SESSION['nev'] = $nev;
            $_SESSION['keresztnev'] = mb_substr($nev, mb_strpos($nev, " "));
            $_SESSION['pkep'] = $pkep;
            $_SESSION['email'] = $email;
        } else {
            $hiba = "MySqli hiba (" . mysqli_errno($dbconn) . "): " . mysqli_error($dbconn) . "\n";
            $hibalista = "<ul>\n<li>{$hiba}</li>\n</ul>\n";
        }
    }
} 

if (isset($_POST['reset'])) {
    // Reset gomb megnyomása esetén törli a profilképet a tmp könyvtárból
    if ($_POST['pkep_file'] != "") {
        @unlink("../users/tmp/" . $_POST['pkep_file']);
    }
}

//Regisztráció törlése
if (isset($_POST['delete']) && !isset($_POST['reset'])) {
    $sql = "SELECT jelszo FROM user WHERE userid='{$_SESSION['userid']}'";
    $eredmeny = mysqli_query($dbconn, $sql);
    $adatok = mysqli_fetch_assoc($eredmeny);
    if($adatok['jelszo'] != sha1($_POST['jelszo_regi'])){
        $hibalista = "A jelszavad nem egyezik!";
    }else{
        // A hibákhoz írja ki a figyelmeztetést, és a megerősítést
        $hibalista = "<p>Minden adatod és fotóid törlődnek!<br>
                      Biztos ezt akarod?
                      <label id=\"del\"><input type=\"checkbox\" name=\"confirm\" id=\"confirm\"> Igen!</label></p>\n";
    }
    if (isset($_POST['confirm'])) {
        if($_SESSION['pkep'] != "avatar.png"){
            unlink("../users/" . $_SESSION['pkep']);
        }
        // A userhez tartozó fotók lekérdezése, mert azokat is törölni kell    
        $sql = "SELECT file FROM foto WHERE artist='{$_SESSION['userid']}'";
        $eredmeny = mysqli_query($dbconn, $sql);    
        while($files = mysqli_fetch_row($eredmeny)){
            unlink("../photos/thumbs/" . $files[0]);
            unlink("../photos/" . $files[0]);
        }
        $sql = "DELETE FROM user WHERE userid='{$_SESSION['userid']}'";
        if (mysqli_query($dbconn, $sql)) {
            $_SESSION = array();
            session_destroy();
            $confirm = "conf";
        } else {
            $hiba = "MySqli hiba (" . mysqli_errno($dbconn) . "): " . mysqli_error($dbconn) . "\n";
            $hibalista = "<ul>\n<li>{$hiba}</li>\n</ul>\n";
        }
    }
}
mysqli_close($dbconn);

// Eredmények küldése az AJAX-nak
echo json_encode(
    array(
        "status" => isset($hibalista) ? "ERR" : "OK",
        "error" => isset($hibalista) ? $hibalista : "",
        "pkep_file" => isset($pkep) ? $pkep : "",
        "delete" => isset($confirm) ? $confirm : "",
    )
);
?>