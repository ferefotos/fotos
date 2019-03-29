<?php
/*****************************************************************
 * A képek feltöltéséhez és feldolgozásához szükséges függvények *
 *****************************************************************/

/* Kép vizsgálata, feltöltése **********************************************************************************/
function upload($path, $prefix){
    /* $path -elérési út, ahova a képet mentjük
       $prefix -a file neve (amivel mentjük), kiterjesztés nélkül */
    // Engedályezett MIME típusok
    $mime = array("image/jpeg", "image/pjpeg", "image/gif", "image/png");
    //Vizsgálat
    if ($_FILES['foto']['error'] > 1) {
        $result = array("error" => true, "hiba" => "Hiba történt a fájlfeltöltés során: " . $_FILES['foto']['error']);
    }
    if ($_FILES['foto']['size'] > 16048000 || $_FILES['foto']['error'] == 1) {
        $result = array("error" => true, "hiba" => "A feltölthető fájl maximális mérete 16MB lehet!");
    }
    if ($_FILES['foto']['error'] == 0 && !in_array($_FILES['foto']['type'], $mime)) {
        $result = array("error" => true, "hiba" => "Nem megfelelő képformátum! Feltölthető: .jpg .png .gif");
    }
    // Ha van hiba
    if (isset($result)) {
        return $result;
    } else {
      // Ha nincs hiba: Fájlnév elkészítése
        switch ($_FILES['foto']['type']) {
            case "image/png":
                $ext = ".png";
                break;
            case "image/gif":
                $ext = ".gif";
                break;
            default:
                $ext = ".jpg";
                break;
        }
    $filename = ekezettelen($prefix) . $ext;
    // Kép mozgatása a végleges helyére
    move_uploaded_file($_FILES['foto']['tmp_name'], $path . $filename);
    //Visszaadja hogy nem volt hiba, és a file nevét
    return $result = array("error" => false, "file" => $filename, "ext" => $ext);
    }
} 

/* A fájlnév tisztítása az ékezetes karakterektől (Ha a usernévben lenne, mivel a profilkép 
   a felhasználói névvel azonos nevet kap.)*/ 
function ekezettelen($szoveg) {
	$mit  = array("á", "é", "í", "ó", "ö", "ő", "ú", "ü", "ű", "Á", "É", "Í", "Ó", "Ö", "Ő", "Ú", "Ü", "Ű", "_", " ");
	$mire = array("a", "e", "i", "o", "o", "o", "u", "u", "u", "A", "E", "I", "O", "O", "O", "U", "U", "U", "-", "-");
	return str_replace($mit, $mire, $szoveg);
}

/* Kép átméretezése *************************************************************************************************/
function resize($path, $n_path, $n_height){
    //Eredeti kép méretének lekérdezése
    $size = getimagesize($path);
    $width = $size[0];
    $height = $size[1];

    // Ha kisebb képet töltenek fel, mint amire méretezni kellene
   if($height < $n_height)
      return;

    // Az új kép szélességének számítása a magasságból
    $n_width = round($n_height * $width / $height);      

    // új (üres) képfájl létrehozása az új képmérettel.
    $ujkep = imagecreatetruecolor($n_width, $n_height);

    // Az eredeti képből image létrehozása 
    switch ($_FILES['foto']['type']) {
        case "image/gif":
            $kep = imagecreatefromgif($path);
            break;
        case "image/jpeg":
            $kep = imagecreatefromjpeg($path);
            break;
        case "image/png":
            $kep = imagecreatefrompng($path);
            break;
    }
    // A paraméterek szerint méretezi, és az új képfájlba másolja
    imagecopyresampled($ujkep, $kep, 0, 0, 0, 0, $n_width, $n_height, $width, $height);

    // A létrehozott képfájlt a megadott helyre menti. jpeg és png esetében itt adható meg a kép minősége
    switch ($_FILES['foto']['type']) {
        case "image/gif":
            imagegif($ujkep, $n_path);
            break;
        case "image/jpeg":
            imagejpeg($ujkep, $n_path, 97);
            break;
        case "image/png":
            imagepng($ujkep, $n_path, 1);
            break;
    }
}

/* Profilkép méretre vágása ***************************************************************************/
function crop_img($path, $n_path, $n_height, $prew_height, $top_pos, $left_pos){
  /*Paraméterként kapott változók:
   * $path, $n_path - kép jelenlegi és új elérési útja
   * $n_height - új kép magassága, vágás és átméretezés után
   * $prew_height - Az előnézeti képen a csúszkával beállított képmagasság
   * $top_pos, $left_pos - Az előnézeti képen beállított pozíció, a képkeret felső és bal oldalán
   *   (ahol a képet vágni kell) */   
    $size = getimagesize($path);
    $width = $size[0];
    $height = $size[1];
//új (üres) képfájl létrehozása az új képmérettel. Mivel négyzetre vágjuk, a két érték azonos
$ujkep = imagecreatetruecolor($n_height, $n_height);
// Az eredeti képből image létrehozása 
$ext = $_FILES['foto']['type'];
switch ($ext) {
    case "image/gif":
        $kep = imagecreatefromgif($path);
        break;
    case "image/jpeg":
        $kep = imagecreatefromjpeg($path);
        break;
    case "image/png":
        $kep = imagecreatefrompng($path);
        break;
}
// Az előnézeti kép szélességének számítása, ami további számításhoz szükséges
$prew_width = $prew_height*$width/$height;

/* Mivel a vágás pozíciója az előnézeti kép méretéhez arányos -mert azon lett beállítva-, de a méretre
 * vágott új képet az eredeti képből hozzuk létre, arányosítani kell a vágás koordinátáit az előnézeti képből 
 * az eredeti képre. */
$cut_st_x = $left_pos*$width/$prew_width;
$cut_st_y = $top_pos*$height/$prew_height;
$cut_end_xy = $height*$n_height/$prew_height;
    
/* Az új image fájlba másolja a paraméterek szerint vágott és kicsinyített képet.
 * $ujkep - A fentebb létrehozott üres képfájlba másolja a méretezett képet
 * $kep - A régi képfájl
 * A 0-ás értékek a kezdő koordináták, ahova az új képfájlba beilleszti a méretezett képet
 * $cut_st_x, $cut_st_y Az eredeti képre vetített kezdő vágási koordináták
 * $n_height Az új kép mérete, erre kicsinyíti. Mivel négyzet, ezért azonos a szélesség és a magasság
 * $cut_end_xy - // Az alsó és jobb oldali vágási koordináták az eredeti képre vetítve. Mivel négyzetre vágjuk, azonos az x és az y
 * */
imagecopyresampled($ujkep, $kep, 0, 0, $cut_st_x, $cut_st_y, $n_height, $n_height, $cut_end_xy, $cut_end_xy);
   
// A létrehozott képfájlt a megadott helyre menti. jpeg és png esetében itt adható meg a kép minősége
    switch ($ext) {
        case "image/gif":
            imagegif($ujkep, $n_path);
            break;
        case "image/jpeg":
            imagejpeg($ujkep, $n_path, 95);
            break;
        case "image/png":
            imagepng($ujkep, $n_path, 1);
            break;
    }
}
/* Kép oldalarány számítása ******************************************************************************/
function getClass($path){
    $size = getimagesize($path);
    $width = $size[0];
    $height = $size[1];
    $per = $width/$height;
    if($per <= 0.8){
        $rate="portrait";
    }elseif($per > 0.8 && $per < 1.2){
        $rate="square";
    }elseif($per >= 1.2 && $per <= 1.7){
        $rate="landscape";
    }elseif($per > 1.7){
        $rate="wide";
    }
    return $rate;
}

/* Exif adatok lekérdezése és feldolgozása ***************************************************************/
function getExif($path){
    if ((isset($path)) and (file_exists($path))) {
        @$exif = exif_read_data($path);

        //Záridő
        /*A megfelelő formátumra alakítás: másodpercben 1s -nál kisebb érték esetén pl. 1/250 
          1s-felett pedig egy egész szám, pl. 5. */
        if (@array_key_exists('ExposureTime', $exif)) {
            $expTime = $exif['ExposureTime'];
            $cut = strtok($expTime, "/");
            $e1 = $cut;
            $cut = strtok("/");
            $e2 = $cut;
            if ($e1 == 10 && $e2 > $e1) {
                $expTime = ($e1 / 10) . "/" . ($e2 / 10);
            }
            if ($e1 >= $e2) {
                $expTime = $e1 / $e2;
            }
            $exifdata['zarido'] = $expTime;
        } else {
            $exifdata['zarido'] = "";
        }

        //Blende
        /* A megfelelő formátum pl.: f/1.2  f4  f5.6   f11 */
        if (@array_key_exists('ApertureFNumber', $exif['COMPUTED'])) {
            $aperture = $exif['COMPUTED']['ApertureFNumber'];
            $cut = strtok($aperture, "/");
            $cut = strtok("/");
            $a2 = $cut;
            if (substr($a2, strlen($a2) - 2) == ".0")
                $a2 = (int)$a2;
            if ($a2 > 1) {
                $aperture = "f/" . $a2;
            } else {
                $aperture = "";
            }
            $exifdata['blende'] = $aperture;
        } else {
            $exifdata['blende'] = "";
        }

        //ISO
        if (@array_key_exists('ISOSpeedRatings', $exif)) {
            $exifdata['iso'] = $exif['ISOSpeedRatings'];
        } else {
            $exifdata['iso'] = "";
        }

        // Fókusztávolság
        /* A megfelelő formátumra alakítás: A fókusztávolság egy egész szám mm-ben, pl. 80 mm*/
        if (@array_key_exists('FocalLength', $exif)) {
            $focus = $exif['FocalLength'];
            $cut = strtok($focus, "/");
            $f1 = $cut;
            $cut = strtok("/");
            $f2 = $cut;
            $focus = $f1 / $f2;
            $exifdata['focus'] = $focus;
        } else {
            $exifdata['focus'] = "";
        }

        // Kamera gyártója
        if (@array_key_exists('Make', $exif)) {
            $make = $exif['Make'];
        } else {
            $make = "";
        }
                       
        // Kamera model
        if (@array_key_exists('Model', $exif)) {
            $model = $exif['Model'];
        } else {
            $model = "";
        }

        // Kamera típusa: gyártó & model
        /* Két exif adatból áll. Típusonként eltérően a modelnél vagy szerepel a márka is, vagy csak a típius név.
         * A gyártónál teljes cégnév is lehet, pl. Nikon corporation. A 2 adatból úgy kell a típust generálni, 
         * hogy a márkanév (csak egyszer), és a gép típusa szerepeljen.*/ 
        if ($make != "" || $model != "") {
            $cams = array('Canon', 'Nikon', 'Olympus', 'Panasonic', 'Sony', 'Pentax', 'Fuji ', 'Samsung', 'Huawei', 'Leica');
            $N = count($cams);
            $i = 0;
            while ($i < $N && substr_count(strtolower($model), strtolower($cams[$i])) == 0) {
                $i++;
            }

            if ($i == $N) {
                $i = 0;
                while ($i < $N && substr_count(strtolower($make), strtolower($cams[$i])) != 1) {
                    $i++;
                }
                if ($i < $N) {
                    $make = $cams[$i] . " ";
                } else {
                    $make = strtok($make, " ") . " ";
                }
                $model = $make . $model;
            }
            $exifdata['kamera'] = $model;
        } else {
            $exifdata['kamera'] = "";
        }

        // Objektív
        if (@array_key_exists('UndefinedTag:0xA434', $exif)) {
            $exifdata['obi'] = $exif['UndefinedTag:0xA434'];
          } else {
            $exifdata['obi'] = "";
          }

        // Készítés dátuma
        /* A kinyert adatból csak a dátum kell, az idő nem. A dátumot : helyett kötőjellel kell elválasztani */
        if (@array_key_exists('DateTimeOriginal', $exif)) {
            $date = $exif['DateTimeOriginal'];
            $cut = strtok($date, " ");
            $exifdata['date'] = strtr($cut, ':', '-');
        } else {
            $exifdata['date'] = "";
        }

    }else{
        $exifdata=array('zarido' => '', 'blende' => '', 'focus' => '', 'iso' => '', 'kamera' => '', 'obi' => '', 'date' => '');
    }
    return $exifdata;
}
//Teszteléshez
/*$exifd = exifData("
/L/teszt/IMG_20180628_211207-1.jpg");
echo "Záridő: " . $exifd['zarido'] . "<br />";
echo "Blende: " . $exifd['blende'] . "<br />";
echo "Fókusztáv: " . $exifd['focus'] . "<br />";
echo "ISO: " . $exifd['iso'] . "<br />";
echo "Kamera: " . $exifd['kamera'] . "<br />";
echo "Objektív: " . $exifd['obi'] . "<br />";
echo "Készült: " . $exifd['date'] . "<br />";*/
?>