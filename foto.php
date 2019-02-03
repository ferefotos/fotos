<?php
session_start();
require("connect.php");
require("upload.php");
require_once("fejlec.php");
?>
<body>
    <div class="container">
        <aside>
            <?php require("aside.php"); ?>
        </aside>

        <main>
            <header>
                <?php require("header.php"); ?>
            </header>
<?php
/*A bejelentkezett felhasználónak a nem publikus saját képeit is meg kell jeleníteni a galériában
  * Itt a lapozás miatt szükséges ez a feltétel*/
if (isset($_SESSION['userid'])) {
    $term = "OR artist='{$_SESSION['userid']}' AND public=0";
} else {$term = "";}
/*A lapozáshoz meg kell keresni az első és az utolsó fájlt Mindezt attól függően
 * hogy mi szerint volt szűrve. Ezt úgy tudom, hogy a GET-nek adok egy list paramétert,
 * amiben az van hogy milyen szűrő volt alkalmazva a galérián. 
*/
if ($_GET['list'] == "katid") {
    $sql = "SELECT max(file) AS max, min(file) AS min FROM foto 
    WHERE katid={$_GET['katid']} AND (public=1 $term)";
} elseif (($_GET['list'] == "userid")) {
    $sql = "SELECT max(file) AS max, min(file) AS min FROM foto 
    WHERE artist='{$_GET['userid']}' AND (public=1 $term)";
} elseif (($_GET['list'] == "src")){
    $sql = "SELECT max(file) AS max, min(file) AS min FROM foto
            JOIN user ON userid=artist
            JOIN kategoria ON kategoria.id=foto.katid 
            WHERE (nev LIKE '%{$_GET['search']}%'
            OR cim LIKE '%{$_GET['search']}%'
            OR kamera LIKE '%{$_GET['search']}%'
            OR obi LIKE '%{$_GET['search']}%'
            OR story LIKE '%{$_GET['search']}%'
            OR kategoria LIKE '%{$_GET['search']}%')
            AND (public=1 $term)";
} elseif (($_GET['list'] == "kedvenc")){
    $sql = "SELECT max(file) AS max, min(file) AS min FROM foto
            JOIN kedvenc ON filename=file
            WHERE jelolo='{$_SESSION['userid']}'";
} else{$sql = "SELECT max(file) AS max, min(file) AS min FROM foto";}
$eredmeny = mysqli_query($dbconn, $sql);
$sor = mysqli_fetch_assoc($eredmeny);
$max = $sor['max'];
$min = $sor['min'];
//Előre lapozáshoz 
/*A jelenlegi fájlhoz képest a következő fájl lekérdezése a szűrés szerint*/
if (isset($_GET['next'])) {
    if ($_GET['list'] == "katid") {
        $sql = "SELECT file FROM foto 
            WHERE katid={$_GET['katid']} AND (public=1 $term) 
            AND file<'{$_GET['file']}'
            ORDER BY file DESC LIMIT 1";
    } elseif (($_GET['list'] == "userid")) {
        $sql = "SELECT file FROM foto 
            WHERE artist='{$_GET['userid']}' AND (public=1 $term) 
            AND file<'{$_GET['file']}'
            ORDER BY file DESC LIMIT 1";
    } elseif (($_GET['list'] == "src")){
        $sql = "SELECT file FROM foto 
            JOIN user ON userid=artist
            JOIN kategoria ON kategoria.id=foto.katid
            WHERE (nev LIKE '%{$_GET['search']}%'
            OR cim LIKE '%{$_GET['search']}%'
            OR kamera LIKE '%{$_GET['search']}%'
            OR obi LIKE '%{$_GET['search']}%'
            OR story LIKE '%{$_GET['search']}%'
            OR kategoria LIKE '%{$_GET['search']}%')
            AND file<'{$_GET['file']}'
            AND (public=1 $term)
            ORDER BY file DESC LIMIT 1";
    } elseif (($_GET['list'] == "kedvenc")){
        $sql = "SELECT file FROM foto
                JOIN kedvenc ON filename=file
                WHERE jelolo='{$_SESSION['userid']}'
                AND file<'{$_GET['file']}'
                ORDER BY file DESC LIMIT 1";
    } else {
        $sql = "SELECT file FROM foto 
            WHERE file<'{$_GET['file']}'
            AND (public=1 $term)
            ORDER BY file DESC LIMIT 1";
    }
    $eredmeny = mysqli_query($dbconn, $sql);
    $sor = mysqli_fetch_assoc($eredmeny);
    $file = $sor['file'];
//Vissza lapozáshoz 
/*A jelenlegi fájlhoz képest az előző fájl lekérdezése a szűrés szerint*/   
} elseif(isset($_GET['prev'])){
    if ($_GET['list'] == "katid") {
        $sql = "SELECT file FROM foto 
            WHERE katid={$_GET['katid']} AND (public=1 $term) 
            AND file>'{$_GET['file']}'
            ORDER BY file LIMIT 1";
    } elseif (($_GET['list'] == "userid")) {
        $sql = "SELECT file FROM foto 
            WHERE artist='{$_GET['userid']}' AND (public=1 $term) 
            AND file>'{$_GET['file']}'
            ORDER BY file LIMIT 1";
    } elseif (($_GET['list'] == "src")){
        $sql = "SELECT file FROM foto 
            JOIN user ON userid=artist
            JOIN kategoria ON kategoria.id=foto.katid
            WHERE (nev LIKE '%{$_GET['search']}%'
            OR cim LIKE '%{$_GET['search']}%'
            OR kamera LIKE '%{$_GET['search']}%'
            OR obi LIKE '%{$_GET['search']}%'
            OR story LIKE '%{$_GET['search']}%'
            OR kategoria LIKE '%{$_GET['search']}%')
            AND file>'{$_GET['file']}' 
            AND (public=1 $term)
            ORDER BY file LIMIT 1";
    } elseif (($_GET['list'] == "kedvenc")){
        $sql = "SELECT file FROM foto
                JOIN kedvenc ON filename=file
                WHERE jelolo='{$_SESSION['userid']}'
                AND file>'{$_GET['file']}'
                ORDER BY file LIMIT 1";
    } else {
        $sql = "SELECT file FROM foto 
            WHERE file>'{$_GET['file']}' 
            AND (public=1 $term)
            ORDER BY file LIMIT 1";
    }
    $eredmeny = mysqli_query($dbconn, $sql);
    $sor = mysqli_fetch_assoc($eredmeny);
    $file = $sor['file'];
// Ha nem léptetés van, a GET paraméterben kapott fájlt kell megjeleníteni
}else {
    $file=$_GET['file'];
}
//Miután meg lett határozva melyik fájlt kell betölteni, előbb le kell kérdezni a szükséges adatait:
$sql = "SELECT file, katid, cim, story, blende, zarido, iso, focus, 
                kamera, obi, date, class, userid, nev, pkep
                FROM foto JOIN user ON userid=artist
                WHERE file='$file'";
if ($eredmeny = mysqli_query($dbconn, $sql)) {
    $sor = mysqli_fetch_assoc($eredmeny);
    //A formázáshoz az osztály meghatározása
    if ($sor['class'] == 'portrait' || $sor['class'] == 'square') {
        $cls = "p-port";
    } else {
        $cls = "p-land";
    }
    //Az adatok módosításához a menü csak azoknál a képeknél jelenik meg, amely a bejelentkezett felhasználóé
    if(isset($_SESSION['userid']) && $_SESSION['userid'] == $sor['userid']){
        $modmenu = "
    <div id=\"fotomod\">\n
        <img src=\"items/menu-dot.png\" id=\"dotmenu\" onclick=\"show_modmenu()\" alt=\"modosit\">\n
        <div id=\"dotmenu-sub\" onmouseleave=\"hide_modmenu()\">\n
            <a href=fotomod.php?file={$sor['file']}&katid={$sor['katid']}&userid={$_GET['userid']}&search={$_GET['search']}&list={$_GET['list']}>Módosítás</a>\n
            <p onclick=\"show_delmenu()\" >Törlés</p>\n
        </div>\n
        <form id=\"keptorles\" method=\"post\">
                <input class=\"gomb\" type=\"submit\" name=\"delete\" id=\"torol\" value=\"Töröl\">\n
                <input class=\"gomb\" type=\"submit\" name=\"cancdel\" value=\"Mégsem\">\n
        </form>
    </div>\n";
    }else{$modmenu = "";}
    
//Az előre és a hátra léptető nyilak meghatározása. A kódba lesz illesztve.
/*Ha a végére ér, akkor nincs nyíl, és nem lehet túl léptetni*/
    if ($sor['file'] == $min) {
        $next_btn = "<div id=\"next\"></div>\n";
    } else {
        $next_btn = "<a href=\"foto.php?next=&file={$sor['file']}&katid={$sor['katid']}&userid={$_GET['userid']}&search={$_GET['search']}&list={$_GET['list']}\">\n
                    <div id=\"next\"><img src=\"items/next.png\" id=\"nextarrow\" alt=\"kovetkezo\"></div></a>\n";
    }
    if ($sor['file'] == $max) {
        $prev_btn = "<div id=\"prev\"></div>\n";
    } else {
        $prev_btn ="<a href=\"foto.php?prev=&file={$sor['file']}&katid={$sor['katid']}&userid={$_GET['userid']}&search={$_GET['search']}&list={$_GET['list']}\">\n
                    <div id=\"prev\"><img src=\"items/prev.png\" id=\"prevarrow\" alt=\"elozo\"></div></a>\n";
    }

//bezárás esetén vissza abba a galériába, ahonnan jött, keresés esetén a főoldalra
    if ($_GET['list'] == "src") {
        $closelink = "gallery.php?search={$_GET['search']}";
    } elseif ($_GET['list'] == "userid") {
        $closelink = "gallery.php?userid={$_GET['userid']}";
    } elseif ($_GET['list'] == "katid") {
        $closelink = "gallery.php?katid={$_GET['katid']}";
    } elseif ($_GET['list'] == "kedvenc") {
        $closelink = "gallery.php?kedvenc";
    } else{$closelink = "index.php";}    

//Like és kedvenc gombok képei változókban
    $like_img_on="heart40cb.png";
    $like_img_off="heart40c.png";
    $kedvenc_img_on="star40cy.png";
    $kedvenc_img_off="star40c.png";
    //Like és kedvenc lekérdezések
    include('like_count.php');

//kép nézet és adatlap összeállítása és megjelenítése
echo "<article class=\"$cls\">\n
    <div id=\"photoframe\">\n
        <img src=\"kepek/L/{$sor['file']}\" id=\"photo\" alt=\"kep\">\n
        <div id=\"navi\">\n
            <div id=\"navi-top\">\n
                <a href=\"$closelink\"><img src=\"items/close.png\" alt=\"bezar\"></a>\n
                <div><img id=\"enlarge\" src=\"items/enlarge.png\" alt=\"nagyit\"></div>\n
            </div>\n 
            <div id=\"navi-center\">\n
                $prev_btn
                $next_btn
            </div>\n
            <div id=\"navi-bottom\"></div>\n
        </div>\n
    </div>\n

    <div id=\"dataframe\">\n
        <div id=\"photodata\">\n
            <div id=\"data-header\">\n
                <div class=\"artist\" id=\"photo_data_artist\">\n
                    <a href=\"gallery.php?userid={$sor['userid']}\"><img src=\"users/{$sor['pkep']}\" alt=\"profilkep\"></a>\n
                    <a href=\"gallery.php?userid={$sor['userid']}\"><p>{$sor['nev']}</p></a>\n
                </div>\n
                <h4>{$sor['cim']}</h4>\n
            </div>\n
            <div id=\"exif\">\n
                <div class=\"exif\">\n
                    <div>\n
                        <img src=\"items/blende.png\" alt=\"blende\">\n
                        <p>{$sor['blende']}</p>\n
                    </div>\n
                    <div>\n
                        <img src=\"items/time.png\" alt=\"zarido\">\n
                        <p>{$sor['zarido']} s</p>\n
                    </div>\n              
                </div>\n
                <div class=\"exif\">\n
                    <div>\n
                        <img src=\"items/iso.png\" alt=\"iso\">\n
                        <p>{$sor['iso']}</p>\n
                    </div>\n
                    <div>\n
                        <img src=\"items/zoom.png\" alt=\"fokusz\">\n
                        <p>{$sor['focus']} mm</p>\n
                    </div>\n
                </div>\n
                <div class=\"exif\">\n
                    <div>\n
                        <img src=\"items/cam.png\" alt=\"kamera\">\n
                        <p>{$sor['kamera']}</p>\n
                    </div>\n
                    <div>\n
                        <img src=\"items/lens.png\" alt=\"objektiv\">\n
                        <p>{$sor['obi']}</p>\n
                    </div>\n
                </div>\n
            </div>\n
            <div id=\"calendar\">\n
                <img src=\"items/calendar.png\" alt=\"datum\">\n
                <p>{$sor['date']}</p>\n
            </div>\n
            <div id=\"desc\">\n
                <img src=\"items/pen.png\" alt=\"leiras\">\n
                <p>{$sor['story']}</p>\n
            </div>\n
        </div>\n 
        <div id=\"dataframe_bottom\">\n
            <form id=\"foto_like\" method=\"post\">\n
                <input type=hidden name=liked value=$file>\n
                <div id=\"star-mark\">\n
                    <button type=\"submit\" name=\"kedvencˇ\" class=\"like_button kedvenc\">
                        <img src=\"items/$kedvenc_img\" id=\"kedvenc_img\" alt=\"kedvenc\" onmouseover=\"like_hover(this)\" onmouseout=\"like_out(this)\">\n
                        <span id=\"count_kedvenc\">$db_kedvenc</span>\n
                    </button>\n
                    <span class=\"like_alert\" id=\"kedvenc_alert\">Jelentkezz be!</span>\n
                </div>\n
                <div id=\"like-mark\">\n
                    <button type=submit name=like class=\"like_button like\">\n
                        <img src=\"items/$like_img\" id=\"like_img\" alt=\"like\" onmouseover=\"like_hover(this)\" onmouseout=\"like_out(this)\">\n
                        <span id=count_like>$db_like</span>\n
                    </button>\n
                    <span class=\"like_alert\" id=\"like_alert\">Jelentkezz be!</span>\n
                </div>\n
            </form>\n
            $modmenu
        </div>\n
    </div>\n
</article>\n";

} else {
    echo "MySqli hiba (" . mysqli_errno($dbconn) . "): " . mysqli_error($dbconn) . "\n";
}

// Kép törlés
if(isset($_POST['delete'])){
    $sql="DELETE from foto WHERE file='$file'";
    if (mysqli_query($dbconn, $sql)) {
        @unlink("kepek/" . $file);
        @unlink("kepek/L/" . $file);
        echo "<script type='text/javascript'>document.location.href='{$closelink}';</script>";
        echo '<META HTTP-EQUIV="refresh" content="0;URL=' . $closelink . '">';
    }else{
        echo "MySqli hiba (" . mysqli_errno($dbconn) . "): " . mysqli_error($dbconn) . "\n";
    }
}
?>
        </main>
    </div>
    
<!-- Űrlapok, háttér elsötétítés ----------------------------------------->
    <div class="form_background" id="elsotetit" onclick="openreg(this)"></div>
    <div class="form_background" id="loading" ><img src="items/loading.gif"></div>
<!-- Regisztrációs űrlap ---------------------------------------------------->
<?php 
require("regform.php");
echo $regform;
?>
      
 <!-- Képfeltöltés űrlap------------------------------------------------------>
  <?php if (isset($uploadform)) echo $uploadform; ?>

  <script src="foto.js"></script>
  <script src="script.js"></script>

</body>
</html>