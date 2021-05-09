<?php

//get the image part from base64 format
$image_parts = explode(";base64,", $_POST["image"]);
$image_type_aux = explode("image/", $image_parts[0]);
$image_type = $image_type_aux[1];
$image_base64 = base64_decode($image_parts[1]);

//create image from the base64 code
$img = imagecreatefromstring($image_base64);

//check if the base64 was a valid image
if ($img) {

    $filePath = "../img/temp/" . uniqid() . ".jpeg"; //choose a path & name
    $imgjpeg = imagejpeg($img, $filePath); //save image as a jpeg to specified path
    $file_size = filesize($filePath); //get size of the new image

    //check if the size is under 5MB
    if ($file_size > 5242880) {
        unlink($filePath); //delete img from folder
    } else {
        //send message back to browser
        echo json_encode($filePath);
    }
   
}

