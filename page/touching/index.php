<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/component/head.php'); 
require_once $_SERVER['DOCUMENT_ROOT'] . '/controller/touching.php';
$db = new Db();
$touchingController = new Touching_frontend(new Touching_model($db));
$info = $touchingController->randomTouching();
?>
<div id="page">
   <h1 id="orientation-remind">僅支援直向模式</h1>
   <h2>還記得感動的時候？</h2>
   <article></article>
   <h2>曾聽過這句話</h2>
   <div class="box">
   <p class="content"><?=$info['content']?></p>
   <p class="source"><?=$info['source']?></p>
      <a id="link" <?php if($info['link']){?> target="_blank" href="<?=$info['link']?>"<?php }?>><img src="<?=empty($info['image'])?'':ROOT.$info['image']?>" alt=""></a>
      <p class="editor">由 <?=$info['name']?> 建立</p>
      <?php if(!empty($info['thoughts'])):?>
      <h3>聽聽大家的感想</h3>
      <?php foreach($info['thoughts'] as $k=>$v):?>
      <p class="<?= $k%2?'thought':'thought-left'?>"><?=$v['thought']?></p>
      <?php endforeach;?>
      <?php endif;?>
   </div>
   <p id="copyright">Copyright => EricWoo 2025</p>
    <a class="right-link" href="../main-menu.php">回到列表</a>
    <a class="left-link" href="search.php">我想要找</a>
   <a href="#" id="top">至頂</a>
</div>
<script src="<?=ROOT.'/js/page/touching/index.js'?>" type="module"></script>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/component/foot.php'); ?>