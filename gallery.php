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
//Címsor kategóriára szűrésnél
if (isset($_GET['katid'])) {
    $sql = "SELECT kategoria FROM kategoria WHERE id={$_GET['katid']}";
    $eredmeny = mysqli_query($dbconn, $sql);
    $sor = mysqli_fetch_assoc($eredmeny);
        echo "<h2>{$sor['kategoria']}</h2>";
}
//Címsor keresésnél
if (isset($_GET['search'])){
    echo "<h2>Keresés eredménye a(z) {$_GET['search']} kifejezésre:</h2>";
}
// Címsor felhasználóra szűrésnél, és felhasznló adatlap
if (isset($_GET['userid'])){
    $sql = "SELECT nev, pkep, rolam, cam, lens FROM user WHERE userid='{$_GET['userid']}'";
    $eredmeny = mysqli_query($dbconn, $sql);
    $sor = mysqli_fetch_assoc($eredmeny);
    echo "<div id=\"user_gallery_title\" onclick=\"show_userinfo()\"><img src=\"users/{$sor['pkep']}\" alt=\"profilkep\">\n
          <h3>{$sor['nev']} fotói</h3><img src=\"items/down.png\" id=\"arrow\" ></div>\n";
    echo"<div id=\"userinfo\">\n
            <div id=\"userinfo_cam\">\n
                <div>\n
                    <img src=\"items/cam_blue.png\" alt=\"kamera\">\n
                    <p>{$sor['cam']}</p>\n
                </div>\n
                <div>\n
                    <img src=\"items/lens_blue.png\" alt=\"objektiv\">\n
                    <p>{$sor['lens']}</p>\n
                </div>\n
            </div>\n   
            <div id=\"userinfo_rolam\">\n
                <img src=\"items/pen_blue.png\" alt=\"rolam\">\n
                <p>{$sor['rolam']}</p>\n
            </div>\n
        </div>\n";
}
// Címsor kedvenc képekre
if(isset($_GET['kedvenc'])){
    echo "<h2>Kedvenc képeim</h2>";
}


// A lájkolást feldolgozó form
echo "<form id=\"foto_like\" method=\"post\"></form>\n";

echo "<div id=\"gallery\">\n";

?>          
            </div>
            <div id="message"></div>
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
    <script src="lapozo.js"></script>
  
</body>
</html>