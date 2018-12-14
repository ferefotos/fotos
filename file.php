<?php
function upload($path, $prefix){
     // Engedályezett MIME típusok
    $mime = array("image/jpeg", "image/pjpeg", "image/gif", "image/png");
     //Vizsgálat
    if ($_FILES['foto']['error'] > 0 && $_FILES['foto']['error'] !=1) {
        $result=array("error" => true, "hiba" => "Hiba történt a fájlfeltöltés során: ".$_FILES['foto']['error']);
    }
    if ($_FILES['foto']['size'] > 3048000 || $_FILES['foto']['error'] ==1) {
        $result=array("error" => true, "hiba" => "A feltölthető fájl maximális mérete 3MB lehet!");
    }
    if ($_FILES['foto']['error'] == 0 && !in_array($_FILES['foto']['type'], $mime)) {
        $result=array("error" => true, "hiba" => "Nem megfelelő képformátum! Feltölthető: .jpg .png .gif");
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
        $filename = $prefix. $ext;
        
        // Kép mozgatása a végleges helyére
        move_uploaded_file($_FILES['foto']['tmp_name'], "$path$filename");
        
        //Visszaadja hogy nem volt hiba, és a file nevét
        return $result=array("error" => false, "file" => $filename);            
    }
} 
//A bélyegkép elkészítése
function resize($path, $n_path, $n_height){
    $size = getimagesize($path);
    $width = $size[0];
    $height = $size[1];

    $n_width = round($n_height * $width / $height);

    $ujkep = imagecreatetruecolor($n_width, $n_height);
     $ext=$_FILES['foto']['type'];

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

    imagecopyresampled($ujkep, $kep, 0, 0, 0, 0, $n_width, $n_height, $width, $height);

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

?>