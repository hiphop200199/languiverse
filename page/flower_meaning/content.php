<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/component/head.php'); 
require_once $_SERVER['DOCUMENT_ROOT'] . '/controller/flower_meaning.php';
$url = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$query = parse_url($url, PHP_URL_QUERY);
if (!preg_match('/^id=\d+$/', $query)) {
  header('Location: ' . ROOT . '/page/flower_meaning/search.php');
  exit;
}
$id =  intval(substr($query, strpos($query, '=') + 1));
$db = new Db();
$flowerMeaningController = new Flower_meaning_frontend(new Flower_meaning_model($db));
$info = $flowerMeaningController->get($id);
?>
    <div id="page">
    <h1 id="orientation-remind">僅支援直向模式</h1>
   <h1><?=$info['name']?></h1>
   <div class="box">
    <h2>花語</h2>
    <p><?=str_replace(',','<br>',$info['content'])?></p>
    <h2>故事</h2>
    <p><?=$info['story']?></p>
    <img src="<?=empty($info['image'])?'':ROOT.$info['image']?>" alt="">
    <p class="editor">by <?=$info['editor_name']?></p>
   </div>
   <a class="function-link" href="index.php">=>回到首頁</a>
   <a class="function-link left" href="search.php">=>搜尋花語</a>
   <p id="copyright">Copyright => EricWoo 2025</p>
</div>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/component/foot.php'); ?>
   