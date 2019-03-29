<?php
require("config.php");
require_once("header.php");
require(ROOT_PATH. "/form/uploadform.php");
require(ROOT_PATH. "/form/regform.php");
     
/*******************************
 *           Galéria           *
 *******************************/              

//Címsor kategóriára szűrésnél
if (isset($_GET['katid'])):
    $sql = "SELECT kategoria FROM kategoria WHERE id={$_GET['katid']}";
    $eredmeny = mysqli_query($dbconn, $sql);
    $sor = mysqli_fetch_assoc($eredmeny);
?>
    <h2><?php echo $sor['kategoria'] ?></h2>
<?php endif ?>

<!-- Címsor keresésnél -->
<?php if (isset($_GET['search'])): ?>
    <?php if(!empty($_GET['search'])): ?>
    <h2>Keresés eredménye a(z) <?php echo $_GET['search'] ?> kifejezésre:</h2>
    <?php else: ?>
    <h3>Nem adtál meg kulcszót a kereséshez - eredmény szűrés nélkül:</h3>
    <?php endif ?>
<?php endif ?>

<!-- Címsor felhasználóra szűrésnél, és a felhasználó adatlapja -->
<?php
if (isset($_GET['userid'])):
    $sql = "SELECT nev, pkep, rolam, cam, lens FROM user WHERE userid='{$_GET['userid']}'";
    $eredmeny = mysqli_query($dbconn, $sql);
    $sor = mysqli_fetch_assoc($eredmeny);
?>
    <div id="user_gallery_title" onclick="show_userinfo()">
        <img src="users/<?php echo $sor['pkep'] ?>" alt="profilkép">
        <h3><?php echo $sor['nev'] ?> fotói</h3>
        <img src="items/down.png" id="arrow" >
    </div>
    <div id="userinfo">
        <div id="userinfo_cam">
            <div>
                <img src="items/cam_blue.png">
                <p><?php echo $sor['cam'] ?></p>
            </div>
            <div>
                <img src="items/lens_blue.png">
                <p><?php echo $sor['lens'] ?></p>
            </div>
        </div>   
        <div id="userinfo_rolam">
            <img src="items/pen_blue.png">
            <p><?php echo $sor['rolam'] ?></p>
        </div>
    </div>
<?php endif ?>
<!-- Címsor: kedvenc képek -->
<?php if(isset($_GET['kedvenc'])): ?>
    <h2>Kedvenc képeim</h2>
<?php endif ?>
<!-- Címsor: TOP60 képek -->
<?php if(isset($_GET['toplist'])): ?>
    <h2>A legnépszerűbb fotók</h2>
<?php endif ?>
<?php mysqli_close($dbconn) ?>

<!--A lájkolást feldolgozó form input mezői -a 'like' és 'kedvenc' gombok- minden egyes
      bélyegképnél találhatóak, egyedi azonosítóval, amely a fájl nevéből származik.-->
            <form id=foto_like method=post></form>
    <!--A képgaléria összeállítása a gallery_list.php-ban történik. Végtelen lapozóval 
         és a lapozo.js tölti be a gellery azonosítójú div-be.-->
            <div id="gallery"></div>
            <div id="message"></div>
        </main>
    </div>

<script src="script/ajaxform.js" type="text/javascript"></script>
<script src="script/script.js" type="text/javascript"></script>
<script src="script/lapozo.js" type="text/javascript"></script>
</body>
</html>