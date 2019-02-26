<?php
require("config.php");
require("header.php");
require(ROOT_PATH. "/form/uploadform.php");
require(ROOT_PATH. "/form/regform.php");
?>
<!-- Főoldal a legújabb feltöltésekkel ------------------------------------------------------>
    <h2>A legújabb feltöltések</h2>    
    <div id="gallery">
<?php 
/* A bejelentkezett felhasználónak a nem publikus saját képeit is meg kell jeleníteni a galériában */
if (isset($_SESSION['userid'])) {
    $term = "OR artist='{$_SESSION['userid']}' AND public=0";
} else { $term = "";}

// Like és kedvenc gombok képei változókban
$like_img_on="heart24cb.png";
$like_img_off="heart24c.png";
$kedvenc_img_on="star24cy.png";
$kedvenc_img_off="star24c.png";

// A lájkolást feldolgozó form
echo "<form id=\"foto_like\" method=\"post\"></form>\n";

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
    include(ROOT_PATH. '/react/like_count.php');

    echo "<div class=\"image {$sor['class']}\" id=\"{$img_id}\" onmousemove=\"showinfo(this)\">\n
            <a href=\"foto.php?file=$file&katid=&userid=&search=&list=\">\n
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
  <script src="script/ajaxform.js"></script>
  <script src="script/script.js"></script>
  
</body>
</html>