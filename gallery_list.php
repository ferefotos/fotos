<?php
require("config.php");

if(isset($_POST['limit'], $_POST['start'])){
    /*A bejelentkezett felhasználónak a nem publikus saját képeit is meg kell jeleníteni a galériában*/
    if (isset($_SESSION['userid'])) {
        $term = "OR artist='{$_SESSION['userid']}' AND public=0";
    } else {$term = "";}
//Az sql lekérdezések elkészítése a szűrési feltételek szerint
    // Kategória szerinti szűrés
    $katid="";
    if ($_POST['katid'] != null) {
        $list="katid";
        $katid=$_POST['katid'];
        $sql = "SELECT file, class, nev, pkep, userid FROM foto
            JOIN user ON userid=artist 
            WHERE katid= {$_POST['katid']} AND (public=1 $term)
            ORDER BY file DESC LIMIT ".$_POST['start'] . ", " . $_POST['limit']."";
    }
    // Keresés 
    $search="";
    if ($_POST['search'] != null){
        $list="src"; $search=$_POST['search'];
        $sql = "SELECT file, class, nev, pkep,userid FROM foto
                JOIN user ON userid=artist
                JOIN kategoria ON kategoria.id=foto.katid 
                WHERE (nev LIKE '%$search%'
                OR cim LIKE '%$search%'
                OR kamera LIKE '%$search%'
                OR obi LIKE '%$search%'
                OR story LIKE '%$search%'
                OR kategoria LIKE '%$search%')
                AND (public=1 $term)
                ORDER BY file DESC LIMIT ".$_POST['start'] . ", " . $_POST['limit']."";
    }
    // Felhasználó szerinti szűrés
    $userid="";
    if ($_POST['userid'] != null){
        $list="userid";
        $userid=$_POST['userid'];
        $sql = "SELECT file, class, nev, pkep, userid FROM foto
                JOIN user ON userid=artist 
                WHERE artist= '{$_POST['userid']}' AND (public=1 $term)
                ORDER BY file DESC LIMIT ".$_POST['start'] . ", " . $_POST['limit']."";
    }

    // Kedvenc képek listázása
    if($_POST['kedvenc'] != null){
        $list="kedvenc";
        $sql="SELECT file, class, nev, pkep, userid FROM foto
            JOIN kedvencek ON filename=file
            JOIN user ON userid=artist
            WHERE jelolo='{$_SESSION['userid']}'
            ORDER BY file DESC LIMIT ".$_POST['start'] . ", " . $_POST['limit']."";
    }
        
    // TOP30 képek listázása (lájkolás szerint a legnépszerűbbek)
    if($_POST['toplist'] != null){
        $list="toplist";
        $sql = "SELECT file, class, nev, pkep, userid FROM foto
        JOIN user ON userid=artist 
        JOIN kedvelesek ON kedvelesek.foto=foto.file
        WHERE public=1
        GROUP BY file
        ORDER BY count(foto) DESC, file DESC
        LIMIT ".$_POST['start'] . ", " . $_POST['limit']."";
    }
        
    //A like és kedvenc gombok képei változókban
    $like_img_on="heart24cb.png";
    $like_img_off="heart24c.png";
    $kedvenc_img_on="star24cy.png";
    $kedvenc_img_off="star24c.png";
    
    //Galéria elkészítése
    /*A bélyegképekről link a foto.php-ra, ahol a képet nagyobb méretben az adatlapjával láthatjuk.
    * A linken a $_GET szuperglobális változóban átadjuk a fájl nevét, a kép kategória azonosítóját,
    *  a képhez tartozó userid-t és azt, hogy milyen szűrő volt alkalmazva. 
    * (pl. kategóriára szűrtünk, vagy felhasználóra, vagy a keresővel szűrtünk.)
    */
    if ($eredmeny = mysqli_query($dbconn, $sql)) {
        // $db=mysqli_num_rows($eredmeny);
        while ($sor = mysqli_fetch_assoc($eredmeny)) {
            $nev = mb_substr($sor['nev'], mb_strpos($sor['nev'], " "));
            $file= $sor['file'];
            $img_id = strtok($file, "."); 
            //Like és kedvenc lekérdezések
            include('like_count.php');

            echo "<div class=\"image {$sor['class']}\" id=\"{$img_id}\" onmousemove=\"showinfo(this)\">\n
                    <a href=\"foto.php?file=$file&katid=$katid&userid=$userid&search=$search&list=$list\">\n
                        <img src=\"kepek/$file\" class=\"gallery_foto\" alt=\"foto\"></a>\n
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
    } else {
        echo "MySqli hiba (" . mysqli_errno($dbconn) . "): " . mysqli_error($dbconn) . "\n";
    }
    mysqli_close($dbconn);
}
?>