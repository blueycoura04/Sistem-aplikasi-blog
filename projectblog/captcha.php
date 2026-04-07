<?php
session_start();

$chars = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghjkmnpqrstuvwxyz23456789';
$captcha = substr(str_shuffle($chars), 0, 4);
$_SESSION['captcha'] = $captcha;

// Buat image
header('Content-type: image/png');
$img = imagecreate(120,40);
$bg = imagecolorallocate($img,255,255,255); // background putih
$textcolor = imagecolorallocate($img,0,0,0); // teks hitam

// Tulis captcha
imagestring($img,5,30,10,$captcha,$textcolor);

imagepng($img);
imagedestroy($img);
?>