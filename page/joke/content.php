<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/component/head.php'); 
require_once $_SERVER['DOCUMENT_ROOT'] . '/controller/joke.php';

$url = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$query = parse_url($url, PHP_URL_QUERY);
if (!preg_match('/^id=\d+$/', $query)) {
  header('Location: ' . ROOT . '/page/joke/search.php');
  exit;
}
$id =  intval(substr($query, strpos($query, '=') + 1));
$db = new Db();
$jokeController = new JokeFrontend(new Joke_model($db));
$info = $jokeController->get($id);

?>
    <div id="page">
   <h1 id="orientation-remind">僅支援直向模式</h1>
  <?php 
  require_once $_SERVER['DOCUMENT_ROOT'] . '/component/loadingIcon.php';
  require_once $_SERVER['DOCUMENT_ROOT'] . '/component/alertLBFrontend.php';
  ?>
   <nav>
      <a href="index.php">回到首頁</a>
      <a href="search.php">尋尋覓覓</a>
      <a href="test.php">你知道嗎</a>
   </nav>
   <div id="hero-image"></div>
   <h2>內容↓</h2>
   <div class="box">
    <p class="ask">問題：<?=$info['question']?></p>
    <p class="reply">回答：<?=$info['answer']?></p>
    <img src="<?=empty($info['image'])?'':ROOT.$info['image']?>" alt="">
    <p>類別：<?=$info['category_name']?></p>
    <p>標籤：<?php foreach($info['subinfo'] as $vs):?><button class="tag" data-id="<?=$vs['tag_id']?>"><?=$vs['name']?></button><?php endforeach;?></p>
  <p class="editor">作者：<?=$info['editor_name']?></p>
  <h3>評價↓</h3>
  <section id="comment-area">
    <?php foreach ($info['rateinfo'] as $vr):?>
<div class="comment">
    <p><?=$vr['comment']?>.</p><p><?= str_repeat('★',$vr['score']).str_repeat('☆',5 - $vr['score']) ?></p>
</div>
<?php endforeach;?>
  </section>
  <button id="open-comment-modal">新增評價</button>
   </div>
   <p id="copyright">Copyright © EricWoo 2024 | All Rights Reserved.</p>
   <a href="#" id="top">top</a>
   <dialog id="comment-modal">
  <form id="comment-add">
    <label >評價</label>
    <textarea name="comment" placeholder="想寫什麼..."></textarea>
    <label >好笑度評分</label>
    <div>
    <?php for($i=0;$i<5;$i++):?>
    <label class="score-label" for="s-<?=$i+1?>"><input class="score" <?php if($i==0){?>checked<?php }?> type="radio" id="s-<?=$i+1?>" name="score" value="<?=$i+1?>"><?=$i+1?></label>
    <?php endfor;?>
    </div>
    <div id="button-area">
      <button type="submit">送出</button><button id="cancel">取消</button>
    </div>
  </form>
   </dialog>
</div>
<script src="<?=ROOT.'/js/page/joke/content.js'?>" type="module"></script>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/component/foot.php'); ?>
   