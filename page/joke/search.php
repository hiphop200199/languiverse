<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/component/head.php'); 
require_once $_SERVER['DOCUMENT_ROOT'] . '/controller/joke.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/controller/joke_category.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/controller/account.php';

$url = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$query = parse_url($url, PHP_URL_QUERY);
$queryArray = explode('&',$query);
$db = new Db();
$jokeCategoryController = new Joke_category_frontend(new Joke_category_model($db));
$categoryList = $jokeCategoryController->index();
$accountController = new Account_frontend(new Account_model($db));
$editorList = $accountController->index();
$jokeController = new JokeFrontend(new Joke_model($db));
$list = $jokeController->getList($queryArray);
?>
    <div id="page">
   <h1 id="orientation-remind">僅支援直向模式</h1>
   <nav>
      <a href="index.php">回到首頁</a>
      <a href="test.php">你知道嗎</a>
   </nav>
   <div id="hero-image"></div>
   <h2>想怎麼找呢？</h2>
   <div class="box">
        <img src="<?=ROOT.'/image/question-mark.png'?>" alt="">
   </div>
   <!-- <h3>給我關鍵字</h3>
   <div class="box">
      <input type="text" id="keyword" placeholder="我愛關鍵字...">
   </div> -->
   <h3>哪些分類呢</h3>
   <div class="box">
      <?php foreach ($categoryList as $vc):?>
      <label class="category-label" for="c-<?=$vc['id']?>"><input type="checkbox" class="category"  value="<?=$vc['id']?>" id="c-<?=$vc['id']?>"><?=$vc['name']?></label>
      <?php endforeach;?>
   </div>
   <h3>還是想看誰寫的</h3>
   <div class="box">
      <?php foreach ($editorList as $ve):?>
      <label class="who-label" for="w-<?=$ve['id']?>"><input type="checkbox" class="who" value="<?=$ve['id']?>" id="w-<?=$ve['id']?>"><?=$ve['name']?></label>
         <?php endforeach;?>
   </div>
   <button id="search">查詢</button>
   <h3>結果...↓</h3>
   <div class="box">
       <?php foreach ($list as $k=>$v):?>
      <a class="content-link" href="content.php?id=<?=$v['id']?>"><?=$k+1?>.<?=$v['question']?></a>
        <?php endforeach;?>
   </div>
   <p id="copyright">Copyright © EricWoo 2024 | All Rights Reserved.</p>
   <a href="#" id="top">top</a>
</div>
<script src="<?=ROOT.'/js/page/joke/search.js'?>" type="module"></script>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/component/foot.php'); ?>
   