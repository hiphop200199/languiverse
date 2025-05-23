<?php
require_once  $_SERVER['DOCUMENT_ROOT'].'/config/app.php';
$url = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . '://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$path = parse_url($url, PHP_URL_PATH);
$path_array = explode('/', $path);
$css  = '/';

switch (count($path_array)) {
  case 2:
    $css .= 'index';
    break;
  default:
  foreach($path_array as $k => $v){
    $str = $v . '/';
    if(empty($v)){
        $str = '';
    }
    if($k == count($path_array)-1){
        $str = str_replace('.php','',$v);
    }
    $css .=  $str;
  }
  break;
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="apple-mobile-web-app-capable" content="yes" />
  <meta name="mobile-web-app-capable" content="yes" />
  <link rel="apple-touch-icon" href="<?php echo ROOT . '/icon/ice-72x72.png'; ?>" />
  <link rel="apple-touch-icon" href="<?php echo ROOT . '/icon/ice-96x96.png'; ?>" />
  <link rel="apple-touch-icon" href="<?php echo ROOT . '/icon/ice-128x128.png'; ?>" />
  <link rel="apple-touch-icon" href="<?php echo ROOT . '/icon/ice-144x144.png'; ?>" />
  <link rel="apple-touch-icon" href="<?php echo ROOT . '/icon/ice-152x152.png' ;?>" />
  <link rel="apple-touch-icon" href="<?php echo ROOT . '/icon/ice-192x192.png'; ?>" />
  <link rel="apple-touch-icon" href="<?php echo ROOT . '/icon/ice-384x384.png' ;?>" />
  <link rel="apple-touch-icon" href="<?php echo ROOT . '/icon/ice-512x512.png'; ?>" />
  <meta name="apple-mobile-web-app-status-bar" content="#000B58" />
  <meta name="theme-color" content="#000B58" />
  <link rel="shortcut icon" href="<?php echo ROOT . '/favicon.ico' ;?>" type="image/x-icon" />
  <link rel="stylesheet" href="<?php echo ROOT . '/css' . $css; ?>.css" />
  <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
  <title>言宇宙<?php if(in_array('admin',$path_array)){?>後台<?php } ?></title>
</head>

<body>