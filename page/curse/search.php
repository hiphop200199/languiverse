<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/component/head.php'); 
require_once $_SERVER['DOCUMENT_ROOT'] . '/controller/curse.php';

$db = new Db();
$curseController = new CurseFrontend(new Curse_model($db));
$list = $curseController->getList();
?>
<div id="page">
   <h1 id="orientation-remind">僅支援直向模式</h1>
   
   <div class="box">
    <?php foreach ($list as $k=>$v):?>
    <a href="content.php?id=<?=$v['id']?>"><?= ($k+1).'. '.$v['content'] ?></a>
    <?php endforeach;?>
   </div>
   <section class="buttons">
    <a href="index.php">回到首頁</a>
   </section>
   <p id="copyright">Copyright => EricWoo 2025</p>
</div>
<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/component/foot.php'); ?>