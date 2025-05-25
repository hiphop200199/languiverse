<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/component/head.php'); 
require_once $_SERVER['DOCUMENT_ROOT'] . '/controller/touching.php';

$url = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$query = parse_url($url, PHP_URL_QUERY);
$queryArray = explode('&',$query);
$db = new Db();
$touchingController = new Touching_frontend(new Touching_model($db));
$list = $touchingController->getList($queryArray);
$sourceList = $touchingController->getSourceList();
?>
<div id="page">
   <h1 id="orientation-remind">僅支援直向模式</h1>
   <h2>出處</h2>
   <div class="box">
    <?php if(!empty($sourceList)) :?>
    <?php foreach ($sourceList as $k=>$v):?> 
   <a href="?source=<?=urlencode($v['source'])?>" class="source"><?=$v['source']?></a>
    <?php endforeach;?> 
    <?php endif;?>
   </div>
   <?php if(!empty($list)):?>
   <h2>結果</h2>
   <div class="box">
    <?php foreach ($list as $k=>$v):?>
        <a class="result" href="content.php?id=<?=$v['id']?>"><?=($k+1).' '.$v['content']?></a>
    <?php endforeach;?>
   </div>
   <?php endif;?>
   <p id="copyright">Copyright => EricWoo 2025</p>
    <a class="left-link" href="index.php">回到首頁</a>
   <a href="#" id="top">至頂</a>
</div>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/component/foot.php'); ?>