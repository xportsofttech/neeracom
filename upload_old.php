<?php

session_start();
if (isset($_FILES["file"]["type"])) {
    $validextensions = array("xls", "ods", "xml","xlsx");
    $temporary = explode(".", $_FILES["file"]["name"]);
    $file_extension = end($temporary);
    if ((in_array($file_extension, $validextensions)) && ($_FILES["file"]["size"] < 100000)) {
        if ($_FILES["file"]["error"] > 0) {
            echo "Return Code: " . $_FILES["file"]["error"] . "<br/><br/>";
        } else {
            $name = uniqid() . "." . $file_extension;
            $sourcePath = $_FILES['file']['tmp_name']; // Storing source path of the file in a variable
            $targetPath = "assets/upload/" . $name; // Target path where file is to be stored
            $res = move_uploaded_file($sourcePath, $targetPath); // Moving Uploaded file
            if ($res) {
                $_SESSION['file_name'] = $name;
            }
        }
    }
}

?>