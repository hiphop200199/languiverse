<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/component/head.php'); 
require_once $_SERVER['DOCUMENT_ROOT'] . '/controller/trends.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/util/util.php';

$id =  Util::getIdOfModel();
$db = new Db();
$trendsController = new TrendsFrontend(new Trends_model($db));
$info = $trendsController->get($id);
?>
<div id="page">
   <h1 id="orientation-remind">僅支援直向模式</h1>
    <?php 
  require_once $_SERVER['DOCUMENT_ROOT'] . '/component/loadingIcon.php';
  require_once $_SERVER['DOCUMENT_ROOT'] . '/component/alertLBFrontend.php';
  ?>
   <div class="box">
   <p class="content"><?=htmlspecialchars($info['content'])?></p>
   <p class="age"><?=htmlspecialchars($info['age_name'])?></p>
      
      <p class="editor">由 <?=htmlspecialchars($info['editor_name'])?> 建立</p>
    
   </div>
   <section class="buttons">
        <a class="link" href="../main-menu.php">回到列表</a>
        <a class="link" href="search.php">前往搜尋</a>
      </section>
      <p id="copyright">Copyright => EricWoo 2025</p>
      <a href="#" id="top">top</a>
</div>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/component/foot.php'); ?>