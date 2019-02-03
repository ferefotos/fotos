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
            
<!-- Galéria ---------------------------------------------------------------->
            <h2>Legújabb feltöltések</h2>    
            <div id="gallery">
<?php 
/*A bejelentkezett felhasználónak a nem publikus saját képeit is meg kell jeleníteni a galériában*/
if (isset($_SESSION['userid'])) {
    $term = "OR artist='{$_SESSION['userid']}' AND public=0";
} else { $term = "";}

//Like és kedvenc gombok képei változókban
$like_img_on="heart24cb.png";
$like_img_off="heart24c.png";
$kedvenc_img_on="star24cy.png";
$kedvenc_img_off="star24c.png";

// A likeolást feldolgozó form
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
        $img_id = strtok($sor['file'], ".");
    //Like és kedvenc lekérdezések
    include('like_count.php');

    echo "<div class=\"image {$sor['class']}\" id=\"{$img_id}\" onmousemove=\"showinfo(this)\">\n
            <a href=\"foto.php?file=$file&katid=&userid=&search=&list=\">\n
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
?>
                <div class="image blank"></div>
                <div class="image blank"></div>
                <div class="image blank"></div>
                <div class="image blank"></div>
                <div class="image blank"></div>
            </div>
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