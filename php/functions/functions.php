<?php
function empty_to_null($value){
    return empty($value) ? NULL : $value;
}

function upload_image($folder_path,$file,$maxSize = 500000){
    global $_FILES;

    $target_dir = $folder_path;
    $target_file = $target_dir . basename($_FILES[$file]["name"]);
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
?>