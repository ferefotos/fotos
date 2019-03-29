<?php
require('file.php');
require("common.php");
/*********************************************************
 *           Képfeltöltő űrlap létrehozása               *
 *********************************************************/

if (isset($_FILES['foto'])){
    /* Átmenetileg a userid lesz a fájlnév, így könnyen törölhető, ha másik képet választanak. 
       A feldolgozás során egyedi nevet kap.*/
    $result = upload("photos/", $_SESSION['userid']); 
    if (!$result['error']) {
        //A fájl név a form rejtett mezőjébe kell
        $photo = $result['file'];
        //Kép oldalarány számítása
        $class = getClass("photos/" . $photo);
        //exif adatok kinyerése a képfájlból
        $exifdatas = getExif("photos/" . $photo);
        //Bélyegkép készítése a galériába
        resize("photos/" . $photo, "photos/thumbs/" . $photo, 300);
        //A kép méretezése
        resize("photos/" . $photo, "photos/" . $photo, 900);
        //Kategóriák a select-hez
        $kategoriak = kategoriak();
    } else {
        $_SESSION['hiba'] = $result['hiba'];
    }
}
?>

<?php if(isset($_FILES['foto']) && !$result['error']): ?>
<!-- Ha sikerült a képet hiba nélkül feltölteni -->
<div class="form_background"></div>
            <fieldset class="upload <?php echo "up-" . $class?>" id="upform" name="upload">
            <form id="uploadform" method="post" enctype="multipart/form-data">
                <div id="upform-top">
                    <div id="foto-pre">
                        <div><img src="photos/thumbs/<?php echo $photo?>" alt="foto"></div>
                        <div>
                            <label class="gomb" for="foto" id="talloz">Képválasztás
                                <input type="file" name="foto" id="foto" form="foto_up_form" onchange="this.form.submit()">
                            </label>
                            <input type="hidden" name="file" id="file" value="<?php echo $photo?>">
                            <?php if ($class == "landscape" || $class == "wide"): ?>
                            <label><input type="checkbox" name="public" id="public"> Nem publikus</label>
                            <?php endif ?>
                        </div>
                    </div>
                    <div id="foto-data">
                        <div>
                            <p><label for="cim">Kép címe:</label>
                                <input type="text" name="cim" id="cim" title="A kép címe" maxlength="32" value="" autofocus></p>
                            <p><label for="kategoria">Kategória: *</label>
                                <select name="kategoria" id="kategoria" title="Kategória választás">
                                    <option value=""></option>
                                    <?php while ($kategoria = mysqli_fetch_assoc($kategoriak)): ?>    
                                    <option value="<?php echo $kategoria['id']?>"><?php echo $kategoria['kategoria']?></option>
                                    <?php endwhile ?>
                                </select></p>
                            <div class="hibak" id="up-errors"></div>    
                        </div>
                        <div>
                            <label for="leiras">Leírás a képről:</label><br>
                                <textarea name="leiras" id="leiras" cols="24" rows="8" maxlength="500" placeholder="Itt írhatsz a kép készítéséről. Maximum 500 karakter!"></textarea>
                        </div>
                        <?php if ($class == "portrait" || $class == "square"): ?>
                        <label><input type="checkbox" name="public" id="public"> Nem publikus</label>
                        <?php endif ?>
                    </div>
                </div>
                <div id="upform-bottom">
                    <div>
                        <div class="foto-exif" id="made-exif">
                            <div>
                                <p><label for="zar">Záridő (s):</label><br>
                                    <input type="text" name="zar" id="zar" title="Zársebesség" maxlength="10" value="<?php echo $exifdatas['zarido']?>"></p>
                                <p><label for="blende">Rekesz:</label><br>
                                    <input type="text" name="blende" id="blende" title="Rekeszérték" maxlength="10" value="<?php echo $exifdatas['blende']?>"></p>
                            </div>
                            <div>
                                <p><label for="iso">ISO:</label><br>
                                    <input type="number" name="iso" id="iso" title="ISO érték" min="25" max="9999995" step="5" value="<?php echo $exifdatas['iso']?>"></p>
                                <p><label for="fokusz">Fókusz (mm):</label><br>
                                    <input type="number" name="fokusz" id="fokusz" title="Fókusztávolság" max="5000" value="<?php echo $exifdatas['focus']?>"></p>
                            </div>
                        </div>
                        <div class="foto-exif" id="cam-exif">
                                <p><label for="cam">Kamera:</label><br>
                                    <input type="text" name="cam" id="cam" title="A gép típusa:" maxlength="64" value="<?php echo $exifdatas['kamera']?>"></p>
                                <p><label for="obi">Objektív:</label><br>
                                    <input type="text" name="obi" id="obi" title="Az objektív típusa:" maxlength="64" value="<?php echo $exifdatas['obi']?>"></p>
                        </div>
                    </div>
                    <div class="gombok" id="up-gombok">
                         <div>
                            <label for="datum">Készült:</label><br>
                                <input type="date" name="datum" id="datum" title="A készítés dátuma" value="<?php echo $exifdatas['date']?>"></p>
                         </div>
                    <div>
                <input class="gomb" type="submit" name="cancel" id="cancel" value="Mégsem">
                <input class="gomb" type="submit" name="feltolt" id="feltolt" value="Feltölt">
            </form></fieldset>
<?php endif ?>
<?php if(isset($result['hiba'])): ?>
<script type='text/javascript'>location.href=window.location.href;</script>
<?php endif ?>