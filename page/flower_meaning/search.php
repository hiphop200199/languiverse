<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/component/head.php'); 
require_once $_SERVER['DOCUMENT_ROOT'] . '/controller/flower_meaning.php';
$db = new Db();
$flowerMeaningController = new Flower_meaning_frontend(new Flower_meaning_model($db));
$list = $flowerMeaningController->index();
?>
    <div id="page">
    <h1 id="orientation-remind">僅支援直向模式</h1>
  <h1>品種</h1>
   <div class="box">
      <?php foreach($list as $k=>$v):?>
      <a href="content.php?id=<?=$v['id']?>"><?= ($k+1).'. '.$v['name']?></a>
    <?php endforeach;?>
   </div>
    <a class="function-link" href="index.php">=>回到首頁</a>
   <p id="copyright">Copyright => EricWoo 2025</p>
</div>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/component/foot.php'); ?>
   