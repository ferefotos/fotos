<?php
/*******************************************************
 *                Regisztrációs űrlap                  *
 *******************************************************/

//Regisztráció és adatmódosítás

//Adatmódosításhoz az adatok lekérdezése
if(isset($_SESSION['userid'])){
    $sql="SELECT * FROM user WHERE userid='{$_SESSION['userid']}'";
    $eredmeny = mysqli_query($dbconn, $sql);
    $sor = mysqli_fetch_assoc($eredmeny);
}
?>

<fieldset id="reg" class="reg" name="regisztracio">
    <legend>Regisztráció</legend>
    <form id="regform" enctype="multipart/form-data" action="#">   
            <div id="reg-top">
                <div>
                    <p>
                        <label for="user">Felhasználónév: *</label><br>
                        <input type="text" name="userid" id="user" maxlength="16" value="<?php if(isset($sor['userid'])) echo $sor['userid'] ?>" autofocus placeholder="minimum 6 karakter" title="Felhasználónév">
                    </p>
                    <p>
                        <label for="jelszo"><?php echo isset($_SESSION['userid']) ? "Új jelszó:" : "Jelszó: *" ?></label><br>
                        <input type="password" name="jelszo" id="jelszo" maxlength="16" value="" placeholder="minimum 8 karakter" title="jelszó">
                    </p>
                    <p>
                        <label for="jelszo2">Jelszó ismét: *</label><br>
                        <input type="password" name="jelszo2" id="jelszo2" maxlength="16" value="" title="Jelszó megismétlése!">
                    </p>
                    <p>
                        <label for="email">E-mail cím: *</label><br>
                        <input type="email" name="email" id="email" maxlength="64" value="<?php if(isset($sor['email'])) echo $sor['email'] ?>" title="e-mail címed">
                    </p>
                    <p>
                        <label for="nev">Név: *</label><br>
                        <input type="text" name="nev" id="nev" maxlength="64" value="<?php if(isset($sor['nev'])) echo $sor['nev'] ?>" title="Ez a név lesz látható a profilodban!">
                    </p>
                    <?php if(isset($_SESSION['userid'])): ?>
                    <p>
                        <label for="jelszo_regi" id="oldpass">Jelenlegi jelszó: *</label><br>
                        <input type="password" name="jelszo_regi" id="jelszo_regi" maxlength="16" value="" placeholder="Módosításhoz, törléshez" title="jelenlegi jelszó">
                    </p>
                    <?php endif ?>
                </div>
                <div>
                    <p>
                        <label for="camera">Fényképezőgép típusa:</label><br>
                        <input type="text" name="cam" id="camera" maxlength="64" value="<?php if(isset($sor['cam'])) echo $sor['cam'] ?>" title="A fényképezőgéped típusa">
                    </p>
                    <p>
                        <label for="lens">Objektív típusa:</label><br>
                        <input type="text" name="lens" id="lens" maxlength="64" value="<?php if(isset($sor['lens'])) echo $sor['lens'] ?>" title="Az objektív típusa">
                    </p>
                    <p>
                        <label for="rolam">Bemutatkozás:</label><br>
                        <textarea name="rolam" id="rolam" cols="30" rows="8" placeholder="Írj egy pár sort magadról!"><?php if(isset($sor['rolam'])) echo $sor['rolam'] ?></textarea>
                    </p>
                    <p id="note">A *-gal jelölt mezők kitöltése kötelező!</p>
                    <?php if(isset($_SESSION['userid'])): ?>
                    <label id="del"> Regisztráció törlése:<input type="checkbox" name="delete" id="delete"></label>
                    <?php endif ?>
                </div>
            </div>
            <p id="profkep_title">Profilkép:</p>
            <div id="reg-bottom">
                <div id="profilkep">
                    <div id="profkep"><img id="profkep_image" src="users/<?php echo isset($sor['pkep']) ? $sor['pkep'] : "avatar.png" ?>"></div>
                    <!-- Profilkép méretre vágáshoz az adatok -->
                    <input type="range" name="prew_height" id="img_height" min=130 max=300 step=1 value=130>
                    <input type="hidden" name="top_pos" id="top_pos" value=0>
                    <input type="hidden" name="left_pos" id="left_pos" value=0>
                </div>
                <div id="reg-bottom-right">
                    <div class="hibak" id="reg-hibak">
                        <div></div>
                        <div id="reg-errors"></div>
                    </div>
                    <div class="gombok" id="reg-gombok">
                        <label class="gomb">Képfeltöltés
                            <input type="file" id="pkep" name="foto" onchange="preview_image(event)"><br>
                            <input type="hidden" name="pkep_file" id="pkep_file" value="">
                        </label> 
                        <p><input class="gomb" type="submit" name="reset" id="reset" value="Mégsem">
                           <input class="gomb" type="submit" name="elkuld" id="elkuld" value="OK"></p>
                    </div>
                </div>
            </div>
        </form>
</fieldset>
