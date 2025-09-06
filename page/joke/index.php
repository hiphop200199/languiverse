<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/component/head.php'); 
require_once $_SERVER['DOCUMENT_ROOT'] . '/config/constant.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/controller/joke.php';
 $joke = new JokeFrontend(new Joke_model(new Db()));
$random = $joke->randomJoke(); 
$list = $joke->jokeEvaluation();
/*$list = $joke->jokeOfThelist(); */
?>
<div id="page">
   <h1 id="orientation-remind">僅支援直向模式</h1>
   <nav>
      <a href="../main-menu.php">回到列表</a>
      <a href="search.php">尋尋覓覓</a>
      <a href="test.php">你知道嗎</a>
   </nav>
   <div id="hero-image"></div>
   <h2>冷笑話定義</h2>
   <article>根據<a href="https://zh.wikipedia.org/zh-tw/%E5%86%B7%E7%AC%91%E8%A9%B1" target="_blank" id="wiki">維基百科</a>，冷笑話也叫爛梗，是指笑話本身因為無聊、諧音字、關聯太牽強等等原因，導致不好笑而冷場。但這不一定代表笑話本身沉悶，當他準確的表達了有趣的巧合，就可能是幽默的一種特別表現形式。

      有些冷笑話重新構想後，可以再發掘不少微妙之處，也可能重新解釋後，就真的變好笑了(哈)。冷笑話在很多地方都有，在家裡、公園、路上、公司、學校也有人會講冷笑話來解悶，非常普及。相信你也可以發現冷笑話其中的魅力，進而構想出自己獨特的冷笑話。
</article>
   <h2>隨機一則冷笑話</h2>
   <div class="box">
      <p class="ask"><?=htmlspecialchars($random['question'])?></p>
      <p class="reply"><?=htmlspecialchars($random['answer'])?></p>
      <img src="<?=empty($random['image'])?'':ROOT . $random['image']?>" alt="">
      <p class="editor">by <?=htmlspecialchars($random['name'])?></p>
   </div>
   <?php if(!empty($list)):?>
   <h2>票選冷笑話</h2>
   <h3>最好笑冷笑話</h3>
   <div class="box">
      <p class="ask"><?=htmlspecialchars($list[0]['question'])?></p>
      <p class="reply"><?=htmlspecialchars($list[0]['answer'])?></p>
      <img src="<?=empty($list[0]['image'])?'':ROOT.$list[0]['image']?>" alt="">
      <p>平均評分：<?=number_format($list[0]['avg_score'],1)?></p>
      <p class="editor">by <?=htmlspecialchars($list[0]['name'])?></p>
   </div>

   <h3>最難笑冷笑話</h3>
   <div class="box">
      <p class="ask"><?=htmlspecialchars($list[1]['question'])?></p>
      <p class="reply"><?=htmlspecialchars($list[1]['answer'])?></p>
     <img src="<?=empty($list[1]['image'])?'':ROOT.$list[1]['image']?>" alt="">
      <p>平均評分：<?=number_format($list[1]['avg_score'],1)?></p>
      <p class="editor">by <?=htmlspecialchars($list[1]['name'])?></p>
   </div>
   <?php endif;?>
   <p id="copyright">Copyright © EricWoo 2024 | All Rights Reserved.</p>
   <a href="#" id="top">top</a>
</div>
<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/component/foot.php'); ?>