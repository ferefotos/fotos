<?php
require("config.php");
require("header.php");
require(ROOT_PATH. "/form/uploadform.php");
require(ROOT_PATH. "/form/regform.php");

/************************************************
 * Kép nézet, kép adatlap, hozzászólás a képhez *
 ************************************************/

$file=$_GET['file'];

/* Lapozás *******************************************************************************************/

/* A bejelentkezett felhasználónak a nem publikus saját képeit is meg kell jeleníteni
 * a lapozás miatt -a galériához hasonlóan- itt is szükséges ez a feltétel */
if (isset($_SESSION['userid'])) {
    $term = "OR artist='{$_SESSION['userid']}' AND public=0";
} else {$term = "";}

/* Ha lapozunk, mindig abban a galériában kell lapozni, ahonnan a képet megnyitottuk.
 * Ha pl. a tájképekből, akkor a kép nézetben a tájképek között kell tudni lapozni 
 * Ha pedig bezárjuk a kép nézetet, akkor abba a galériába kell visszakerülni, ahonnan megnyitottuk.*/

/* A lapozáshoz meg kell keresni az első és az utolsó fájlt, hogy ne lehessen túllapozni. 
 *A címsorban átadott 'list' paraméterből lehet tudni, hogy milyen galériából nyitottuk meg a képet.*/
switch($_GET['list']){
    case "katid": $sql = "SELECT max(file) AS max, min(file) AS min FROM foto 
                          WHERE katid={$_GET['katid']} AND (public=1 $term)"; 
    break;
    case "userid": $sql = "SELECT max(file) AS max, min(file) AS min FROM foto 
                           WHERE artist='{$_GET['userid']}' AND (public=1 $term)";
    break;
    case "kedvenc": $sql = "SELECT max(file) AS max, min(file) AS min FROM foto
                            JOIN kedvencek ON filename=file
                            WHERE jelolo='{$_SESSION['userid']}'";
    break;
    case "toplist": $sql = "SELECT file FROM foto
                            JOIN kedvelesek ON kedvelesek.foto=foto.file
                            WHERE public=1
                            GROUP BY file
                            ORDER BY count(foto) DESC, file DESC LIMIT 60";
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
    break;
    default: $sql = "SELECT max(file) AS max, min(file) AS min FROM foto";
}
$eredmeny = mysqli_query($dbconn, $sql);

/* A toplistánál nem file szerinti sorrendben vannak a képek, hanem kedvelés szerint. 
   Az egész TOP60-at le kell kérdezni, és egy tömbbe rakni. Így megvan a lista első és utolsó eleme.
   A léptetésnél a következő vagy előző fájlt is csak így lehet megtudni, ezért a léptetésnél
   nem kell ismét lekérdezni. */
if($_GET['list'] == "toplist"){
    $rekordok=array();
    while($sor = mysqli_fetch_assoc($eredmeny)){
        array_push($rekordok, $sor['file']);
    }
    $min = $rekordok[count($rekordok)-1];
    $max = $rekordok[0];
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
        case "toplist" : $i= array_search($file, $rekordok);
                         $file = $rekordok[$i+1];
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
// Sql parancsok vissza lapozásnál -az előző lekérdezése a szűrés szerint  
} elseif(isset($_GET['prev'])){
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
        case "toplist" : $i= array_search($file, $rekordok);
                         $file = $rekordok[$i-1];
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
/* A lekérdezés csak akkor kell, ha nem a TOP60-ban lépkedünk, mivel ott már korábban
   megtörtént a lekérdezés az első és az utolsó fájl meghatározásához. */
if((isset($_GET['next']) || isset($_GET['prev'])) && $_GET['list'] != "toplist"){
    $eredmeny = mysqli_query($dbconn, $sql);
    $sor = mysqli_fetch_assoc($eredmeny);
    $file = $sor['file'];
}
/* lapozás rész vége **********************************************************************************************/

// Miután meg lett határozva melyik fájlt kell betölteni, le kell kérdezni az adatait:
$sql = "SELECT file, katid, cim, story, blende, zarido, iso, focus, 
                kamera, obi, date, class, userid, nev, pkep
                FROM foto JOIN user ON userid=artist
                WHERE file='$file'";
$eredmeny = mysqli_query($dbconn, $sql);
$sor = mysqli_fetch_assoc($eredmeny);

/* A kód egyes részei változnak, feltételektől függenek. Ezeket előre létre kell hozni, majd a kódba illeszteni. */    

// A formázáshoz az osztály meghatározása (álló vagy fekvő kép, más a formázás)
if ($sor['class'] == 'portrait' || $sor['class'] == 'square') {
    $cls = "p-port";
} else {
    $cls = "p-land";
}
// Az képadatok módosításához a menü csak azoknál a képeknél jelenik meg, amely a bejelentkezett felhasználóé
if(isset($_SESSION['userid']) && $_SESSION['userid'] == $sor['userid']){
    $modmenu = "
    <div id=\"fotomod\">\n
    <img src=\"items/menu-dot.png\" id=\"dotmenu\" onclick=\"show_modmenu()\" alt=\"modosit\">\n
    <div id=\"dotmenu-sub\" onmouseleave=\"hide_modmenu()\">\n
        <a href=fotomod.php?file=$file&katid={$sor['katid']}&userid={$_GET['userid']}&search={$_GET['search']}&list={$_GET['list']}>Módosítás</a>\n
        <p onclick=\"show_delmenu()\" >Törlés</p>\n
    </div>\n
    <form id=\"keptorles\" method=\"post\">\n
            <input class=\"gomb\" type=\"submit\" name=\"delete\" id=\"torol\" value=\"Töröl\">\n
            <input class=\"gomb\" type=\"submit\" name=\"cancdel\" value=\"Mégsem\">\n
    </form>\n
</div>\n";
}else{$modmenu = "";}
    
//Az előre és a hátra léptető nyilak melyek a kódba lesznek illesztve
/*Ha a végére ér, akkor nincs nyíl, és nem lehet túlléptetni*/
if ($file== $min) {
    $next_btn = "<div class=\"next\"></div>\n";
} else {
    $next_btn = "<a href=\"foto.php?next=&file=$file&katid={$sor['katid']}&userid={$_GET['userid']}&search={$_GET['search']}&list={$_GET['list']}\">\n
                <div class=\"next\"><img src=\"items/next.png\" class=\"nextarrow\" alt=\"kovetkezo\"></div></a>\n";
}
if ($file == $max) {
    $prev_btn = "<div class=\"prev\"></div>\n";
} else {
    $prev_btn ="<a href=\"foto.php?prev=&file=$file&katid={$sor['katid']}&userid={$_GET['userid']}&search={$_GET['search']}&list={$_GET['list']}\">\n
                <div class=\"prev\"><img src=\"items/prev.png\" class=\"prevarrow\" alt=\"elozo\"></div></a>\n";
}

// A képnézet bezárása esetén abba a galériába kell visszajutni, ahonnan a kép meg lett nyitva
switch($_GET['list']){
    case "src": $closelink = "gallery.php?search={$_GET['search']}";
    break;
    case "userid": $closelink = "gallery.php?userid={$_GET['userid']}";
    break;
    case "katid": $closelink = "gallery.php?katid={$_GET['katid']}";
    break;
    case "kedvenc": $closelink = "gallery.php?kedvenc=";
    break;
    case "toplist": $closelink = "gallery.php?toplist="; 
    break;
    default: $closelink = "index.php";
}

// Like és kedvenc gombok képei változókban
$like_img_on="heart40cb.png";
$like_img_off="heart40c.png";
$kedvenc_img_on="star40cy.png";
$kedvenc_img_off="star40c.png";

//Like és kedvenc lekérdezések (meg kell számolni, hányszor lájkolták a képet)
include(ROOT_PATH. '/react/like_count.php');
?>
<!-- A fotó, és az adatlapja --------------------------------------------------------------->
<article class="<?php echo $cls; ?>">
    <div id="photoframe">
        <img src="photos/<?php echo $file; ?>" id="photo" alt="kep">
        <div class="navi">
            <div class="navi-top">
                <a href="<?php echo $closelink; ?>"><img src="items/close.png" alt="bezar"></a>
                <div><img id="enlarge" src="items/enlarge.png" alt="nagyit"></div>
            </div> 
            <div class="navi-center">
                <?php 
                echo $prev_btn;
                echo $next_btn; 
                ?>              
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
            <?php echo $modmenu; ?>
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
        echo "<script type='text/javascript'>document.location.href='{$closelink}';</script>";
        echo '<META HTTP-EQUIV="refresh" content="0;URL=' . $closelink . '">';
    }else{
        echo "MySqli hiba (" . mysqli_errno($dbconn) . "): " . mysqli_error($dbconn) . "\n";
    }
}
?>
<!--Hozzászólás a képhez ----------------------------------------------------------------------------------->
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
            //hozzászólások kiírása    
            $eredmeny=mysqli_query($dbconn, $sql);
            while($sor=mysqli_fetch_assoc($eredmeny)){ 
                echo "<div class=\"post_box\">\n
                            <div class=\"post_pkep\">\n
                                <a href=\"gallery.php?userid={$sor['userid']}\">\n
                                <img src=\"users/{$sor['pkep']}\"></a>\n
                            </div>\n
                            <div class=\"post_right\">\n
                                <div class=\"post_header\">\n
                                <a href=\"gallery.php?userid={$sor['userid']}\">{$sor['nev']}</a>\n
                                <span>{$sor['datum']}</span>\n
                                </div>\n
                                <p class=post>{$sor['komment']}</p>\n
                            </div>\n
                      </div><br>\n";
            }
            ?>
                </div>
                <details>
                    <summary>Új hozzászólás</summary>
                    <?php 
                    // új hozzászólás űrlap
                    if(isset($_SESSION['userid'])){
                        echo "
                        <form id=\"komment_form\" method=\"post\">\n
                            <input type=\"hidden\" name=\"kep\" value=$file>\n
                            <textarea name=\"komment\" id=\"komment\" cols=\"30\" rows=\"10\" placeholder=\"Mondj véleményt a képről!\"></textarea>\n
                            <input class=\"gomb\" type=\"submit\" name=\"hozzaszol\" id=\"hozzaszol\" value=\"Elküld\">\n       
                        </form>\n";
                    }else{
                        echo "<p class=\"hibak\">Jelentkezz be!</p>";
                    }    
                    mysqli_close($dbconn);
                    ?>
                </details>    
            </div>
        </main>
    </div>
<!-- Nagykép nézet elsötétített háttérrel -->
        <?php 
/* Az előtérben jelenik meg, display kapcsolásával, ugyan azokkal a léptetés gombokkal, amivel a
 * kisebb nézetben is léptetünk. Viszont paraméter átadásnál jelezni kell, hogy teljes nézetben 
 * léptetünk, és így tudunk a befoglaló div elemnek egy id-t adni (big), amelynek display:flex
 * van megadva, így léptetésnél nem záródik vissza. 
 *  Mikor bezárjuk a nagykép nézetet, mögötte az a kép lesz, ami előtte nagykép nézetben volt*/

    //A léptetés gomboknál a linkhez beszúrjuk a next=big illetve prev=big paramétereket
    if ($file != $max){
        $prev_btn=substr_replace($prev_btn,"big",23,0);
    }
    if ($file != $min){
        $next_btn=substr_replace($next_btn,"big",23,0);
    }
    //Beállítjuk az id-t a képet befoglaló div-hez    
    $big="";
    if(isset($_GET['next']) && $_GET['next']=="big" || isset($_GET['prev']) && $_GET['prev']=="big"){
        $big="id=big";
    }
    // Kép teljes nézetben    
       echo "<div class=\"nagykep_div\" $big>\n
                <img src=\"photos/$file\" id=\"nagykep\">\n
                    <div class=\"navi\" id=\"navi\">\n
                        <div class=\"navi-top\" id=\"close_div\">\n
                            <div></div>\n    
                            <img src=\"items/close.png\" id=\"close\" alt=\"bezar\">\n
                        </div>\n 
                        <div class=\"navi-center\">\n
                            $prev_btn
                            $next_btn
                        </div>\n
                        <div class=\"navi-bottom\"></div>\n
                    </div>\n
             </div>\n";
        ?>
  <script src="script/foto.js"></script>
  <script src="script/ajaxform.js"></script>  
  <script src="script/script.js"></script>

</body>
</html>