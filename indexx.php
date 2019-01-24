<?php
session_start();
require("connect.php");
require("aside.php"); 

?><!DOCTYPE html>
<html lang="hu">

<head>
    <meta charset="UTF-8">
    <title>weboldal</title>
    <link rel="stylesheet" href="gallery.css">
    <link href="https://fonts.googleapis.com/css?family=Gentium+Book+Basic" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src=script-top.js></script>
    
</head>
<body>
    <div class="container">
        <aside>
            <div class="logo">
            <img src="items/logo.png" alt="Zoom">
            </div>
            <nav>
               <form action="" method="get">
                    <input type="search" name="kereso" id="kereso" title="keresés">
                    <button type="submit" name="keres" value="">
                        <img src="items/find.png" alt="keresés" title="keresés">
                    </button>
                </form>

                <h3>Kategóriák</h3>
                <?php echo $kategoriak;?>
            </nav>
        </aside>

        <main>
            <header>
                <div id="top-left">
                    <div class="ddmenu" id="catmenu" onmouseover="menuover(this)" onmouseout="menuout()">
                        <div class="mainmenu"><a href="#">Kategóriák</a></div>
                        <img id="catmenu-resp" src="items/cat-list.png" alt="">
                        <ul class="submenu" id="catmenu-sub">
                            <li><a href="#">Állat</a></li>
                            <li><a href="#">Tájkép</a></li>
                            <li><a href="#">Makró</a></li>
                            <li><a href="#">Portré</a></li>
                            <li><a href="#">Város</a></li>
                        </ul>
                    </div>

                    <div id="infomenu" class="ddmenu"><a href="#">Ismertető</a></div>
                    <div id="infomenu-resp"><img src="items/infomenu.png" alt=""></div>
                </div>

                <div id="top-center">
                    <div class="logo">
                        <img src="items/logo.png" alt="Zoom">
                    </div>
                </div>

                <div id="top-right">
                   <div id="uploadmenu">
                   <?php 
                   if(isset($_SESSION['userid'])){
                       echo "<form id=\"foto_up_form\" method=\"post\" name=\"foto_up_form\" enctype=\"multipart/form-data\">\n";
                       echo "<label><img src=\"items/upload.png\" alt=\"feltöltés\">\n";
                       echo "<input type=\"file\" name=\"foto\" id=\"foto_up\" onchange=\"this.form.submit()\"></label></form>\n";
                   }else{
                       echo "<img src=\"items/upload.png\" id=\"uplink-notlog\" alt=\"feltöltés\">\n";
                   }
                   ?> 
                   </div>

                <div class="hibak" id="alertbox"><h3 id="alert"><?php if(isset($hiba)) echo $hiba; ?></h3></div>

                    <div class="ddmenu" id="usermenu" onmouseover="menuover(this)" onmouseout="menuout()">
                        <div class="mainmenu"><a href="#"><?php echo isset($_SESSION['keresztnev']) ? $_SESSION['keresztnev'] : "Bejelentkezés"; ?></a>
                            <img id="menu-pkep" src="<?php echo isset($_SESSION['pkep']) ? "users/".$_SESSION['pkep'] : "users/avatar.png"; ?>" alt="avatar">
                        </div> 
                        <?php 
                        if(isset($_SESSION['userid'])){
                            $usermenu= "
                            <ul class=\"submenu submenu-user\" id=\"usermenu-sub\">\n
                            <li><a href=\"#\">Kedvencek</a></li>\n
                            <li><a href=\"#\">Profil</a></li>\n
                            <li id=\"regmod\" onclick=\"openreg(this)\"><a href=\"#\">Adatmódosítás</a></li>\n
                            <form id=\"logoutform\"><input type=\"submit\" class=\"gomb\" name=\"logout\" id=\"logout\" value=\"Kijelentkezés\"></form>\n
                            </ul>";
                        }else{
                            $usermenu= "
                            <form class=\"submenu logmenu\" id=\"usermenu-sub\">\n
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
                        echo $usermenu; ?>
                    </div>
                </div>

            </header>
            
<!-- Galéria ---------------------------------------------------------------->
            <h2>Galéria</h2>    

            <div id="gallery">
                <div class="image" id="o2414" onmousemove="showinfo(this)"><a href="foto.html"><img src="kep/IMG_2414.JPG" alt="2414"></a>
                    <div class="info">
                        <div class="user">
                            <img src="profil.jpg" alt="img">
                            <p>Szatmári Ferenc</p>
                        </div>
                        <div class="info-right">
                            <div class="kedvenc">
                                <img src="items/star_c.png" alt="img">
                            </div>
                            <div class="like">
                                <img src="items/heart-c.png" alt="img">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="image portrait" id="o2231" onmousemove="showinfo(this)"><img src="kep/IMG_2231.JPG" alt="2414">
                    <div class="info">
                        <div class="user">
                            <img src="profil.jpg" alt="img">
                            <p>Szatmári Ferenc</p>
                        </div>
                        <div class="info-right">
                            <div class="kedvenc">
                                <img src="items/star_c.png" alt="img">
                            </div>
                            <div class="like">
                                <img src="items/heart-c.png" alt="img">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="image wide" id="o2766w" onmouseover="showinfo(this)"><img src="kep/IMG_2766w.JPG" alt="2414">
                    <div class="info">
                        <div class="user">
                            <img id="pk" src="profil.jpg" alt="img">
                            <p>Szatmári Ferenc</p>
                        </div>
                        <div class="info-right">
                            <div class="kedvenc">
                                <img src="items/star_c.png" alt="img">
                            </div>
                            <div class="like">
                                <img src="items/heart-c.png" alt="img">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="image square"><img src="kep/IMG_22161.JPG" alt="2414">
                    <div class="info">Értékelés</div>
                </div>
                <div class="image portrait"><img src="kep/IMG_2367.JPG" alt="2414"></div>
                <div class="image"><img src="kep/IMG_2510.JPG" alt="2414">
                    <div class="info">Értékelés</div>
                </div>
                <div class="image portrait"><img src="kep/IMG_2545.JPG" alt="2414"></div>
                <div class="image"><img src="kep/IMG_2572.JPG" alt="2414"></div>
                <div class="image wide"><img src="kep/IMG_2692w.JPG" alt="2414"></div>
                <div class="image"><img src="kep/IMG_2612.JPG" alt="2414"></div>
                <div class="image portrait"><img src="kep/IMG_2326.JPG" alt="2414"></div>
                <div class="image"><img src="kep/IMG_2460.JPG" alt="2414"></div>

                <div class="image"><img src="kep/IMG_2288.JPG" alt="2414"></div>
                <div class="image"><img src="kep/IMG_2364.JPG" alt="2414"></div>
                <div class="image portrait"><img src="kep/IMG_2538.JPG" alt="2414"></div>
                <div class="image wide"><img src="kep/IMG_2557.JPG" alt="2414"></div>
                <div class="image"><img src="kep/IMG_2565.JPG" alt="2414"></div>
                <div class="image wide"><img src="kep/IMG_2578.JPG" alt="2414"></div>
                <div class="image"><img src="kep/IMG_2599.JPG" alt="2414"></div>
                <div class="image portrait"><img src="kep/IMG_2367.JPG" alt="2414"></div>
                <div class="image"><img src="kep/IMG_2216.JPG" alt="2414"></div>
                <div class="image portrait"><img src="kep/IMG_2538.JPG" alt="2414"></div>
                <div class="image portrait"><img src="kep/IMG_2326.JPG" alt="2414"></div>
                <div class="image portrait"><img src="kep/IMG_2367.JPG" alt="2414"></div>
                <div class="image portrait"><img src="kep/IMG_2538.JPG" alt="2414"></div>
                <div class="image portrait"><img src="kep/IMG_2326.JPG" alt="2414"></div>
                <div class="image portrait"><img src="kep/IMG_2538.JPG" alt="2414"></div>
                <div class="image portrait"><img src="kep/IMG_2326.JPG" alt="2414"></div>
                <div class="image portrait"><img src="kep/IMG_2538.JPG" alt="2414"></div>
                <div class="image"><img src="kep/IMG_2216.JPG" alt="2414"></div>



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
  <?php
    require("upload.php");
   if(isset($uploadform)) echo $uploadform; 
   ?>

  <script src="script.js"></script>
  
    
    

</body>

</html>