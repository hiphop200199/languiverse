<div id="page">
   <h1 id="orientation-remind">僅支援直向模式</h1>
   <nav>
      <a href="../main-menu.php">回到列表</a>
      <a href="search.php">我想要找</a>
   </nav>
   <div id="hero-image"></div>
   <h2>還記得內心觸動的時候嗎？</h2>
   <article>
</article>
   <h2>隨機一則冷笑話</h2>
   <div class="box">
      <p class="ask"><?=$random['question']?></p>
      <p class="reply"><?=$random['answer']?></p>
      <img src="<?=empty($random['image'])?'':ROOT . $random['image']?>" alt="">
      <p class="editor">by <?=$random['name']?></p>
   </div>
 
   <p id="copyright">Copyright © EricWoo 2025 | All Rights Reserved.</p>
   <a href="#" id="top">top</a>
</div>