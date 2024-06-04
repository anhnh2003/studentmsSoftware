<?php
if (isset($_POST['imgData'])) {
    $imgData = $_POST['imgData'];
    // Remove the header of the data URL
    $imgData = str_replace('data:image/png;base64,', '', $imgData);
    $imgData = str_replace(' ', '+', $imgData);
    // Decode the base64 data
    $imgData = base64_decode($imgData);
    // Write the data to a file in the student/temp directory
    $file = 'temp/photo.png';
    file_put_contents($file, $imgData);
    echo "Image saved successfully";
}
?>