<?php

/**
 * Dreisatzberechnung -
 * gibt den gesuchten Wert zurück
 * @param float $x1 Erster Wert (z. B. Breite)
 * @param float $y1 Zweiter Wert (z. B. Höhe)
 * @param float $x2 Dritter Wert (z. B. neue Breite)
 * @return float (z. B. neue Höge)
 */
function calcDimension($x1, $y1, $x2) {
   $y2 = $y1 * $x2 / $x1;
   // return [$x2, $y2];
   return $y2;
}

function getRandName($prefix = '') {
    //return uniqid($prefix, true);
    return str_replace('.', '_', uniqid($prefix, true));
}

function getGdImage($path) {
    $info = getimagesize($path);
    $img = false;
    switch ($info[2]) {
        case 1:
            $img = imagecreatefromgif($path); 
            break;
        case 2:
            $img = imagecreatefromjpeg($path);
            break;
        case 3:
            $img = imagecreatefrompng($path);
            break;
        default:
            $img = false;
            break;
    }
    return [$img, $info[0], $info[1], $info[2]];   
}

function createResample($srcImg, $srcWidth, $srcHeight, $dstWidth,
        $dstHeight, $filetype, $path, $filename) {
    
    $dstPath = false;
    $dstImg = imagecreatetruecolor($dstWidth, $dstHeight);
   imagecopyresampled($dstImg, $srcImg, 0, 0, 0, 0, $dstWidth, $dstHeight,
           $srcWidth, $srcHeight);
   
    // Varante 1: imagejpeg() oder imagepng()
    if ($filetype === 2) {
        $dstPath =  $path . $filename . '.jpeg';
        imagejpeg($dstImg, $dstPath); 
    } elseif ($filetype === 3) {
        $dstPath = $path . $filename . '.png';
        imagepng($dstImg, $dstPath); 
    } else {
        return false;
    }
    return $dstPath;
}

function getImageFileType($path) {
    $types = ['', 'gif', 'jpeg', 'png'];
    $type = getimagesize($path)[2];
    if ($type > 0 && $type < 4) {
        return $types[$type];
    }
    return false;
}

function uploadFile($tmpName, $path, $dstName = false) {
    $n = ($dstName) ? $dstName : getRandName() . '.' . getImageFileType($tmpName);
    if (move_uploaded_file($tmpName, $path . $n)) {
        return $path . $n;
    }
    return FALSE;
}

function uploadFiles($files, $path) {
    $uploaded = [];
    for ($i = 0; $i < count($_FILES['uplImgs']['tmp_name']); $i++) {
        $uploaded[] = uploadFile($files['tmp_name'][$i], $path);
    }
    return $uploaded;
}

function createThumbnails($files) {
   // $gdImg[0] Bilddaten 
   // $gdImg[1][0] Breite
   // $gdImg[1][1] Höhe
   // $gdImg[1][2] Typ
    
   for ($i = 0; $i < count($files); $i++) {
   $gdImg = getGdImage($files[$i]);
   $dstH = intval(calcDimension($gdImg[1], $gdImg[2], THUMB_WIDTH));
   $name = pathinfo($files[$i])['filename'].'_'.THUMB_WIDTH.'x'.$dstH;
   createResample($gdImg[0], $gdImg[1], $gdImg[2], THUMB_WIDTH, $dstH,
           IMAGETYPE_JPEG, PATH_THUMBNAILS, $name);        
   }
}