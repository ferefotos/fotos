<?php
require("config.php");
require("header.php");
require(ROOT_PATH. "/form/uploadform.php");
require(ROOT_PATH. "/form/regform.php");
require_once(ROOT_PATH. "/form/common.php");
?>
<!-- Főoldal a legújabb feltöltésekkel ------------------------------------------------------>
    <h2>A legújabb feltöltések</h2>    
    <div id="gallery">
    <!-- A lájkolást feldolgozó form -->
    <form id=foto_like method=post></form>

<?php 
// A főoldalon a 30 legfrissebb feltöltés jelenik meg
$sql = "SELECT file, class, nev, pkep, userid FROM foto
        JOIN user ON userid=artist 
        WHERE public=1 $term
        ORDER BY file DESC
        LIMIT 30";
if ($eredmeny = mysqli_query($dbconn, $sql)) {
    while ($sor = mysqli_fetch_assoc($eredmeny)) {
        $nev = mb_substr($sor['nev'], mb_strpos($sor['nev'], " "));
        $file= $sor['file'];
        $img_id = strtok($sor['file'], "."); //Egyedi azonosító a fájl nevéből
      //Like és kedvenc lekérdezések
        $db_like = db_like($file);
        $db_kedvenc = db_kedvenc($file);
        $like_img = "heart24c.png";
        $kedvenc_img = "star24c.png";
      // változik a gomb ikon színe ha a felhasználó jelölte a képet 
        if(isset($_SESSION['userid'])){
            if(kedvelt($file)) $like_img = "heart24cb.png";
            if(kedvenc($file)) $kedvenc_img = "star24cy.png"; 
        }
    echo "<div class=\"image {$sor['class']}\" id=\"{$img_id}\" onmousemove=\"showinfo(this)\">\n
            <a href=\"foto.php?file=$file&list=\">\n
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
} else {
    echo "MySqli hiba (" . mysqli_errno($dbconn) . "): " . mysqli_error($dbconn) . "\n";
}
mysqli_close($dbconn);
?>
            </div>
        </main>
    </div>
  <script src="script/ajaxform.js" type="text/javascript"></script>
  <script src="script/script.js" type="text/javascript"></script>
</body>
</html>