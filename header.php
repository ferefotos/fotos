<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Zoom || Fotós közösségi oldal</title>
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/responsive.css">
    <link href="https://fonts.googleapis.com/css?family=Source+Serif+Pro" rel="stylesheet">
    <!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>-->
    <script src=script/jquery-3.3.1.min.js></script>
</head>
<!--<body oncontextmenu="return false;">-->
<body>
    <!-- Űrlapok, háttér elsötétítése ----------------------------------------->
    <div class="form_background" id="elsotetit" onclick="openreg(this)"></div>
    <div class="form_background" id="loading" ><img src="items/loading.gif"></div>

    <div class="container">
        <aside>
           <?php require("aside.php"); ?>
        </aside>
        <main>
            <header>
                <div id="top-left">
                    <div class="ddmenu" id="catmenu" onmouseover="onmenu(this)" onmouseout="outmenu()">
                        <div class="mainmenu"><a href="#">Kategóriák</a></div>
                        <img id="catmenu-resp" src="items/cat-list.png" alt="">
                                        <ul class="submenu" id="catmenu-sub">
                                            <?php echo $kategoriak; ?>
                                        </ul>
                                   
                              
        </div>
                    <div id="infomenu" class="ddmenu"><a href="#">Ismertető</a></div>
                    <div id="infomenu-resp"><img src="items/infomenu.png" alt=""></div>
                </div>

                <div id="top-center">
                    <div class="logo">
                        <a href="index.php"><img src="items/logo.png" alt="Zoom"></a>
                    </div>
                </div>

                <div id="top-right">
                    <div id="uploadmenu">
                    <?php 
                    if (isset($_SESSION['userid'])) {
                        echo "<form id=\"foto_up_form\" method=\"post\" name=\"foto_up_form\" enctype=\"multipart/form-data\">\n
                                <label><img src=\"items/upload.png\" id=\"uplink-log\" alt=\"feltöltés\">\n
                                <input type=\"file\" name=\"foto\" id=\"foto_up\" onchange=\"this.form.submit()\"></label>\n
                              </form>\n";
                    } else {
                        echo "<img src=\"items/upload.png\" id=\"uplink-notlog\" alt=\"feltöltés\">\n";
                    }
                    ?> 
                    </div>

                    <div class="hibak" id="alertbox"><h3 id="alert"><?php if (isset($hiba)) echo $hiba; ?></h3></div>

                    <div class="ddmenu" id="usermenu" onmouseover="onmenu(this)" onmouseout="outmenu()">
                        <div class="mainmenu"><a href="#"><?php echo isset($_SESSION['keresztnev']) ? $_SESSION['keresztnev'] : "Bejelentkezés"; ?></a>
                            <img id="menu-pkep" src="<?php echo isset($_SESSION['pkep']) ? "users/" . $_SESSION['pkep'] : "users/avatar.png"; ?>" alt="avatar">
                        </div> 
                    <?php 
                    if (isset($_SESSION['userid'])) {
                        echo "<ul class=\"submenu submenu-user\" id=\"usermenu-sub\">\n
                                    <li><a href=\"gallery.php?userid={$_SESSION['userid']}\">Saját képek</a></li>\n
                                    <li><a href=\"gallery.php?kedvenc=\">Kedvencek</a></li>\n
                                    <li id=\"regmod\" onclick=\"openreg(this)\"><a href=\"#\">Adatmódosítás</a></li>\n
                                    <form id=\"logoutform\"><input type=\"submit\" class=\"gomb\" name=\"logout\" id=\"logout\" value=\"Kijelentkezés\"></form>\n
                              </ul>";
                    } else {
                        echo "<form class=\"submenu logmenu\" id=\"usermenu-sub\">\n
                                    <p>\n
                                        <label for=\"userid\">Felhasználónév:</label><br>\n
                                        <input type=\"text\" name=\"userid\" id=\"userid\" onclick=\"inlog()\" onblur=\"outlog()\" required autofocus title=\"Felhasználónév\">\n
                                    </p>\n
                                    <p>\n
                                        <label for=\"jelszo\">Jelszó:</label><br>\n
                                        <input type=\"password\" name=\"jelszo\" id=\"password\" onclick=\"inlog()\" onblur=\"outlog()\" required title=\"Jelszó\">\n
                                    </p>\n
                                    <p><input class=\"gomb\" type=\"submit\" name=\"login\" id=\"login\" onblur=\"outlog()\" value=\"Bejelentkezés\"></p>\n
                                    <p class=\"hibak\" id=error></p>\n
                                    <ul><li id=\"reglink\" onclick=\"openreg(this)\"><a href=\"#\">Regisztráció</a></li></ul>\n
                              </form>\n ";
                    }
                    ?>
                    </div>
                </div>
            </header>