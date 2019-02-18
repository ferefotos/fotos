<?php
/**************************************************************
 * Regisztrációs űrlap                                        *
 **************************************************************/
//Űrlap változók
$foto= "users/avatar.png";
$userid="";
$email="";
$nev="";
$rolam="";
$cam="";
$lens="";
$del="";
$txt_passw="Jelszó:*";
$oldpass="";
//Adat módosításnál a változók lekérdezése
if(isset($_SESSION['userid'])){
    $sql="SELECT * FROM user WHERE userid='{$_SESSION['userid']}'";
    if ($eredmeny = mysqli_query($dbconn, $sql)) {
        $sor = mysqli_fetch_assoc($eredmeny);
        $userid = $sor['userid'];
        $email = $sor['email'];
        $nev = $sor['nev'];
        $pkep = $sor['pkep'];
        $foto = "users/".$pkep;
        $rolam = $sor['rolam'];
        $cam = $sor['cam'];
        $lens = $sor['lens'];
        $del="<label id=\"del\"> Regisztráció törlése:<input type=\"checkbox\" name=\"delete\" id=\"delete\"></label>\n";
        $txt_passw="Új jelszó:";
        $oldpass="<p>\n
                    <label for=\"jelszo_regi\" id=\"oldpass\">Jelenlegi jelszó: *</label><br>\n
                    <input type=\"password\" name=\"jelszo_regi\" id=\"jelszo_regi\" maxlength=\"16\" value=\"\" placeholder=\"Módosításhoz, törléshez\">\n
                </p>\n";
    } else {
        $errors[] = "MySqli hiba (" . mysqli_errno($dbconn) . "): " . mysqli_error($dbconn) . "\n";
    }
}
//Regisztrációs form
echo "
<fieldset id=\"reg\" class=\"reg\" name=\"regisztracio\">\n
    <legend>Regisztráció</legend>\n
    <form id=\"regform\" enctype=\"multipart/form-data\" action=\"#\">\n   
            <div id=\"reg-top\">\n
                <div>\n
                    <p>\n
                        <label for=\"user\">Felhasználónév: *</label><br>\n
                        <input type=\"text\" name=\"userid\" id=\"user\" maxlength=\"16\" autofocus value=\"$userid\" placeholder=\"minimum 6 karakter\">\n
                    </p>\n
                    <p>\n
                        <label for=\"jelszo\">$txt_passw</label><br>\n
                        <input type=\"password\" name=\"jelszo\" id=\"jelszo\" maxlength=\"16\" value=\"\" placeholder=\"minimum 8 karakter\">\n
                    </p>\n
                    <p>\n
                        <label for=\"jelszo2\">Jelszó ismét: *</label><br>\n
                        <input type=\"password\" name=\"jelszo2\" id=\"jelszo2\" maxlength=\"16\" value=\"\" title=\"Jelszó megismétlése!\">\n
                    </p>\n
                    <p>\n
                        <label for=\"email\">E-mail cím: *</label><br>\n
                        <input type=\"email\" name=\"email\" id=\"email\" maxlength=\"64\" value=\"$email\" title=\"e-mail címed\">\n
                    </p>\n
                    <p>\n
                        <label for=\"nev\">Név: *</label><br>\n
                        <input type=\"text\" name=\"nev\" id=\"nev\" maxlength=\"64\" value=\"$nev\" title=\"Ez a név lesz látható a profilodban!\">\n
                    </p>\n
                    $oldpass
                </div>\n

                <div>\n
                    <p>\n
                        <label for=\"camera\">Fényképezőgép típusa:</label><br>\n
                        <input type=\"text\" name=\"cam\" id=\"camera\" maxlength=\"64\" value=\"$cam\" title=\"A fényképezőgéped típusa\">\n
                    </p>\n
                    <p>\n
                        <label for=\"lens\">Objektív típusa:</label><br>\n
                        <input type=\"text\" name=\"lens\" id=\"lens\" maxlength=\"64\" value=\"$lens\" title=\"Az objektív típusa\">\n
                    </p>\n
                    <p>\n
                        <label for=\"rolam\">Bemutatkozás:</label><br>\n
                        <textarea name=\"rolam\" id=\"rolam\" cols=\"30\" rows=\"8\" placeholder=\"Írj egy pár sort magadról!\">$rolam</textarea>\n
                    </p>\n
                    <p class=\"star\">A *-gal jelölt mezők kitöltése kötelező!</p>\n
                    $del
                </div>\n
            </div>\n
            <p id=\"profkep_title\">Profilkép:</p>\n
            <div id=\"reg-bottom\">\n
                <div id=\"profilkep\">\n
                    <div id=\"profkep\"><img id=\"profkep_image\" src=\"$foto\"></div>\n
                    <input type=\"range\" name=\"prew_height\" id=img_height min=130 max=300 step=1 value=130>\n
                    <input type=hidden name=top_pos id=top_pos value=0>\n
                    <input type=hidden name=left_pos id=left_pos value=0>\n
                </div>\n
                <div id=\"reg-bottom-right\">\n
                    <div class=\"hibak\" id=\"reg-hibak\">\n
                        <div></div>\n
                        <div id=\"reg-errors\"></div>\n
                    </div>\n
                    <div class=\"gombok\" id=\"reg-gombok\">\n
                        <label class=\"gomb\">Képfeltöltés\n
                            <input type=\"hidden\" name=\"filename\" id=\"filename\" value=\"\">\n
                            <input type=\"file\" id=\"pkep\" name=\"foto\" onchange=\"preview_image(event)\"><br>\n
                        </label>\n 
                        <p><input class=\"gomb\" type=\"submit\" name=\"reset\" id=\"reset\" value=\"Mégsem\">\n
                           <input class=\"gomb\" type=\"submit\" name=\"elkuld\" id=\"elkuld\" value=\"OK\"></p>\n
                    </div>\n
                </div>\n
            </div>\n
        </form>\n
</fieldset>\n";
?>