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
    <script>
        function preview_image(event){
            var reader = new FileReader();
            reader.onload = function(){
            var output = document.getElementById('profkep_image');
            output.src = reader.result;
            }
        reader.readAsDataURL(event.target.files[0]);
        }
    </script>
    
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
                   <div id="uploadmenu"><img src="items/upload.png" id="uplink" onclick="openreg(this)" alt="feltöltés"></div>
                   
                    <div class="ddmenu" id="usermenu" onmouseover="menuover(this)" onmouseout="menuout()">
                        <div class="mainmenu"><a href="#"><?php echo isset($_SESSION['keresztnev']) ? $_SESSION['keresztnev'] : "Bejelentkezés"; ?></a>
                            <img id="menu-pkep" src="<?php echo isset($_SESSION['pkep']) ? "users/".$_SESSION['pkep'] : "users/avatar.png"; ?>" alt="avatar">
                        </div> 
                        <?php 
                        if(isset($_SESSION['userid'])){
                            $usermenu= "
                            <ul class=\"submenu submenu-user\" id=\"usermenu-sub\">\n
                            <li><a href=\"#\">Profil</a></li>\n
                            <li><a href=\"#\">Kedvencek</a></li>\n
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
    <div id="elsotetit" onclick="openreg(this)"></div>
<!-- Regisztrációs űrlap ---------------------------------------------------->
    <fieldset id="reg" class="reg" name="regisztracio">
        <legend>Regisztráció</legend>
        <form id="regform" enctype="multipart/form-data" action="#">   
                <div id="reg-top">
                    <div>
                        <p>
                            <label for="user">Felhasználónév: *</label><br>
                            <input type="text" name="userid" id="user" autofocus value="" title="Minimum 6 karakter!">
                        </p>
                        <p>
                            <label for="jelszo">Jelszó: *</label><br>
                            <input type="password" name="jelszo" id="jelszo" value="" title="Minimum 8 karakter!">
                        </p>
                        <p>
                            <label for="jelszo2">Jelszó ismét: *</label><br>
                            <input type="password" name="jelszo2" id="jelszo2" value="" title="Jelszó megismétlése!">
                        </p>
                        <p>
                            <label for="email">E-mail cím: *</label><br>
                            <input type="email" name="email" id="email" value="" title="e-mail címed">
                        </p>
                        <p>
                            <label for="nev">Név: *</label><br>
                            <input type="text" name="nev" id="nev" value="" title="Ez a név lesz látható a profilodban!">
                        </p>
                        <p>Profilkép:</p>
                    </div>
    
                    <div>
                        <p>
                            <label for="camera">Fényképezőgép típusa:</label><br>
                            <input type="text" name="cam" id="camera" value="" title="A fényképezőgéped típusa">
                        </p>
                        <p>
                            <label for="lens">Objektív típusa:</label><br>
                            <input type="text" name="lens" id="lens" value="" title="Az objektív típusa">
                        </p>
                        <p>
                            <label for="rolam">Bemutatkozás:</label><br>
                            <textarea name="rolam" id="rolam" cols="30" rows="8" placeholder="Írj egy pár sort magadról!"></textarea>
                        </p>
                        <p class="star">A *-al jelölt mezők kitöltése kötelező!</p>
                    </div>
                </div>
    
                <div id="reg-bottom">
    
                    <div id="profkep"><img id="profkep_image" src="<?php echo isset($foto) ? "users/tmp/".$foto : "users/avatar.png" ?>"></div>
                
                    <div id="reg-bottom-right">
                        <div class="hibak" id="reg-hibak">
                            <div></div>
                            <div id="reg-errors"></div>
                        </div>
    
                       <div class="gombok" id="reg-gombok">
                            <label class="gomb">Képfeltöltés
                               <input type="hidden" name="filename" id="filename" value="">
                               <input type="file" id="pkep" name="foto" onchange="preview_image(event)"><br>
                            </label>
                            <p><input class="gomb" type="submit" name="reset" id="reset" value="Mégsem">
                               <input class="gomb" type="submit" name="elkuld" id="elkuld" value="Elküld"></p>
                        </div>
                    </div>
                </div>
            </form>
    </fieldset> 
    
 <!-- Képfeltöltés űrlap------------------------------------------------------>
    <fieldset class="upload  up-square" id="upform" name="upload">
        <legend>Fénykép feltöltése</legend>
        <form id="uploadform" method="post" enctype="multipart/form-data" action="">

            <div id="upform-top">
                <div id="foto-pre">
                    <div><img src="kepek/IMG_2216.JPG" alt=""></div>
                    <div>
                        <label class="gomb" for="foto" id="talloz">Képválasztás
                            <input type="file" name="foto" id="foto" onchange="this.form.submit()">
                        </label>
                        <label><input type="checkbox" name="public" id="publicw"> Nem publikus</label>
                    </div>
                </div>
                <div id="foto-data">
                    <div>
                        <p><label for="cim">Kép címe:</label>
                            <input type="text" name="cim" id="cim" title="A kép címe" value="">
                        </p>
                        <p><label for="kategoria">Kategória: *</label>
                            <select name="kategoria" id="kategoria" title="Kategória választás">
                                <OPTIon value="allat">Állat</OPTIon>
                                <OPTIon value="tajkep">Tájkép</OPTIon>
                                <OPTIon value="makro">Makró</OPTIon>
                                <OPTIon value="portre">Portré</OPTIon>
                            </select>
                        </p>
                    </div>

                    <div>
                        <label for="leiras">Leírás a képről:</label><br>
                        <textarea name="leiras" id="leiras" cols="33" rows="8" placeholder="Itt írhatsz a kép készítéséről"></textarea>
                    </div>
                    <!--Publikus jelölő helye--->
                    <label><input type="checkbox" name="public" id="public"> Nem publikus</label>
                </div>
            </div>

            <div id="upform-bottom">
                <div>
                    <div class="foto-exif" id="made-exif">
                        <div>
                            <p><label for="zar">Záridő (s):</label><br>
                                <input type="text" name="zar" id="zar" title="Zársebesség" value="">
                            </p>
                            <p><label for="blende">Rekesz:</label><br>
                                <input type="text" name="blende" id="blende" title="Rekeszérték" value="">
                            </p>
                        </div>
                        <div>
                            <p><label for="iso">ISO:</label><br>
                                <input type="number" name="iso" id="iso" title="ISO érték" value="">
                            </p>
                            <p><label for="fokusz">Fókusz (mm):</label><br>
                                <input type="number" name="fokusz" id="fokusz" title="Fókusztávolság" value="">
                            </p>
                        </div>
                    </div>

                    <div class="foto-exif" id="cam-exif">
                        <div>
                            <p><label for="cam">Kamera:</label><br>
                                <input type="text" name="cam" id="cam" title="A gép típusa" value="">
                            </p>
                            <p><label for="obi">Objektív:</label><br>
                                <input type="text" name="obi" id="obi" title="Az objektív típusa" value="">
                            </p>
                        </div>
                    </div>
                </div>
                <div class="gombok" id="up-gombok">
                    <div><label for="datum">Készült:</label><br>
                        <input type="date" name="datum" id="datum" title="A készítés dátuma" value="">
                    </div>
                    <div><input class="gomb" type="reset" name="cancel" id="cancel" value="Mégsem" onclick="openreg(this)">
                        <input class="gomb" type="submit" name="enter" id="enter" value="Elküld">
                    </div>
                </div>
            </div>
            <!--Hibalista
                <div class="hibak" id="up-hibak">
                    <ul>
                        <li>hiba 1</li>
                        <li>hiba 2</li>
                        <li>hiba 3</li>
                    </ul>
                </div>
            -->
        </form>
    </fieldset>

  
    <script src="script.js"></script>
    

</body>

</html>