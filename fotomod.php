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
//Adatmódosító űrlap a képekhez
$file = $_GET['file'];
//Az űrlaphoz le kell kérdezni a kategóriákat
$sql = "SELECT * FROM kategoria";
if ($eredmeny = mysqli_query($dbconn, $sql)) {
    $kategoriak = "";
    while ($sor = mysqli_fetch_assoc($eredmeny)) {
        if ($sor['id'] == $_GET['katid']) {
            $kategoriak .= "<option selected value=\"{$sor['id']}\">{$sor['kategoria']}</option>\n";
        } else {
            $kategoriak .= "<option value=\"{$sor['id']}\">{$sor['kategoria']}</option>\n";
        }
    }
} else {
    $hiba = "MySqli hiba (" . mysqli_errno($dbconn) . "): " . mysqli_error($dbconn) . "\n";
}

//Kép adatok lekérdezése
$sql = "SELECT file, cim, story, blende, zarido, iso, focus, 
                kamera, obi, date, class, public, userid, nev, pkep
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
    if (!$sor['public']) {
        $checked = "checked";
    } else {
        $checked = "";
    }

//kép nézet és adatlap összeállítása és megjelenítése   
$article = "<article class=\"$cls\">\n
    <div id=\"photoframe\">\n
        <img src=\"kepek/L/{$sor['file']}\" alt=\"kep\">\n
        <div id=\"navi\">\n
            <div id=\"navi-top\">\n
                <a href=foto.php?file={$sor['file']}&katid={$_GET['katid']}&userid={$_GET['userid']}&search={$_GET['search']}&list={$_GET['list']}><img src=\"items/close.png\" alt=\"bezar\"></a>\n
                <div></div>\n
            </div>\n 
            <div id=\"navi-center\">\n
                <div id=\"next\"></div>\n
                <div id=\"prev\"></div>\n
            </div>\n
        </div>\n
    </div>\n
    <form id=\"dataframe\" method=\"post\">\n
        <div id=\"photodata\">\n
            <div id=\"data-header\">\n
                <div class=\"artist\" id=\"photo_data_artist\">\n
                    <a href=\"gallery.php?userid={$sor['userid']}\"><img src=\"users/{$sor['pkep']}\" alt=\"profilkep\"></a>\n
                    <a href=\"gallery.php?userid={$sor['userid']}\"><p>{$sor['nev']}</p></a>\n
                </div>\n
            </div>\n

            <div id=\"photodata_mod\">\n
                <label for=\"cim\"> Kép címe:\n
                <input type=\"text\" name=\"cim\" id=\"cim_mod\" title=\"A kép címe\" maxlength=\"32\" value=\"{$sor['cim']}\"></label><br>\n 
                <label for=\"kategoria\">Kategória:</label>\n
                <select name=\"kategoria\" id=\"kategoria_mod\" title=\"Kategória választás\">\n
                $kategoriak;
                </select></p>\n 
                <label><input type=\"checkbox\" name=\"public\" id=\"public_mod\" value=\"\" $checked> Nem publikus</label>\n
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
            <div id=\"desc_mod\">\n
                <label for=\"leiras\">Leírás a képről: </label>\n
                <textarea name=\"leiras\" id=\"leiras_mod\" cols=\"38\" rows=\"8\" maxlength=\"500\" placeholder=\"Itt írhatsz a kép készítéséről. Maximum 500 karakter!\">{$sor['story']}</textarea>\n
            </div>\n
        </div>\n
        <div id=\"edit-buttons\">\n
            <input class=\"gomb\" type=\"submit\" name=\"canc\" id=\"canc\" value=\"Mégsem\">\n
            <input class=\"gomb\" type=\"submit\" name=\"ment\" id=\"ment\" value=\"Mentés\">\n
        </div>\n
    </form>\n
</article>\n";
    echo $article;

} else {
    echo "MySqli hiba (" . mysqli_errno($dbconn) . "): " . mysqli_error($dbconn) . "\n";
}

//űrlap feldolgozás
if (isset($_POST['ment'])) {
    function tisztit($dbconn, $text){
        return mysqli_real_escape_string($dbconn, stripslashes(strip_tags(trim($text))));
    }
    $cim = tisztit($dbconn, $_POST['cim']);
    $leiras = tisztit($dbconn, $_POST['leiras']);
    $kategoria = $_POST['kategoria'];
    if (isset($_POST['public'])) {
        $public = 0;
    } else {
        $public = 1;
    }

    $sql = "UPDATE foto SET katid=$kategoria, cim='$cim', 
         story='$leiras', public=$public 
          WHERE file='{$_GET['file']}'";
    if (mysqli_query($dbconn, $sql)) {
        $URL="foto.php?file={$sor['file']}&katid={$_GET['katid']}&userid={$_GET['userid']}&search={$_GET['search']}&list={$_GET['list']}";
        echo "<script type='text/javascript'>document.location.href='{$URL}';</script>";
        echo '<META HTTP-EQUIV="refresh" content="0;URL=' . $URL . '">';
    } else {
        echo "MySqli hiba (" . mysqli_errno($dbconn) . "): " . mysqli_error($dbconn) . "\n";
    }   
}
if (isset($_POST['canc'])) {
    $URL="foto.php?file={$sor['file']}&katid={$_GET['katid']}&userid={$_GET['userid']}&search={$_GET['search']}&list={$_GET['list']}";
    echo "<script type='text/javascript'>document.location.href='{$URL}';</script>";
    echo '<META HTTP-EQUIV="refresh" content="0;URL=' . $URL . '">';
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

  <script src="script.js"></script>

</body>
</html>