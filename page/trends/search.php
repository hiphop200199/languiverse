<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/component/head.php'); 
require_once $_SERVER['DOCUMENT_ROOT'] . '/controller/trends.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/controller/trends_age.php';
$url = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$query = parse_url($url, PHP_URL_QUERY);
$queryArray = explode('&',$query);
$db = new Db();
$trendsController = new TrendsFrontend(new Trends_model($db));
$trendsAgeController = new Trends_age_frontend(new Trends_age_model($db));
$list = $trendsController->getList($queryArray);
$ageList = $trendsAgeController->index();
?>
<div id="page">
   <h1 id="orientation-remind">僅支援直向模式</h1>
   <h2>時代</h2>
   <div class="box">
    <?php if(!empty($ageList)) :?>
    <?php foreach ($ageList as $k=>$v):?> 
   <a href="?age=<?=$v['id']?>" class="age"><?=$v['name']?></a>
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
     <section class="buttons">
        <a class="link" href="index.php">回到首頁</a>
      </section>
      <p id="copyright">Copyright => EricWoo 2025</p>
      <a href="#" id="top">top</a>
</div>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/component/foot.php'); ?>