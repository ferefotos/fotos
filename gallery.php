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
<?php
/*A bejelentkezett felhasználónak a nem publikus saját képeit is meg kell jeleníteni a galériában*/
if (isset($_SESSION['userid'])) {
    $term = "OR artist='{$_SESSION['userid']}' AND public=0";
} else {$term = "";}
//Kategória szerinti listázás
$katid="";
if (isset($_GET['katid'])) {
    $list="katid";
    $katid=$_GET['katid'];
    $sql = "SELECT kategoria FROM kategoria WHERE id={$_GET['katid']}";
    $eredmeny = mysqli_query($dbconn, $sql);
    $sor = mysqli_fetch_assoc($eredmeny);
        echo "<h2>{$sor['kategoria']}</h2>";
        $sql = "SELECT file, class, nev, pkep, userid FROM foto
            JOIN user ON userid=artist 
            WHERE katid= {$_GET['katid']} AND (public=1 $term)
            ORDER BY file DESC";
}
//Keresés több mezőben is
$search="";
if (isset($_GET['search'])){
    $list="src"; $search=$_GET['search'];
    echo "<h2>Keresés eredménye a(z) '$search' kifejezésre:</h2>";
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
            ORDER BY file DESC";
}
// Felhasználó szerinti listázás
$userid="";
if (isset($_GET['userid'])){
    $list="userid";
    $userid=$_GET['userid'];
    $sql = "SELECT nev, pkep FROM user WHERE userid='{$_GET['userid']}'";
    $eredmeny = mysqli_query($dbconn, $sql);
    $sor = mysqli_fetch_assoc($eredmeny);
    echo "<div id=\"user_gallery_title\"><img src=\"users/{$sor['pkep']}\" alt=\"profilkep\">\n
          <h3>{$sor['nev']} fotói</h3></div>\n";
    $sql = "SELECT file, class, nev, pkep, userid FROM foto
            JOIN user ON userid=artist 
            WHERE artist= '{$_GET['userid']}' AND (public=1 $term)
            ORDER BY file DESC";
}
// Kedvenc képek listázása
if(isset($_GET['kedvenc'])){
    echo "<h2>Kedvenc képeim</h2>";
    $list="kedvenc";
    $sql="SELECT file, class, nev, pkep, userid FROM foto
          JOIN kedvenc ON filename=file
          JOIN user ON userid=artist
          WHERE jelolo='{$_SESSION['userid']}'
          ORDER BY file DESC";
}
//Like és kedvenc gombok képei változókban
$like_img_on="heart24cb.png";
$like_img_off="heart24c.png";
$kedvenc_img_on="star24cy.png";
$kedvenc_img_off="star24c.png";

// A likeolást feldolgozó form
echo "<form id=\"foto_like\" method=\"post\"></form>\n";

//Galéria elkészítése
/*A bélyegképekről link a foto.php-ra, ahol a képet nagyobb méretben az adatlapjával láthatjuk.
 * A linken a $_GET szuperglobális változóban átadjuk a fájl nevét, a kép kategória azonosítóját,
 *  a képhez tartozó userid-t és azt, hogy milyen szűrő volt alkalmazva. 
 * (pl. kategóriára szűrtünk, vagy felhasználóra, vagy a keresővel szűrtünk.)
 */
echo "<div id=\"gallery\">\n";
if ($eredmeny = mysqli_query($dbconn, $sql)) {
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