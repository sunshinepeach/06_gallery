<?php
// Sandkasten zum Spielen

require_once '../config.php';
require_once '../includes/functions.php';

function getImageFileType($path) {
    $types = ['', 'gif', 'jpeg', 'png'];
   // return $types[getimagesize($path)][2];
    $type = getimagesize($path)[2];
    if ($type > 0 && $type < 4) {
        return $types[$type];
    }
    return false;    
}

function uploadFile($tmpName, $path, $dstName = false) {
    //$n = ($dstName) ? $dstName : getRandName();
  // move_uploaded_file($tmpName, $path . $dstName);
    $n = ($dstName) ? $dstName : getRandName().'.'.
            getImageFileType($tmpName);
    if (move_uploaded_file($tmpName, $path . $n)) {
        return $path . $n;
    }
    
}

if (isset($_FILES['uplImgs'])) {
    for ($i = 0; $i < count($_FILES['uplImgs']['tmp_name']); $i++) {
        $tmpName = $_FILES['uplImgs']['tmp_name'][$i];
        
       // uploadFile($tmpName, '../'. PATH_ORIGINALS, 'bild_'.$i.'.jpeg');     
       // uploadFile($tmpName, '../'. PATH_ORIGINALS, getRandName() . '.jpeg');     
         uploadFile($tmpName, '../'. PATH_ORIGINALS);     
        
    }   
   
}

// Bildname: 1343213456.6541354765


?>
<html>

    <form enctype="multipart/form-data">
        <input multiple type="file" name="uplImgs[]">
        <button>send</button>
    </form>
    
    
    
    
</html>
<!--
// zur Orientierung
if (isset($_FILES['uploadFiles'])) {
    $imageTypes = ['', '.gif', '.jpeg', '.png'];
    $amountImages = count($_FILES['uploadFiles']['name']);
    for ($i = 0; $i < $amountImages; $i++) {
        $src = $_FILES['uploadFiles']['tmp_name'][$i];
       // $dst = './uploads/'.$_FILES['uploadFiles']['name'][$i];
       // move_uploaded_file($src, $dst);
        
        $imgInfo = getimagesize($src);
        $imgType = $imgInfo[2]; // 1 (gif)  2 (jpeg)  3 (png)
        if ($imgType >= 1 && $imgType <= 3) {
           
            $folder = './uploads/';
            $filename = uniqid('634287', true); // php Funktion
            $filetype = $imageTypes[$imgType];

            $dst = $folder . $filename . $filetype;
            move_uploaded_file($src, $dst);
        }
    }
    // verhindert reload der Seite ->
    // dadruch wird das Formular nicht nochmal abgeschickt
    header("Location:files_multiple.php");
}

-->
