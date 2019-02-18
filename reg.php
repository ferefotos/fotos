<?php
require("config.php");
require('file.php');
/**************************************************************
 * Regisztrációs űrlap feldolgozása                           *
 **************************************************************/
function tisztit($dbconn, $text){
    return mysqli_real_escape_string($dbconn, stripslashes(strip_tags(trim($text))));
}
if (!isset($_SESSION['userid'])) {
    //Regisztráció
    if (isset($_POST['elkuld'])) {    
        //változók tisztítása
        $userid = tisztit($dbconn, $_POST['userid']);
        $jelszo = $_POST['jelszo'];
        $jelszo2 = $_POST['jelszo2'];
        $email = strtolower(tisztit($dbconn, $_POST['email']));
        $nev = ucwords(mb_strtolower(tisztit($dbconn, $_POST['nev'])));
        $cam = tisztit($dbconn, $_POST['cam']);
        $lens = tisztit($dbconn, $_POST['lens']);
        $rolam = tisztit($dbconn, $_POST['rolam']);

        //változók vizsgálata
        if (empty($userid))
            $errors[] = "Nem adtál meg felhasználónevet!";
        elseif (strlen($userid) < 6)
            $errors[] = "A felhasználónév minimum 6 karakter legyen!";
        $sql = "SELECT COUNT(*) AS db FROM user WHERE userid='{$userid}'";
        $eredmeny = mysqli_query($dbconn, $sql);
        $sor = mysqli_fetch_assoc($eredmeny);
        if ($sor['db'] != 0)
            $errors[] = "Foglalt felhasználónév!";
        if (empty($jelszo))
            $errors[] = "Nem adtál meg jelszót!";
        elseif (strlen($jelszo) < 8)
            $errors[] = "A jelszó minimum 8 karakter legyen!";
        if ($jelszo != $jelszo2)
            $errors[] = "A megismételt jelszó nem egyezik!";
        if (empty($email))
            $errors[] = "Nem adtál meg e-mail címet!";
        if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL))
            $errors[] = "Rossz e-mail címet adtál meg!";
        $sql = "SELECT COUNT(*) AS db FROM user WHERE email='{$email}'";
        $eredmeny = mysqli_query($dbconn, $sql);
        $sor = mysqli_fetch_assoc($eredmeny);
        if ($sor['db'] != 0)
            $errors[] = "Foglalt e-mail cím!";
        if (empty($nev))
            $errors[] = "Nem adtad meg a neved!";
        if (strlen($rolam) > 500)
            $errors[] = "A bemutatkozás max. 500 karakter lehet!"; 
        
        /*Kép feltöltése. Vizsgálni kell azt is hogy új képet töltött -e fel, mert hiba esetén az űrlap
         *újraküldésekor az is előfordulhat hogy új képet tölt fel. Ilyenkor a korábbit le is kell törölni
         * A rejtett mezővel hasonlítja össze a feltöltött fájl nevét. */ 
        if ($_FILES['foto']['name'] != "" && $_FILES['foto']['name'] != $_POST['filename']) {
            //ha másik képet választott, az előzőt letörli
            if ($_POST['filename'] != "") {
                unlink("users/tmp/" . $_POST['filename']);
                $_POST['filename'] = "";
            }
            //kép feltöltése előbb egy tmp könyvtárba
            $result = upload("users/tmp/", strtok($_FILES['foto']['name'], "."));
            if (!$result['error']) {
                $foto = $result['file'];
            } else {
                $errors[] = $result['hiba'];
            }
        }
        /*Rejtett input mezőbe írja majd a fájl nevét, hogy hiba esetén űrlap újraküldésnél
          megmaradjon a már kiválasztott profilkép, így nem kell újra feltölteni.*/ 
        if ($_POST['filename'] != "") {
            $foto = $_POST['filename'];
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
            if (isset($foto)) {
                $newfilename = substr_replace($foto, ekezettelen($userid), 0, -4);
                rename("users/tmp/" . $foto, "users/" . $newfilename);
                $foto = $newfilename;
                crop_img("users/" . $foto, "users/" . $foto, 130, $_POST['prew_height'], $_POST['top_pos'], $_POST['left_pos']);
            } else {
                $foto = "avatar.png";
            }
            //jelszó titkosítása
            $jelszo = sha1($jelszo);
            //Adatok mentése az adatbázisba
            $sql = "INSERT INTO user
              (userid, jelszo, email, nev, pkep, rolam, cam, lens)
              VALUES
              ('$userid', '$jelszo', '$email', '$nev', '$foto', '$rolam', '$cam', '$lens')";

            if (mysqli_query($dbconn, $sql)) {
                //beléptetés
                $_SESSION['userid'] = $userid;
                $_SESSION['nev'] = $nev;
                $_SESSION['keresztnev'] = mb_substr($nev, mb_strpos($nev, " "));
                $_SESSION['pkep'] = $foto;
            } else {
                $hiba = "MySqli hiba (" . mysqli_errno($dbconn) . "): " . mysqli_error($dbconn) . "\n";
                $hibalista = "<ul>\n<li>{$hiba}</li>\n</ul>\n";
            }
        }
      // Reset gomb megnyomása esetén letörli a feltöltött profilképet  
    } elseif (isset($_POST['reset'])) {
        if ($_POST['filename'] != "") {
            @unlink("users/tmp/" . $_POST['filename']);
        }
    }
  /* Adatmódosítás ***************************************************************************/  
} else {
    if (isset($_POST['elkuld']) && !isset($_POST['delete'])) {    
        // változók tisztítása
        $userid = tisztit($dbconn, $_POST['userid']);
        $jelszo = $_POST['jelszo'];
        $jelszo2 = $_POST['jelszo2'];
        $jelszo_regi = $_POST['jelszo_regi'];
        $email = strtolower(tisztit($dbconn, $_POST['email']));
        $nev = ucwords(mb_strtolower(tisztit($dbconn, $_POST['nev'])));
        $cam = tisztit($dbconn, $_POST['cam']);
        $lens = tisztit($dbconn, $_POST['lens']);
        $rolam = tisztit($dbconn, $_POST['rolam']);
    
        // Adatok lekérdezése
        $sql = "SELECT * FROM user WHERE userid='{$_SESSION['userid']}'";
        $eredmeny = mysqli_query($dbconn, $sql);
        $adatok = mysqli_fetch_assoc($eredmeny);

        // változók vizsgálata
        if (empty($userid))
            $errors[] = "Nem adtál meg felhasználónevet!";
        elseif (strlen($userid) < 6)
            $errors[] = "A felhasználónév minimum 6 karakter legyen!";

        if ($userid != $adatok['userid']) {
            $sql = "SELECT COUNT(*) AS db FROM user WHERE userid='{$userid}'";
            $eredmeny = mysqli_query($dbconn, $sql);
            $sor = mysqli_fetch_assoc($eredmeny);
            if ($sor['db'] != 0)
                $errors[] = "Foglalt felhasználónév!";
        }
        // Csak akkor foglalkozik a jelszó mezőkkel ha írtak bele, ha más adatot módosít, akkor nem kell jelszót megadni
        if (!empty($jelszo) || !empty($jelszo2)) {
            if (empty($jelszo))
                $errors[] = "Nem adtál meg jelszót!";
            elseif (strlen($jelszo) < 8)
                $errors[] = "A jelszó minimum 8 karakter legyen!";
            if ($jelszo != $jelszo2)
                $errors[] = "A megismételt jelszó nem egyezik!";
        }
        if($adatok['jelszo'] != sha1($_POST['jelszo_regi']))
            $errors[] = "A jelenlegi jelszavad nem egyezik!";

        if (empty($email))
            $errors[] = "Nem adtál meg e-mail címet!";
        if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL))
            $errors[] = "Rossz e-mail címet adtál meg!";

        if ($email != $adatok['email']) {
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

       //kép feltöltése 
        if ($_FILES['foto']['name'] != "" && $_FILES['foto']['name'] != $_POST['filename']) {
        //ha másik képet választ, az előzőt letörli
            if ($_POST['filename'] != "") {
                unlink("users/tmp/" . $_POST['filename']);
                $_POST['filename'] = "";
            }
            $result = upload("users/tmp/", strtok($_FILES['foto']['name'], "."));
            if (!$result['error']) {
                $foto = $result['file'];
            } else {
                $errors[] = $result['hiba'];
            }
        }

        if ($_POST['filename'] != "") {
            $foto = $_POST['filename'];
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
            if (isset($foto)) {
                $newfilename = substr_replace($foto, ekezettelen($userid), 0, -4);
                rename("users/tmp/" . $foto, "users/" . $newfilename);
                $foto = $newfilename;
                crop_img("users/" . $foto, "users/" . $foto, 130, $_POST['prew_height'], $_POST['top_pos'], $_POST['left_pos']);
            } else {
                //Ha nem történt képcsere
                $foto = $adatok['pkep'];
            }

            if (!empty($jelszo)) {
                $jelszo = sha1($jelszo);
            } else {
                $jelszo = $adatok['jelszo'];
            }

            //Ha nevet és képet is változtatott, a régi képet törölni kell    
            if ($foto != $adatok['pkep']) {
                unlink("users/" . $adatok['pkep']);
            }
            // Ha változott a username, a fotót is át kell nevezni
            if ($userid != $adatok['userid']) {
                $newfilename = substr_replace($foto, ekezettelen($userid), 0, -4);
                rename("users/" . $foto, "users/" . $newfilename);
                $foto = $newfilename;
            }

            //Adatok módosítása az adatbázisban
            $sql = "UPDATE user SET
                    userid='{$userid}', jelszo='{$jelszo}', email='{$email}', nev='{$nev}',
                    pkep='{$foto}', rolam='{$rolam}', cam='{$cam}', lens='{$lens}'
                    WHERE userid='{$adatok['userid']}'";

            if (mysqli_query($dbconn, $sql)) {
                //beléptetés
                $_SESSION['userid'] = $userid;
                $_SESSION['nev'] = $nev;
                $_SESSION['keresztnev'] = mb_substr($nev, mb_strpos($nev, " "));
                $_SESSION['pkep'] = $foto;
            } else {
                $hiba = "MySqli hiba (" . mysqli_errno($dbconn) . "): " . mysqli_error($dbconn) . "\n";
                $hibalista = "<ul>\n<li>{$hiba}</li>\n</ul>\n";
            }
        }

    } elseif (isset($_POST['reset'])) {
        if ($_POST['filename'] != "") {
            @unlink("users/tmp/" . $_POST['filename']);
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
                unlink("users/" . $_SESSION['pkep']);
            }
            // A userhez tartozó fotók lekérdezése, mert azokat is törölni kell    
            $sql = "SELECT file FROM foto WHERE artist='{$_SESSION['userid']}'";
            $eredmeny = mysqli_query($dbconn, $sql);    
            while($files = mysqli_fetch_row($eredmeny)){
                unlink("kepek/" . $files[0]);
                unlink("kepek/L/" . $files[0]);
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
}
//Válasz az ajax felé
echo json_encode(
    array(
        "status" => isset($hibalista) ? "ERR" : "OK",
        "error" => isset($hibalista) ? $hibalista : "",
        "foto" => isset($foto) ? $foto : "",
        "delete" => isset($confirm) ? $confirm : "",
    )
);
mysqli_close($dbconn);
?>