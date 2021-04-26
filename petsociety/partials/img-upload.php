<?php

function uploadImg ($file, $imgfolder, $dots){

    //get properties of $file array as variables
    $fileName = $file['name'];
    $fileTmpName = $file['tmp_name'];
    $fileSize = $file['size'];
    $fileError = $file['error'];
    $fileType = $file['type'];

    //get file extention as lowercase letters
    $fileExt = explode('.', $fileName);
    $fileActualExt = strtolower(end($fileExt));

    //allowed file types and extentions
    $allowedExt = array('jpg', 'jpeg', 'png');
    $allowedTypes = array('image/jpeg', 'image/png');

    //check if the file extention and mime type match one of the allowed ones
    if (in_array($fileActualExt, $allowedExt) && in_array($fileType, $allowedTypes)) {

        //check for upload errors
        if ($fileError === 0) {

            //max size 5MB
            if ($fileSize < 5000000) {

                //create new name as a random string (to prevent overwriting data) + extention
                $fileNameNew = uniqid('', true) . "." . $fileActualExt; //based on current time

                //choose a file destination and move it from its temporary location
                if ($dots) {
                    $fileDestination = '../img/' . $imgfolder . '/' . $fileNameNew;
                } else {
                    $fileDestination = 'img/' . $imgfolder . '/' . $fileNameNew;
                }
                
                move_uploaded_file($fileTmpName, $fileDestination);

                return $imgfolder . '/' . $fileNameNew;
            } else {
                return "Error: Your image file is too big!";
            }
        } else {
            return "Error: There was an error uploading your image!";
        }
    } else {
        return "Error: You cannot upload files of this type!";
    }
}

?>