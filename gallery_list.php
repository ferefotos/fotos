<?php
require("config.php");
require(ROOT_PATH. "/form/common.php");
/***********************************
 *     Galéria összeállítása       *
 ***********************************/

/* Végtelen lapozású galéria, amit a lapozo.js működtet AJAX technológiával
   $_POST['limit] - a lekérdezendő képek száma. 
   $_POST['start'] - a lekérdezés kezdő pozíciója 
   Minden szükséges változót post-tal küld az AJAX. */ 

if(isset($_POST['limit'], $_POST['start'])){
// Az sql lekérdezések elkészítése a szűrési feltételek szerint
    // Kategória szerinti szűrés
    $url_param="";
    if ($_POST['katid'] != null) {
        $url_param="katid={$_POST['katid']}&list=katid";
        $sql = "SELECT file, class, nev, pkep, userid FROM foto
                JOIN user ON userid=artist 
                WHERE katid= {$_POST['katid']} AND (public=1 $term)
                ORDER BY file DESC LIMIT ".$_POST['start'] . ", " . $_POST['limit']."";
    }
    // Keresés 
    if ($_POST['search'] != null){
        $search=tisztit($_POST['search']);
        $url_param="search=$search&list=src";
        $sql = "SELECT DISTINCT file, class, nev, pkep, userid FROM foto
                JOIN user ON userid=artist
                JOIN kategoria ON kategoria.id=foto.katid 
                LEFT JOIN kommentek ON foto.file=kommentek.kep
                WHERE (nev LIKE '%$search%'
                OR cim LIKE '%$search%'
                OR kamera LIKE '%$search%'
                OR obi LIKE '%$search%'
                OR story LIKE '%$search%'
                OR kategoria LIKE '%$search%'
                OR komment LIKE '%$search%')
                AND (public=1 $term)
                ORDER BY file DESC LIMIT ".$_POST['start'] . ", " . $_POST['limit']."";
    }
    // Felhasználó szerinti szűrés
   // $userid="";
    if ($_POST['userid'] != null){
        $url_param="userid={$_POST['userid']}&list=userid";
        $sql = "SELECT file, class, nev, pkep, userid FROM foto
                JOIN user ON userid=artist 
                WHERE artist= '{$_POST['userid']}' AND (public=1 $term)
                ORDER BY file DESC LIMIT ".$_POST['start'] . ", " . $_POST['limit']."";
    }

    // Kedvenc képek listázása
    if($_POST['kedvenc'] != null){
        $url_param="list=kedvenc";
        $sql="SELECT file, class, nev, pkep, userid FROM foto
              JOIN kedvencek ON filename=file
              JOIN user ON userid=artist
              WHERE jelolo='{$_SESSION['userid']}'
              ORDER BY file DESC LIMIT ".$_POST['start'] . ", " . $_POST['limit']."";
    }
        
    // TOP60 képek listázása (lájkolás szerint a legnépszerűbbek)
    if($_POST['toplist'] != null){
        $url_param="list=toplist";
        $sql = "SELECT file, class, nev, pkep, userid FROM foto
        JOIN user ON userid=artist 
        JOIN kedvelesek ON kedvelesek.foto=foto.file
        WHERE public=1
        GROUP BY file
        ORDER BY count(foto) DESC, file DESC
        LIMIT ".$_POST['start'] . ", " . $_POST['limit']."";
    }
    
    /* Galéria összeállítása **********************************************************************/

    /*A bélyegképekről link a foto.php-ra, ahol a képet nagyobb méretben az adatlapjával láthatjuk.
    * A címsorban paraméterként átadjuk a fájl nevét, a kép kategória azonosítóját,
    *  a képhez tartozó userid-t és azt, hogy milyen szűrő volt alkalmazva. 
    * (pl. kategóriára szűrtünk, vagy felhasználóra, vagy a keresővel szűrtünk.)*/
    if ($eredmeny = mysqli_query($dbconn, $sql)) {
        if(mysqli_num_rows($eredmeny)>0){
            while ($sor = mysqli_fetch_assoc($eredmeny)) {
                $nev = mb_substr($sor['nev'], mb_strpos($sor['nev'], " "));
                $file = $sor['file'];
                $img_id = strtok($file, "."); 
                //Like és kedvenc lekérdezések
                $db_like=db_like($file);
                $db_kedvenc=db_kedvenc($file);
                $like_img ="heart24c.png";
                $kedvenc_img ="star24c.png";
                // változik az gomb ikon színe ha a felhasználó jelölte a képet 
                if(isset($_SESSION['userid'])){
                    if(kedvelt($file)) $like_img = "heart24cb.png";
                    if(kedvenc($file)) $kedvenc_img="star24cy.png"; 
                }
                echo "<div class=\"image {$sor['class']}\" id=\"{$img_id}\" onmousemove=\"showinfo(this)\">\n
                        <a href=\"foto.php?file=$file&$url_param\">\n
                            <img src=\"photos/thumbs/$file\" class=\"gallery_foto\" alt=\"foto\"></a>\n
                        <div class=\"like_stripe\">\n
                            <div class=\"artist\">\n
                            <a href=\"gallery.php?userid={$sor['userid']}\" id=\"artist_link\">\n
                                <img src=\"users/{$sor['pkep']}\" alt=\"profilkep\"><span>{$nev}</span></a>\n
                            </div>\n
                            <div class=like_box>\n
                                <button type=\"submit\" name=\"kedvenc\" form=\"foto_like\" class=\"like_button kedvenc\" id=\"{$file}K\" onclick=\"liked(this)\">\n
                                    <img src=\"items/$kedvenc_img\" id=\"{$file}Ki\" class=like_img alt=\"kedvenc\" onmouseover=\"like_hover(this)\" onmouseout=\"like_out(this)\">\n
                                    <span class=\"like_count\" id=\"{$file}Kc\">$db_kedvenc</span>\n
                                </button>\n
                                <button type=\"submit\" name=\"like\" form=\"foto_like\" class=\"like_button like\" id=\"{$file}L\" onclick=\"liked(this)\">\n
                                    <img src=\"items/$like_img\" id=\"{$file}Li\" class=like_img alt=\"like\" onmouseover=\"like_hover(this)\" onmouseout=\"like_out(this)\">\n
                                    <span class=\"like_count\" id=\"{$file}Lc\">$db_like</span>\n
                                </button>\n
                                <span class=like_alert>Jelentkezz be!</span>\n
                            </div>\n
                        </div>\n
                    </div>\n";  
            }
        }else{echo 0;} //Ha nincs eredmény
    } else {
        echo "MySqli hiba (" . mysqli_errno($dbconn) . "): " . mysqli_error($dbconn) . "\n";
    }
    mysqli_close($dbconn);
}
?>