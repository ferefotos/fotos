<?php
require("config.php");
require("header.php");
require(ROOT_PATH. "/form/uploadform.php");
require(ROOT_PATH. "/form/regform.php");
require_once(ROOT_PATH. "/form/common.php");

/****************************
 *   Képadatok módosítása   *
 ****************************/

$file = $_GET['file'];
// Kategóriák lekérdezése a select-hez
$kategoriak = kategoriak();

// Képadatok lekérdezése
$sql = "SELECT file, cim, story, blende, zarido, iso, focus, 
                kamera, obi, date, class, public, userid, nev, pkep
                FROM foto JOIN user ON userid=artist
                WHERE file='$file'";
$eredmeny = mysqli_query($dbconn, $sql);
$sor = mysqli_fetch_assoc($eredmeny);

?>
<article class="<?php echo ($sor['class'] == 'portrait' || $sor['class'] == 'square')? "p-port" : "p-land" ?>">
    <div id="photoframe">
        <img src="photos/<?php echo $sor['file']; ?>" alt="kep">
        <div id="navi">
            <div id="navi-top">
                <div></div>
                <div></div>
            </div> 
            <div id="navi-center">
                <div id="next"></div>
                <div id="prev"></div>
            </div>
        </div>
    </div>
    <form id="dataframe" method="post">
        <div id="photodata">
            <div id="data-header">
                <div class="artist" id="photo_data_artist">
                    <a href="gallery.php?userid=<?php echo $sor['userid']; ?>"><img src="users/<?php echo $sor['pkep']; ?>" alt="profilkep"></a>
                    <a href="gallery.php?userid=<?php echo $sor['userid']; ?>"><p><?php echo $sor['nev']; ?></p></a>
                </div>
            </div>
            <div id="photodata_mod">
                <label for="cim"> Kép címe:
                <input type="text" name="cim" id="cim_mod" title="A kép címe" maxlength="32" value="<?php echo $sor['cim']; ?>"></label><br> 
                <label for="kategoria">Kategória:</label>
                <select name="kategoria" id="kategoria_mod" title="Kategória választás">
                <?php while ($kategoria = mysqli_fetch_assoc($kategoriak)): ?>    
                    <?php if ($kategoria['id'] == $_GET['katid']): ?>
                    <option selected value="<?php echo $kategoria['id'] ?>"><?php echo $kategoria['kategoria'] ?></option>    
                    <?php else: ?>
                    <option value="<?php echo $kategoria['id'] ?>"><?php echo $kategoria['kategoria'] ?></option>    
                    <?php endif ?>   
                <?php endwhile ?>
                </select></p> 
                <label><input type="checkbox" name="public" id="public_mod" value="" <?php if (!$sor['public']) echo "checked" ?>> Nem publikus</label>
            </div>
            <div id="exif">
                <div class="exif">
                    <div>
                        <img src="items/blende.png" alt="blende">
                        <p><?php echo $sor['blende']; ?></p>
                    </div>
                    <div>
                        <img src="items/time.png" alt="zarido">
                        <p><?php echo $sor['zarido']; ?> s</p>
                    </div>              
                </div>
                <div class="exif">
                    <div>
                        <img src="items/iso.png" alt="iso">
                        <p><?php echo $sor['iso']; ?></p>
                    </div>
                    <div>
                        <img src="items/zoom.png" alt="fokusz">
                        <p><?php echo $sor['focus']; ?> mm</p>
                    </div>
                </div>
                <div class="exif">
                    <div>
                        <img src="items/cam.png" alt="kamera">
                        <p><?php echo $sor['kamera']; ?></p>
                    </div>
                    <div>
                        <img src="items/lens.png" alt="objektiv">
                        <p><?php echo $sor['obi']; ?></p>
                    </div>
                </div>
            </div>
            <div id="calendar">
                <img src="items/calendar.png" alt="datum">
                <p><?php echo $sor['date']; ?></p>
            </div>    
            <div id="desc_mod">
                <label for="leiras">Leírás a képről: </label>
                <textarea name="leiras" id="leiras_mod" cols="38" rows="8" maxlength="500" placeholder="Itt írhatsz a kép készítéséről. Maximum 500 karakter!"><?php echo $sor['story']; ?></textarea>
            </div>
        </div>
        <div id="edit-buttons">
            <input class="gomb" type="submit" name="canc" id="canc" value="Mégsem">
            <input class="gomb" type="submit" name="ment" id="ment" value="Mentés">
        </div>
    </form>
</article>

<?php
/* url az oldal frissítéséhez. Adatmódosítás után visszairányít a foto.php-ra. 
    úgy kell paraméterezni, hogy azt a képet töltse be, amit módosítottunk.*/
    $url="foto.php?file={$sor['file']}"; 
    if(isset($_GET['katid'])) $url .= "&katid=" . $_GET['katid'];
    if(isset($_GET['userid'])) $url .= "&userid=" . $_GET['userid'];
    if(isset($_GET['search'])) $url .= "&search=" . $_GET['search'];
    $url .="&list=" . $_GET['list'];

//űrlap feldolgozása
if (isset($_POST['ment'])) {
    $cim = tisztit($_POST['cim']);
    $leiras = tisztit($_POST['leiras']);
    $kategoria = $_POST['kategoria'];
    if (isset($_POST['public'])) {
        $public = 0;
    } else {
        $public = 1;
    }
    $sql = "UPDATE foto SET katid=$kategoria, cim='$cim', 
         story='$leiras', public=$public 
          WHERE file='{$_GET['file']}'";      
    if (mysqli_query($dbconn, $sql)) {
        location($url);
    } else {
        echo "MySqli hiba (" . mysqli_errno($dbconn) . "): " . mysqli_error($dbconn) . "\n";
    }   
}
//módosítás visszavonása
if (isset($_POST['canc'])) {
    location($url);
}

mysqli_close($dbconn);
?>
        </main>
    </div>
  <script src="script/ajaxform.js" type="text/javascript"></script>  
  <script src="script/script.js" type="text/javascript"></script>
</body>
</html>