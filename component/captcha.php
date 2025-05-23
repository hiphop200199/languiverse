<?php

session_start();
$code = mt_rand(1000, 9999);
$_SESSION['code'] = $code;
$im = imagecreate(100, 40);
imagecolorallocate($im, 0, 0, 0);
$white = imagecolorallocate($im, 255, 255, 255);
imagettftext($im,20,0,20,28,$white,$_SERVER['DOCUMENT_ROOT'].'/font/Times New Roman.ttf',(string)$code);//用ttf字體寫字，x,y是left,top，字大小可以比imagestring大
header("Cache-Control: no-store,no-cache, must-revalidate");//防瀏覽器存回應到快取
header('Content-Type: image/png');
imagepng($im);