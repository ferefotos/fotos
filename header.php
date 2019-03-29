<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name=description content="Amatőr fotósok, fotózás iránt érdeklődők megoszthatják egymással legjobban sikerült képeiket.
                                    A feltöltött képeket értékelni lehet szövegesen és lájkolással.">
    <meta name=keywords content="fényképek, képfeltöltés, fotós, közösség, like, fotógaléria, tájkép, portré"> 
    <meta name=author content="Szatmári Ferenc">
    <meta name=generator content="Visual Studio Code">                               
    <title>Zoom || Fotós közösségi oldal</title>
    <link rel="shortcut icon" type="image/png" href="items/favico.png" />
    <link rel="stylesheet" href="css/normalize.css" type="text/css">
    <link rel="stylesheet" href="css/style.css" type="text/css">
    <link rel="stylesheet" href="css/responsive.css" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Source+Serif+Pro" rel="stylesheet">
    <script src="script/jquery-3.3.1.min.js"  type="text/javascript"></script>
</head>
<!--<body oncontextmenu="return false;">-->
<body>
    <!-- Űrlapok, háttér elsötétítése ----------------------------------------->
    <div class="form_background" id="elsotetit" onclick="openreg(this)"></div>
    <div class="form_background" id="loading" ><img src="items/loading.gif"></div>

    <div class="container">
        <aside>
           <?php include("aside.php"); ?>
        </aside>
        <main>
            <header>
                <div id="top-left">
                    <div class="ddmenu" id="catmenu" onmouseover="onmenu(this)" onmouseout="outmenu()">
                        <div class="mainmenu"><a href="#">Kategóriák</a></div>
                        <img id="catmenu-resp" src="items/cat-list.png">
                            <ul class="submenu" id="catmenu-sub">
                               <?php echo $kategoriak; ?>
                            </ul>                                                
                    </div>
                    <div id="infomenu" class="ddmenu mainmenu"><a href="info.php">Ismertető</a></div>
                    <a href="info.php" id="infomenu-resp"><img src="items/infomenu.png" alt="Info"></a>
                </div>
                <div id="top-center">
                    <div class="logo">
                        <a href="index.php"><img src="items/logo.png" alt="Zoom"></a>
                    </div>
                </div>
                <div id="top-right">
                    <div id="uploadmenu">
                    <?php if (isset($_SESSION['userid'])): ?>
                        <form id="foto_up_form" method="post" name="foto_up_form" enctype="multipart/form-data">
                            <label>
                                <img src="items/upload.png" id="uplink-log" alt="feltöltés" onmouseover="img_upload(this, 'upload_h.png')" onmouseleave="img_upload(this, 'upload.png')">
                                <input type="file" name="foto" id="foto_up" onchange="this.form.submit()">
                            </label>
                        </form>
                    <?php else: ?>    
                        <img src="items/upload.png" id="uplink-notlog" alt="feltöltés" onmouseover="img_upload(this, 'upload_h.png')" onmouseleave="img_upload(this, 'upload.png')">
                    <?php endif ?>    
                    </div>
                    <div class="hibak" id="alertbox">
                        <h3 id="alert"  ><?php if (isset($_SESSION['hiba'])) echo $_SESSION['hiba']; $_SESSION['hiba'] =NULL; ?></h3>
                    </div>
                    <div class="ddmenu" id="usermenu" onmouseover="onmenu(this)" onmouseout="outmenu()">
                        <div class="mainmenu">
                            <a href="#"><?php echo isset($_SESSION['keresztnev']) ? $_SESSION['keresztnev'] : "Bejelentkezés"; ?></a>
                            <img id="menu-pkep" src="<?php echo isset($_SESSION['pkep']) ? "users/" . $_SESSION['pkep'] : "users/avatar.png"; ?>" alt="avatar">
                        </div> 
                    <?php if (isset($_SESSION['userid'])): ?>
                        <ul class="submenu submenu-user" id="usermenu-sub">
                            <li><a href="gallery.php?userid=<?php echo $_SESSION['userid'] ?>">Saját képek</a></li>
                            <li><a href="gallery.php?kedvenc=">Kedvencek</a></li>
                            <li id="regmod" onclick="openreg(this)">Adatmódosítás</li>
                            <form id="logoutform">
                                <input type="submit" class="gomb" name="logout" id="logout" value="Kijelentkezés">
                            </form>
                        </ul>
                    <?php else: ?>
                        <form class="submenu logmenu" id="usermenu-sub">
                            <p>
                                <label for="userid">Felhasználónév:</label><br>
                                <input type="text" name="userid" id="userid" onclick="inlog()" onblur="outlog()" required autofocus title="Felhasználónév">
                            </p>
                            <p>
                                <label for="jelszo">Jelszó:</label><br>
                                <input type="password" name="jelszo" id="password" onclick="inlog()" onblur="outlog()" required title="Jelszó">
                            </p>
                            <p><input class="gomb" type="submit" name="login" id="login" onblur="outlog()" value="Bejelentkezés"></p>
                            <p class="hibak" id=error></p>
                            <ul><li id="reglink" onclick="openreg(this)">Regisztráció</li></ul>
                        </form> 
                    <?php endif ?>
                    </div>
                </div>
            </header>