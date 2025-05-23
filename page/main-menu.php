<?php
require_once ($_SERVER['DOCUMENT_ROOT'].'/component/head.php'); ?>
    <div id="page">
    <h1 id="orientation-remind">僅支援直向模式</h1>
       <!-- <h1 id="title">你的方向</h1> -->
       <section id="link-box">
   <a href="./joke/index.php" class="link"><p>難笑笑話</p><!-- <img src="../image/snowflake.png" alt=""> --></a>
       <a href="" class="link"><p>黑語言防禦術</p><!-- <img src="../image/fire.png" alt=""> --></a>
       <a href="" class="link"><p>感人話語</p><!-- <img src="../image/fox.png" alt=""> --></a>
       <a href="" class="link"><p>花語收藏</p><!-- <img src="../image/flowers.png" alt=""> --></a>
       </section>
    <canvas id="canvas"></canvas>
    </div>
   <script src="<?= ROOT.'/js/page/main-menu.js';?>"></script>
<?php require_once ($_SERVER['DOCUMENT_ROOT'].'/component/foot.php'); ?>