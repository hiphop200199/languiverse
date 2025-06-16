<?php
require_once ($_SERVER['DOCUMENT_ROOT'].'/component/head.php'); ?>
    <div id="page">
    <h1 id="orientation-remind">僅支援直向模式</h1>
    
       <section id="link-box">
   <a href="./joke/index.php" class="link"><p>難笑笑話</p></a>
       <!-- <a href="./curse/index.php" class="link"><p>黑語言防禦術</p></a> -->
       <a href="./touching/index.php" class="link"><p>感人話語</p></a>
       <!-- <a href="./flower_meaning/index.php" class="link"><p>花語收藏</p></a> -->
       </section>
    <canvas id="canvas"></canvas>
    </div>
   <script src="<?= ROOT.'/js/page/main-menu.js';?>"></script>
<?php require_once ($_SERVER['DOCUMENT_ROOT'].'/component/foot.php'); ?>