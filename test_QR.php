<?php
include_once('phpqrcode/qrlib.php');
$path = 'images/';
$qrcode = $path.time().".png";
QRcode::png("localhost/classmanagement", $qrcode);
echo "<img src='".$qrcode."'>";
?>