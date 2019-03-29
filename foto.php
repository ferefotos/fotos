<?php
require("config.php");
require("header.php");
require(ROOT_PATH. "/form/uploadform.php");
require(ROOT_PATH. "/form/regform.php");
require_once(ROOT_PATH. "/form/common.php");

/************************************************
 * Kép nézet, kép adatlap, hozzászólás a képhez *
 ************************************************/

$file=$_GET['file'];

/* Kép nézetben lehetőség van lapozni. Ha lapozunk, mindig abban a galériában kell lapozni, 
 * ahonnan a képet megnyitottuk. Pl. ha a tájkép galériából megnézünk egy képet, akkor tovább 
 * lapozva a  tájkép galéria következő képét kell látnunk. Ha bezárjuk a kép nézetet, akkor abba 
 * a galériába kell visszatérni, ahonnan megnyitottuk. A címsorban átadott 'list' paraméterből  
 * lehet tudni, hogy milyen galériából nyitottunk meg egy képet.*/

/* Lapozás ***************************************************************************************/

/* A lapozáshoz meg kell keresni az első és az utolsó fájlt, hogy ne lehessen túllapozni. 
    Egyúttal a kép bezárásához is elkészül az url hogy mely galériába kell visszalépni. */
switch($_GET['list']){
    case "katid": $sql = "SELECT max(file) AS max, min(file) AS min FROM foto 
                          WHERE katid={$_GET['katid']} AND (public=1 $term)"; 
                  $url = "gallery.php?katid={$_GET['katid']}";        
    break;
    case "userid": $sql = "SELECT max(file) AS max, min(file) AS min FROM foto 
                           WHERE artist='{$_GET['userid']}' AND (public=1 $term)";
                   $url = "gallery.php?userid={$_GET['userid']}";        
    break;
    case "kedvenc": $sql = "SELECT max(file) AS max, min(file) AS min FROM foto
                            JOIN kedvencek ON filename=file
                            WHERE jelolo='{$_SESSION['userid']}'";
                    $url = "gallery.php?kedvenc=";        
    break;
    case "toplist": $sql = "SELECT file FROM foto
                            JOIN kedvelesek ON kedvelesek.foto=foto.file
                            WHERE public=1
                            GROUP BY file
                            ORDER BY count(foto) DESC, file DESC LIMIT 60";
                    $url = "gallery.php?toplist=";        
    break;                        
    case "src": $sql = "SELECT max(file) AS max, min(file) AS min FROM foto
                        JOIN user ON userid=artist
                        JOIN kategoria ON kategoria.id=foto.katid
                        LEFT JOIN kommentek ON foto.file=kommentek.kep 
                        WHERE (nev LIKE '%{$_GET['search']}%'
                        OR cim LIKE '%{$_GET['search']}%'
                        OR kamera LIKE '%{$_GET['search']}%'
                        OR obi LIKE '%{$_GET['search']}%'
                        OR story LIKE '%{$_GET['search']}%'
                        OR kategoria LIKE '%{$_GET['search']}%'
                        OR komment LIKE '%{$_GET['search']}%')
                        AND (public=1 $term)";  
                $url = "gallery.php?search={$_GET['search']}";        
    break;
    default: $sql = "SELECT max(file) AS max, min(file) AS min FROM foto";
             $url = "index.php";   
}
$eredmeny = mysqli_query($dbconn, $sql);

/* A toplistánál nem file szerinti sorrendben vannak a képek, hanem kedvelés szerint. 
   Az egész TOP60-at le kell kérdezni, és egy tömbbe rakni. Így megvan a lista első és utolsó eleme.
   A léptetésnél a következő vagy előző fájlt is csak így lehet megtudni, ezért a léptetésnél már
   nem kell újra lekérdezni. */
if($_GET['list'] == "toplist"){
    $toplist=array();
    while($sor = mysqli_fetch_assoc($eredmeny)){
        array_push($toplist, $sor['file']);
    }
    $min = $toplist[count($toplist)-1];
    $max = $toplist[0];
}else{
    $sor = mysqli_fetch_assoc($eredmeny);
    $max = $sor['max'];
    $min = $sor['min'];
}

// Sql parancsok előre lapozásnál -a következő fájl lekérdezése a szűrés szerint
if (isset($_GET['next'])) {
    switch($_GET['list']){
        case "katid": $sql = "SELECT file FROM foto 
                              WHERE katid={$_GET['katid']} AND (public=1 $term) 
                              AND file<'$file'
                              ORDER BY file DESC LIMIT 1"; 
        break;
        case "userid": $sql = "SELECT file FROM foto 
                               WHERE artist='{$_GET['userid']}' AND (public=1 $term) 
                               AND file<'$file'
                               ORDER BY file DESC LIMIT 1";
        break;
        case "kedvenc": $sql = "SELECT file FROM foto
                                JOIN kedvencek ON filename=file
                                WHERE jelolo='{$_SESSION['userid']}'
                                AND file<'$file'
                                ORDER BY file DESC LIMIT 1";
        break;
        case "toplist" : $i= array_search($file, $toplist); // az aktuális fájl indexe
                         $file = $toplist[$i+1]; // a következő fájl
        break;
        case "src": $sql = "SELECT DISTINCT file FROM foto 
                            JOIN user ON userid=artist
                            JOIN kategoria ON kategoria.id=foto.katid
                            LEFT JOIN kommentek ON foto.file=kommentek.kep 
                            WHERE (nev LIKE '%{$_GET['search']}%'
                            OR cim LIKE '%{$_GET['search']}%'
                            OR kamera LIKE '%{$_GET['search']}%'
                            OR obi LIKE '%{$_GET['search']}%'
                            OR story LIKE '%{$_GET['search']}%'
                            OR kategoria LIKE '%{$_GET['search']}%'
                            OR komment LIKE '%{$_GET['search']}%')
                            AND file<'$file'
                            AND (public=1 $term)
                            ORDER BY file DESC LIMIT 1";
        break;
        default: $sql = "SELECT file FROM foto 
                         WHERE file<'$file'
                         AND (public=1 $term)
                         ORDER BY file DESC LIMIT 1";
    }
} 
// Sql parancsok vissza lapozásnál -az előző lekérdezése a szűrés szerint  
if(isset($_GET['prev'])){
    switch($_GET['list']){
        case "katid": $sql = "SELECT file FROM foto 
                              WHERE katid={$_GET['katid']} AND (public=1 $term) 
                              AND file>'$file'
                              ORDER BY file LIMIT 1"; 
        break;
        case "userid": $sql = "SELECT file FROM foto 
                               WHERE artist='{$_GET['userid']}' AND (public=1 $term) 
                               AND file>'$file'
                               ORDER BY file LIMIT 1";
        break;
        case "kedvenc": $sql = "SELECT file FROM foto
                                JOIN kedvencek ON filename=file
                                WHERE jelolo='{$_SESSION['userid']}'
                                AND file>'$file'
                                ORDER BY file LIMIT 1";
        break;
        case "toplist" : $i= array_search($file, $toplist);
                         $file = $toplist[$i-1];
        break;
        case "src": $sql = "SELECT DISTINCT file FROM foto 
                            JOIN user ON userid=artist
                            JOIN kategoria ON kategoria.id=foto.katid
                            LEFT JOIN kommentek ON foto.file=kommentek.kep 
                            WHERE (nev LIKE '%{$_GET['search']}%'
                            OR cim LIKE '%{$_GET['search']}%'
                            OR kamera LIKE '%{$_GET['search']}%'
                            OR obi LIKE '%{$_GET['search']}%'
                            OR story LIKE '%{$_GET['search']}%'
                            OR kategoria LIKE '%{$_GET['search']}%'
                            OR komment LIKE '%{$_GET['search']}%')
                            AND file>'$file' 
                            AND (public=1 $term)
                            ORDER BY file LIMIT 1";
        break;
        default: $sql = "SELECT file FROM foto 
                         WHERE file>'$file' 
                         AND (public=1 $term)
                         ORDER BY file LIMIT 1";
    }
}
/* A lekérdezés csak akkor kell, ha nem a TOP60-ban lapozunk, mivel ott már korábban
   megtörtént a lekérdezés */
if((isset($_GET['next']) || isset($_GET['prev'])) && $_GET['list'] != "toplist"){
    $eredmeny = mysqli_query($dbconn, $sql);
    $sor = mysqli_fetch_assoc($eredmeny);
    $file = $sor['file'];
}
/* lapozás rész vége ******************************************************************************************/

// Kép adatok lekérdezése:
$sql = "SELECT file, katid, cim, story, blende, zarido, iso, focus, 
                kamera, obi, date, class, userid, nev, pkep
                FROM foto JOIN user ON userid=artist
                WHERE file='$file'";
$eredmeny = mysqli_query($dbconn, $sql);
$sor = mysqli_fetch_assoc($eredmeny);

//Like és kedvenc lekérdezések (meg kell számolni, hányszor lájkolták a képet)
$db_like=db_like($file);
$db_kedvenc=db_kedvenc($file);
// változik az gomb ikon színe ha a bejelentkezett felhasználó jelölte a képet 
$like_img ="heart40c.png";
$kedvenc_img ="star40c.png";
if(isset($_SESSION['userid'])){
    if(kedvelt($file)) $like_img = "heart40cb.png";
    if(kedvenc($file)) $kedvenc_img="star40cy.png"; 
}
?>
<!-- A fotó, és az adatlapja --------------------------------------------------------------->
    <article class="<?php echo ($sor['class'] == 'portrait' || $sor['class'] == 'square')? "p-port" : "p-land" ?>">
        <div id="photoframe">
            <img src="photos/<?php echo $file; ?>" id="photo" alt="kép">
            <!-- A kép felett elhelyezkedő navigáció (léptetés előre, hátra, nagyítás, bezárás) -->
            <div class="navi">
                <div class="navi-top"> <!-- bezárás, azaz vissza a galériába -->
                    <a href="<?php echo $url; ?>"><img src="items/close.png" alt="bezár"></a>
                    <div><img id="enlarge" src="items/enlarge.png" alt="nagyít"></div>
                </div> 
                <div class="navi-center">
                    <?php if($file != $max): ?> <!-- léptetés előre -->
                        <a href="foto.php?prev=&file=<?php echo $file?><?php if(isset($_GET['katid'])) echo "&katid=" . $_GET['katid']?><?php if(isset($_GET['userid'])) echo "&userid=" . $_GET['userid']?><?php if(isset($_GET['search'])) echo "&search=" . $_GET['search']?>&list=<?php echo $_GET['list']?>">
                            <div class="prev">
                                <img src="items/prev.png" class="prevarrow" alt="előző">
                            </div>
                        </a>
                    <?php else: ?>
                        <div class="prev"></div>
                    <?php endif ?>
                    <?php if($file != $min): ?> <!-- léptetés vissza -->
                        <a href="foto.php?next=&file=<?php echo $file?><?php if(isset($_GET['katid'])) echo "&katid=" . $_GET['katid']?><?php if(isset($_GET['userid'])) echo "&userid=" . $_GET['userid']?><?php if(isset($_GET['search'])) echo "&search=" . $_GET['search']?>&list=<?php echo $_GET['list']?>">
                            <div class="next">
                                <img src="items/next.png" class="nextarrow" alt="következő">
                            </div>
                        </a>
                    <?php else: ?>
                        <div class="next"></div>
                    <?php endif ?>             
                </div>
                <div class="navi-bottom"></div>
            </div>
        </div>
        <div id="dataframe">
            <div id="photodata">
                <div id="data-header">
                    <div class="artist" id="photo_data_artist">
                        <a href="gallery.php?userid=<?php echo $sor['userid']; ?>"><img src="users/<?php echo $sor['pkep']; ?>" alt="profilkep"></a>
                        <a href="gallery.php?userid=<?php echo $sor['userid']; ?>"><p><?php echo $sor['nev']; ?></p></a>
                    </div>
                    <h4><?php echo $sor['cim']; ?></h4>
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
                <div id="desc">
                    <img src="items/pen.png" alt="leiras">
                    <p><?php echo $sor['story']; ?></p>
                </div>
            </div> 
            <div id="dataframe_bottom">
                <form id="foto_like" method="post">
                    <input type="hidden" name="liked" value=<?php echo $file; ?>>
                    <div id="star-mark">
                        <button type="submit" name="kedvenc" class="like_button kedvenc">
                            <img src="items/<?php echo $kedvenc_img; ?>" id="kedvenc_img" alt="kedvenc" onmouseover="like_hover(this)" onmouseout="like_out(this)">
                            <span id="count_kedvenc"><?php echo $db_kedvenc; ?></span>
                        </button>
                        <span class="like_alert" id="kedvenc_alert">Jelentkezz be!</span>
                    </div>
                    <div id="like-mark">
                        <button type="submit" name="like" class="like_button like">
                            <img src="items/<?php echo $like_img; ?>" id="like_img" alt="like" onmouseover="like_hover(this)" onmouseout="like_out(this)">
                            <span id="count_like"><?php echo $db_like; ?></span>
                        </button>
                        <span class="like_alert" id="like_alert">Jelentkezz be!</span>
                    </div>
                </form> 
                <!-- Képadatok módosításának menüje a saját képeinknél-->
                <?php if(isset($_SESSION['userid']) && $_SESSION['userid'] == $sor['userid']): ?>
                <div id="fotomod">
                    <img src="items/menu-dot.png" id="dotmenu" onclick="show_modmenu()" alt="módosit">
                    <div id="dotmenu-sub" onmouseleave="hide_modmenu()">
                        <a href="fotomod.php?file=<?php echo $file?>&katid=<?php echo $sor['katid']?><?php if(isset($_GET['userid'])) echo "&userid=" . $_GET['userid']?><?php if(isset($_GET['search'])) echo "&search=" . $_GET['search']?>&list=<?php echo $_GET['list']?>">Módosítás</a>
                        <p onclick="show_delmenu()">Törlés</p>
                    </div>
                    <form id="keptorles" method="post">
                            <input class="gomb" type="submit" name="delete" id="torol" value="Töröl">
                            <input class="gomb" type="submit" name="cancdel" value="Mégsem">
                    </form>
                </div>
                <?php endif ?>
            </div>
        </div>
    </article>

<?php
/* Kép törlése ***********************************************************************************************/
if(isset($_POST['delete'])){
    $sql="DELETE from foto WHERE file='$file'";
    if (mysqli_query($dbconn, $sql)) {
        @unlink("photos/thumbs/" . $file);
        @unlink("photos/" . $file);
        location($url);
    }else{
        echo "MySqli hiba (" . mysqli_errno($dbconn) . "): " . mysqli_error($dbconn) . "\n";
    }
}
?>
<!--Hozzászólás a képhez -------------------------------------------------------------------------------------->
        <div id="commentframe">
            <div id="komment_list">
                <!--Új hozzászólás megjelenítése JS által-->
                <div id=post_box_empty></div><br>
                <!----------------------------------------->
                <?php 
                // hozzászólások lekérdezése, megjelenítése
                $sql="SELECT userid, nev, pkep, komment, datum FROM user
                    JOIN kommentek ON userid=ertekelo
                    WHERE kep='$file' 
                    ORDER BY datum DESC";
                // hozzászólások kiírása    
                $eredmeny=mysqli_query($dbconn, $sql);
                mysqli_close($dbconn);
                while($row=mysqli_fetch_assoc($eredmeny)): ?>
                <div class="post_box">
                    <div class="post_pkep">
                        <a href="gallery.php?userid=<?php echo $row['userid']?>">
                        <img src="users/<?php echo $row['pkep']?>"></a>
                    </div>
                    <div class="post_right">
                        <div class="post_header">
                            <a href="gallery.php?userid=<?php echo $row['userid']?>"><?php echo $row['nev']?></a>
                            <span><?php echo $row['datum']?></span>
                        </div>
                        <p class=post><?php echo $row['komment']?></p>
                    </div>
                </div><br>
                <?php endwhile?>      
            </div>
            <details>
                <summary>Új hozzászólás</summary>
                <?php if(isset($_SESSION['userid'])): ?>
                <form id="komment_form" method="post">
                    <input type="hidden" name="kep" value="<?php echo $file ?>">
                    <textarea name="komment" id="komment" cols="30" rows="10" placeholder="Mondj véleményt a képről!"></textarea>
                    <input class="gomb" type="submit" name="hozzaszol" id="hozzaszol" value="Elküld">       
                </form>
                <?php else: ?>
                <p class="hibak">Jelentkezz be!</p>
                <?php endif ?>
            </details>    
        </div>

<!-- Nagykép nézet elsötétített háttérrel ----------------------------------------------------------->
<!-- 
  Az előtérben jelenik meg, display kapcsolásával, ugyanazokkal a léptetés gombokkal, amivel a
  kisebb nézetben is léptetünk. Viszont paraméter átadásnál jelezni kell, hogy teljes nézetben 
  léptetünk, és így tudunk a befoglaló div elemnek egy id-t adni (large), amelynek display:flex
  van megadva, hogy léptetésnél ne záródjon be. 
  Mikor bezárjuk a nagykép nézetet, az a kép lesz kisebb nézetben, ami előtte nagykép nézetben volt.                    
-->
        <div class="nagykep_div" id="<?php if(isset($_GET['next']) && $_GET['next'] == "large" || isset($_GET['prev']) && $_GET['prev'] == "large") echo "large" ?>">
            <img src="photos/<?php echo $file?>" id="nagykep">
            <div class="navi" id="navi">
                <div class="navi-top" id="close_div">
                    <div></div>    
                    <img src="items/close.png" id="close" alt="bezár">
                </div> 
                <div class="navi-center">
                <?php if($file != $max): ?> <!-- léptetés előre -->
                    <a href="foto.php?prev=large&file=<?php echo $file?><?php if(isset($_GET['katid'])) echo "&katid=" . $_GET['katid']?><?php if(isset($_GET['userid'])) echo "&userid=" . $_GET['userid']?><?php if(isset($_GET['search'])) echo "&search=" . $_GET['search']?>&list=<?php echo $_GET['list']?>">
                        <div class="prev">
                            <img src="items/prev.png" class="prevarrow" alt="előző">
                        </div>
                    </a>
                <?php else: ?>
                    <div class="prev"></div>
                <?php endif ?>
                <?php if($file != $min): ?> <!-- léptetés vissza -->
                    <a href="foto.php?next=large&file=<?php echo $file?><?php if(isset($_GET['katid'])) echo "&katid=" . $_GET['katid']?><?php if(isset($_GET['userid'])) echo "&userid=" . $_GET['userid']?><?php if(isset($_GET['search'])) echo "&search=" . $_GET['search']?>&list=<?php echo $_GET['list']?>">
                        <div class="next">
                            <img src="items/next.png" class="nextarrow" alt="következő">
                        </div>
                    </a>
                <?php else: ?>
                    <div class="next"></div>
                <?php endif ?>
                </div>
                <div class="navi-bottom"></div>
            </div>
        </div>    
    </main>
</div>
  <script src="script/foto.js" type="text/javascript"></script>
  <script src="script/ajaxform.js" type="text/javascript"></script>  
  <script src="script/script.js" type="text/javascript"></script>

</body>
</html>