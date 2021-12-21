<?php
//require "php/config.php";

$examname = $_POST['exam'];
$sid = $_POST['sid'];



// connect to the database
$conn = mysqli_connect('localhost', 'root', '', 'fms');

// Uploads files
if (isset($_POST['save'])) { // if save button on the form is clicked
    // name of the uploaded file
    $filename = $_FILES['attachment']['name'];

    // destination of the file on the server
    $destination = 'uploads/' . $filename;

    // get the file extension
    $extension = pathinfo($filename, PATHINFO_EXTENSION);

    // the physical file on a temporary uploads directory on the server
    $file = $_FILES['attachment']['tmp_name'];
    $size = $_FILES['attachment']['size'];
    echo "kay";

    if (!in_array($extension, ['zip', 'pdf', 'docx'])) {
        echo "You file extension must be .zip, .pdf or .docx";
    } elseif ($_FILES['attachment']['size'] > 10000000) { // file shouldn't be larger than 1Megabyte
        echo "File too large!";
    } else {
        // move the uploaded (temporary) file to the specified destination
        if (move_uploaded_file($file, $destination)) {
            $sql = "INSERT INTO uploads (sid,examname,filename, size, spath) VALUES ('$sid','$examname','$filename', $size, '$destination')";
            if (mysqli_query($conn, $sql)) {
                header('Location: st_profile_uploads.php');
            }
        } else {
            echo "Failed to upload file.";
        }
    }
}