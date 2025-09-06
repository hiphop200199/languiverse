<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/component/head.php'); 
require_once $_SERVER['DOCUMENT_ROOT'] . '/controller/touching.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/util/util.php';

$id =  Util::getIdOfModel();
$db = new Db();
$touchingController = new Touching_frontend(new Touching_model($db));
$info = $touchingController->get($id);
?>
<div id="page">
   <h1 id="orientation-remind">僅支援直向模式</h1>
    <?php 
  require_once $_SERVER['DOCUMENT_ROOT'] . '/component/loadingIcon.php';
  require_once $_SERVER['DOCUMENT_ROOT'] . '/component/alertLBFrontend.php';
  ?>
   <div class="box">
   <p class="content"><?=htmlspecialchars($info['content'])?></p>
   <p class="source"><?=htmlspecialchars($info['source'])?></p>
      <img src="<?=empty($info['image'])?'':ROOT.$info['image']?>" alt="">
      <p class="editor">由 <?=htmlspecialchars($info['name'])?> 建立</p>
      <?php if(!empty($info['thoughts'])):?>
      <h3>感想</h3>
      <?php foreach($info['thoughts'] as $k=>$v):?>
      <p class="<?= $k%2?'thought':'thought-left'?>"><?=htmlspecialchars($v['thought'])?></p>
      <?php endforeach;?>
      <?php endif;?>
      <button id="open-thought-modal">寫下感想</button>
   </div>
   <p id="copyright">Copyright => EricWoo 2025</p>
    <a class="right-link" href="index.php">回到首頁</a>
    <a class="left-link" href="search.php">我想要找</a>
   <a href="#" id="top">至頂</a>
   <dialog id="thought-modal">
  <form id="thought-add">
    <label >感想</label>
    <textarea name="thought" placeholder="想寫什麼..."></textarea>
    <div id="button-area">
      <button type="submit">送出</button><button id="cancel">取消</button>
    </div>
  </form>
   </dialog>
</div>
 <script src="<?=ROOT.'/js/page/touching/content.js'?>" type="module"></script>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/component/foot.php'); ?>