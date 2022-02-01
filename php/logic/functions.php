<?php
require_once(__DIR__.'/../../php/logic/error_reporting.php');
require_once(__DIR__.'/../config.php');

function empty_to_null($value){
    return empty($value) ? NULL : $value;
}

function upload_image($folder_path,$file,$maxSize = 500000){
    $target_dir = $folder_path;
    $target_file = $target_dir.basename($_FILES[$file]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

    // Check if image file is a actual image or fake image
    if(isset($_POST["submit"])) {
        $check = getimagesize($_FILES[$file]["tmp_name"]);
        if($check !== false) {
            $uploadOk = 1;
        } else {
            throw new Exception("File is not an image.");
        }
    }

    // Check if file already exists
    if (file_exists($target_file)) {
        throw new Exception("Sorry, file already exists.");
    }

    // Check file size
    if ($_FILES[$file]["size"] > $maxSize) {
        throw new Exception("Sorry, your file is too large.");
    }

    // Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
        throw new Exception("Sorry, only JPG, JPEG, PNG files are allowed.");
    }

    // if everything is ok, try to upload file
    if (!move_uploaded_file($_FILES[$file]["tmp_name"], $target_file)) {
        throw new Exception("Sorry, there was an error uploading your file.");
    }
}



function deleteImage($path){
    if (file_exists($path)) {
        unlink($path);
    }
}

//restituisce matrice contenente tabelle risultato della query passata
function convertQuery($query_res): array {
    $arr = array();
    if ($query_res) {
        while ($row=$query_res->fetch_array(MYSQLI_ASSOC)) {
            $arr[] = $row;
        }
    }
    return $arr;
}

function timeToMinutes($time){
    
    $dt = new DateTime("1970-01-01 $time", new DateTimeZone('UTC'));
    $seconds = (int)$dt->getTimestamp();
    return number_format($seconds / 60);
}

function timeToSeconds($time){
    
    $dt = new DateTime("1970-01-01 $time", new DateTimeZone('UTC'));
    $seconds = (int)$dt->getTimestamp();
    return $seconds;
}

function minutesToTime($minutes){
    $seconds = $minutes * 60;
    return gmdate("H:i:s", $seconds);
}

function secondsToTime($seconds){
    return gmdate("H:i:s", $seconds);
}

function dateEurToUsa($dateEur){
    return date("Y-m-d", strtotime($dateEur));
}

function dateUsaToEur($dateUsa){
    return date("d-m-Y", strtotime($dateUsa));
}

//ordinamento array di oggetti
function sortArrayByKey(&$array,$key,$string = false,$asc = true){
    if ($string) {
        usort($array,function ($a, $b) use(&$key,&$asc) {
            if($asc)    return strcmp(strtolower($a->$key), strtolower($b->$key));
            else        return strcmp(strtolower($b->$key), strtolower($a->$key));
        });
    } else {
        usort($array,function ($a, $b) use(&$key,&$asc) {
            if($a->$key == $b->$key) {return 0; }
            if($asc) return ($a->$key < $b->$key) ? -1 : 1;
            else     return ($a->$key > $b->$key) ? -1 : 1;
        });
    }
}

function siglaToPaese($sigla){
    return $_SESSION['sigle_paesi'][strtoupper($sigla)] ?? "";
}

function stringToTime($string){
    $hours = substr($string, 0, strpos($string, 'h'));
    $minutes = substr($string, strpos($string, 'h')+1, strlen($string) - (strpos($string, 'h')+2));
    
    $myDateTime = DateTime::createFromFormat('H:i:s', $hours.':'.$minutes.':00');
    $newDateTime = $myDateTime->format('H:i:s');
    
    return $newDateTime;
}

function timeToString($time){
    $myDateTime = DateTime::createFromFormat('H:i:s', $time);
    $hours = (int)($myDateTime->format('H'));
    $minutes = (int)($myDateTime->format('i'));

    return $hours.'h'.$minutes.'m';
}

function isUserAdmin(){
    return isset($_SESSION["user"]) && strtolower($_SESSION["user"]["Permessi"]) == "admin";
}

function validate_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);

    return $data;
}

function isStageWebsite(){
    return $_SERVER["SERVER_NAME"] == "localhost";
}

function renameImage($image){
    $image = str_replace(' ', '_', $image);
    return substr_replace($image, "_".date("YmdHis"), strripos($image, '.'), 0);
}