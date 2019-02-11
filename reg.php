<?php
session_start();
require('file.php');
require("connect.php");

if (!isset($_SESSION['userid'])) {
    //Regisztráció
    if (isset($_POST['elkuld'])) {    
        //változók tisztítása
        $userid = strip_tags(trim($_POST['userid']));
        $jelszo = strip_tags($_POST['jelszo']);
        $jelszo2 = strip_tags($_POST['jelszo2']);
        $email = strip_tags(strtolower(trim($_POST['email'])));
        $nev = strip_tags(ucwords(mb_strtolower(trim($_POST['nev']))));
        $cam = strip_tags(trim($_POST['cam']));
        $lens = strip_tags(trim($_POST['lens']));
        $rolam = strip_tags(trim($_POST['rolam']));

        //változók vizsgálata
        if (empty($userid))
            $errors[] = "Nem adtál meg felhasználónevet!";
        elseif (strlen($userid) < 6)
            $errors[] = "A felhasználónév minimum 6 karakter legyen!";

        $sql = "SELECT COUNT(*) AS db FROM user WHERE userid='{$userid}'";
        if ($eredmeny = mysqli_query($dbconn, $sql)) {
            $sor = mysqli_fetch_assoc($eredmeny);
            if ($sor['db'] != 0)
                $errors[] = "Foglalt felhasználónév!";
        } else {
            $errors[] = "MySqli hiba (" . mysqli_errno($dbconn) . "): " . mysqli_error($dbconn) . "\n";
        }
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
        if ($eredmeny = mysqli_query($dbconn, $sql)) {
            $sor = mysqli_fetch_assoc($eredmeny);
            if ($sor['db'] != 0)
                $errors[] = "Foglalt e-mail cím!";
        } else {
            $errors[] = "MySqli hiba (" . mysqli_errno($dbconn) . "): " . mysqli_error($dbconn) . "\n";
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
                resize("users/" . $foto, "users/" . $foto, 130);
            } else {
                $foto = "avatar.png";
            }

            $jelszo = sha1($jelszo);
            //Adatok mentése az adatbázisba
            $sql = "INSERT INTO user
              (userid, jelszo, email, nev, pkep, rolam, cam, lens)
              VALUES
              ('{$userid}', '{$jelszo}', '{$email}', '{$nev}', '{$foto}', '{$rolam}', '{$cam}', '{$lens}' )";

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
} else {
    // Adatmódosítás
    if (isset($_POST['elkuld']) && !isset($_POST['delete'])) {    
        //változók tisztítása
        $userid = strip_tags(trim($_POST['userid']));
        $jelszo = strip_tags($_POST['jelszo']);
        $jelszo2 = strip_tags($_POST['jelszo2']);
        $email = strip_tags(strtolower(trim($_POST['email'])));
        $nev = strip_tags(ucwords(mb_strtolower(trim($_POST['nev']))));
        $cam = strip_tags(trim($_POST['cam']));
        $lens = strip_tags(trim($_POST['lens']));
        $rolam = strip_tags(trim($_POST['rolam']));
    
        
         /*Korábbi adatok lekérdezése */
        $sql = "SELECT * FROM user WHERE userid='{$_SESSION['userid']}'";
        if ($eredmeny = mysqli_query($dbconn, $sql)) {
            $old = mysqli_fetch_assoc($eredmeny);
        } else {
            $errors[] = "MySqli hiba (" . mysqli_errno($dbconn) . "): " . mysqli_error($dbconn) . "\n";
        }
        //változók vizsgálata
        if (empty($userid))
            $errors[] = "Nem adtál meg felhasználónevet!";
        elseif (strlen($userid) < 6)
            $errors[] = "A felhasználónév minimum 6 karakter legyen!";

        if ($userid != $old['userid']) {
            $sql = "SELECT COUNT(*) AS db FROM user WHERE userid='{$userid}'";
            if ($eredmeny = mysqli_query($dbconn, $sql)) {
                $sor = mysqli_fetch_assoc($eredmeny);
                if ($sor['db'] != 0)
                    $errors[] = "Foglalt felhasználónév!";
            } else {
                $errors[] = "MySqli hiba (" . mysqli_errno($dbconn) . "): " . mysqli_error($dbconn) . "\n";
            }
        }

        if (!empty($jelszo) || !empty($jelszo2)) {
            if (empty($jelszo))
                $errors[] = "Nem adtál meg jelszót!";
            elseif (strlen($jelszo) < 8)
                $errors[] = "A jelszó minimum 8 karakter legyen!";
            if ($jelszo != $jelszo2)
                $errors[] = "A megismételt jelszó nem egyezik!";
        }

        if (empty($email))
            $errors[] = "Nem adtál meg e-mail címet!";
        if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL))
            $errors[] = "Rossz e-mail címet adtál meg!";

        if ($email != $old['email']) {
            $sql = "SELECT COUNT(*) AS db FROM user WHERE email='{$email}'";
            if ($eredmeny = mysqli_query($dbconn, $sql)) {
                $sor = mysqli_fetch_assoc($eredmeny);
                if ($sor['db'] != 0)
                    $errors[] = "Foglalt e-mail cím!";
            } else {
                $errors[] = "MySqli hiba (" . mysqli_errno($dbconn) . "): " . mysqli_error($dbconn) . "\n";
            }
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
                resize("users/" . $foto, "users/" . $foto, 130);
            } else {
                //Ha nem történt képcsere
                $foto = $old['pkep'];
            }

            if (!empty($jelszo)) {
                $jelszo = sha1($jelszo);
            } else {
                $jelszo = $old['jelszo'];
            }

            //Ha nevet és képet is változtatott, a régi képet törölni kell    
            if ($foto != $old['pkep']) {
                unlink("users/" . $old['pkep']);
            }
            // Ha változott a username, a fotót is át kell nevezni
            if ($userid != $old['userid']) {
                $newfilename = substr_replace($foto, ekezettelen($userid), 0, -4);
                rename("users/" . $foto, "users/" . $newfilename);
                $foto = $newfilename;
            }

            //Adatok mentése az adatbázisba
            $sql = "UPDATE user SET
                    userid='{$userid}', jelszo='{$jelszo}', email='{$email}', nev='{$nev}',
                    pkep='{$foto}', rolam='{$rolam}', cam='{$cam}', lens='{$lens}'
                    WHERE userid='{$old['userid']}'";

            if (mysqli_query($dbconn, $sql)) {
                //beléptetés
                $_SESSION['userid'] = $userid;
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
        $hibalista = "<p>Minden adatod és fotóid törlődnek!<br>
                          Biztos ezt akarod?
                          <label id=\"del\"><input type=\"checkbox\" name=\"confirm\" id=\"confirm\"> Igen!</label></p>\n";
        if (isset($_POST['confirm'])) {
            if($_SESSION['pkep'] != "avatar.png"){
                unlink("users/" . $_SESSION['pkep']);
            }
            // A userhez tartozó fotók lekérdezése, mert azokat is törölni kell    
            $sql = "SELECT file FROM foto WHERE artist='{$_SESSION['userid']}'";
            if ($eredmeny = mysqli_query($dbconn, $sql)) {      
                while($files = mysqli_fetch_row($eredmeny)){
                    unlink("kepek/" . $files[0]);
                    unlink("kepek/L/" . $files[0]);
                }
                
            } else {
                $hiba = "MySqli hiba (" . mysqli_errno($dbconn) . "): " . mysqli_error($dbconn) . "\n";
                $hibalista = "<ul>\n<li>{$hiba}</li>\n</ul>\n";
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


?>