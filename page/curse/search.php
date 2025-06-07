<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/component/head.php'); 
require_once $_SERVER['DOCUMENT_ROOT'] . '/controller/curse.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/controller/curse_category.php';

$url = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$query = parse_url($url, PHP_URL_QUERY);
$queryArray = explode('&',$query);
$db = new Db();
$curseCategoryController = new Curse_category_frontend(new Curse_category_model($db));
$categoryList = $curseCategoryController->index();
$curseController = new CurseFrontend(new Curse_model($db));
$list = $curseController->getList($queryArray);
?>
<div id="page">
   <h1 id="orientation-remind">僅支援直向模式</h1>
   <div class="box">
    <?php foreach($categoryList as $v):?>
          <a href="?category=<?=$v['id']?>" class="category <?php switch(mt_rand(1,3)){ case 1:?><?php break;case 2:?>middle<?php break;case 3:?>right<?php break;}?>"><?=$v['name']?></a>
    <?php endforeach;?>
  </div>
   
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