<?php
require('file.php');

/*********************************************************
 *           Képfeltöltő űrlap létrehozása               *
 *********************************************************/

/* Az űrlap akkor kerül létrehozásra, ha van kiválasztva fájl $_FILES['foto']. */ 

if (isset($_FILES['foto']) && !isset($_POST['file'])) {
    /* Átmenetileg a userid lesz a fájlnév, így könnyen törölhető, ha mégsem tölti 
    fel a felhasználó vagy másik képet választ. A feldolgozás során egyedi nevet kap.*/
    $result = upload("photos/", $_SESSION['userid']);
    if (!$result['error']) {
        //A fájl név a form rejtett mezőjébe kell
        $photo = $result['file'];
        //Bélyegkép készítése a galériába
        resize("photos/" . $photo, "photos/thumbs/" . $photo, 300);
        //Kép oldalarány számítása
        $class = "up-" . ratio("photos/" . $photo);
        //exif adatok kinyerése a képfájlból
        $exifdatas = getExif("photos/" . $photo);
        //A kép méretezése
        resize("photos/" . $photo, "photos/" . $photo, 900);
        //Kategóriák lekérdezése az űrlap legördülő listájába
        $sql = "SELECT * FROM kategoria ORDER BY kategoria";
        $eredmeny = mysqli_query($dbconn, $sql);
        $kategoriak = "<option value=\"\"></option>\n";
        while ($sor = mysqli_fetch_assoc($eredmeny)) {
            $kategoriak .= "<option value=\"{$sor['id']}\">{$sor['kategoria']}</option>\n";
        }
        // Álló és fekvő képnél máshova kerül a checkbox
        if ($class == "up-landscape" || $class == "up-wide") {
            $checkbox1 = "<label><input type=\"checkbox\" name=\"public\" id=\"public\"> Nem publikus</label>\n";
        } else {
            $checkbox1 = "";
        }
        if ($class == "up-portrait" || $class == "up-square") {
            $checkbox2 = "<label><input type=\"checkbox\" name=\"public\" id=\"public\"> Nem publikus</label>\n";
        } else {
            $checkbox2 = "";
        }

        echo "<div class=\"form_background\"></div>\n
            <fieldset class=\"upload $class\" id=\"upform\" name=\"upload\">\n
            <form id=\"uploadform\" method=\"post\" enctype=\"multipart/form-data\">\n
                <div id=\"upform-top\">\n
                    <div id=\"foto-pre\">\n
                        <div><img src=\"photos/thumbs/$photo\" alt=\"foto\"></div>\n
                        <div>\n
                            <label class=\"gomb\" for=\"foto\" id=\"talloz\">Képválasztás\n
                                <input type=\"file\" name=\"foto\" id=\"foto\" form=\"foto_up_form\" onchange=\"this.form.submit()\">\n
                            </label>\n
                            <input type=\"hidden\" name=\"file\" id=\"file\" value=\"$photo\">\n
                            $checkbox1
                        </div>\n
                    </div>\n
                    <div id=\"foto-data\">\n
                        <div>\n
                            <p><label for=\"cim\">Kép címe:</label>\n
                                <input type=\"text\" name=\"cim\" id=\"cim\" title=\"A kép címe\" maxlength=\"32\" value=\"\" autofocus></p>\n
                            <p><label for=\"kategoria\">Kategória: *</label>\n
                                <select name=\"kategoria\" id=\"kategoria\" title=\"Kategória választás\">\n
                                    $kategoriak
                                </select></p>\n
                            <div class=\"hibak\" id=\"up-errors\"></div>\n    
                        </div>\n
                        <div>\n
                            <label for=\"leiras\">Leírás a képről:</label><br>\n
                                <textarea name=\"leiras\" id=\"leiras\" cols=\"24\" rows=\"8\" maxlength=\"500\" placeholder=\"Itt írhatsz a kép készítéséről. Maximum 500 karakter!\"></textarea>\n
                        </div>\n
                        $checkbox2
                    </div>\n
                </div>\n
                <div id=\"upform-bottom\">\n
                    <div>\n
                        <div class=\"foto-exif\" id=\"made-exif\">\n
                            <div>\n
                                <p><label for=\"zar\">Záridő (s):</label><br>\n
                                    <input type=\"text\" name=\"zar\" id=\"zar\" title=\"Zársebesség\" maxlength=\"10\" value=\"{$exifdatas['zarido']}\"></p>\n
                                <p><label for=\"blende\">Rekesz:</label><br>
                                    <input type=\"text\" name=\"blende\" id=\"blende\" title=\"Rekeszérték\" maxlength=\"10\" value=\"{$exifdatas['blende']}\"></p>\n
                            </div>\n
                            <div>\n
                                <p><label for=\"iso\">ISO:</label><br>\n
                                    <input type=\"number\" name=\"iso\" id=\"iso\" title=\"ISO érték\" min=\"25\" max=\"9999995\" step=\"5\" value=\"{$exifdatas['iso']}\"></p>\n
                                <p><label for=\"fokusz\">Fókusz (mm):</label><br>\n
                                    <input type=\"number\" name=\"fokusz\" id=\"fokusz\" title=\"Fókusztávolság\" max=\"5000\" value=\"{$exifdatas['focus']}\"></p>\n
                            </div>\n
                        </div>\n
                        <div class=\"foto-exif\" id=\"cam-exif\">\n
                                <p><label for=\"cam\">Kamera:</label><br>\n
                                    <input type=\"text\" name=\"cam\" id=\"cam\" title=\"A gép típusa:\" maxlength=\"64\" value=\"{$exifdatas['kamera']}\"></p>\n
                                <p><label for=\"obi\">Objektív:</label><br>\n
                                    <input type=\"text\" name=\"obi\" id=\"obi\" title=\"Az objektív típusa:\" maxlength=\"64\" value=\"{$exifdatas['obi']}\"></p>\n
                        </div>\n
                    </div>\n
                    <div class=\"gombok\" id=\"up-gombok\">\n
                         <div>\n
                            <label for=\"datum\">Készült:</label><br>\n
                                <input type=\"date\" name=\"datum\" id=\"datum\" title=\"A készítés dátuma\" value=\"{$exifdatas['date']}\"></p>\n
                         </div>\n
                    <div>\n
                <input class=\"gomb\" type=\"submit\" name=\"cancel\" id=\"cancel\" value=\"Mégsem\">\n
                <input class=\"gomb\" type=\"submit\" name=\"feltolt\" id=\"feltolt\" value=\"Feltölt\">\n
            </form></fieldset>\n";
    } else {
        $hiba = $result['hiba'];
    }
}
?>