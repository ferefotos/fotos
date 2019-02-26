<?php
require("config.php");
require_once("header.php");
require(ROOT_PATH. "/form/uploadform.php");
require(ROOT_PATH. "/form/regform.php");
     
/*******************************
 *           Galéria           *
 *******************************/              

//Címsor kategóriára szűrésnél
if (isset($_GET['katid'])) {
    $sql = "SELECT kategoria FROM kategoria WHERE id={$_GET['katid']}";
    $eredmeny = mysqli_query($dbconn, $sql);
    $sor = mysqli_fetch_assoc($eredmeny);
    echo "<h2>{$sor['kategoria']}</h2>";
}
//Címsor keresésnél
if (isset($_GET['search'])){
    if(!empty($_GET['search'])){
        echo "<h2>Keresés eredménye a(z) {$_GET['search']} kifejezésre:</h2>";
    }else{
        echo "<h3>Nem adtál meg kulcszót a kereséshez - eredmény szűrés nélkül:</h3>";
    }
}
// Címsor felhasználóra szűrésnél, és felhasználó adatlap
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
//címsor TOP60 képekre
if(isset($_GET['toplist'])){
    echo "<h2>A legnépszerűbb fotók</h2>";
}
mysqli_close($dbconn);
?>      <!--A lájkolást feldolgozó form input mezői -a 'like' és 'kedvenc' gombok- minden egyes
              bélyegképnél találhatóak, egyedi azonosítóval, amely a fájl nevéből származik.-->
            <form id=foto_like method=post></form>
        <!--A képgaléria összeállítása a gallery_list.php-ban történik. Végtelen lapozóval 
             és a lapozo.js tölti be a gellery azonosítójú div-be.-->
            <div id="gallery"></div>
            <div id="message"></div>
        </main>
    </div>

  <script src="script/ajaxform.js"></script>
  <script src="script/script.js"></script>
  <script src="script/lapozo.js"></script>
  
</body>
</html>