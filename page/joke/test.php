<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/component/head.php'); 
require_once $_SERVER['DOCUMENT_ROOT'] . '/controller/joke.php';
?>
<div id="page">
   <h1 id="orientation-remind">僅支援直向模式</h1>
   <?php 
  require_once $_SERVER['DOCUMENT_ROOT'] . '/component/loadingIcon.php';
  require_once $_SERVER['DOCUMENT_ROOT'] . '/component/alertLBFrontend.php';
  ?>
   <nav>
      <a href="search.php">尋尋覓覓</a>
      <a href="index.php">回到首頁</a>
   </nav>
   <div id="hero-image"></div>
   <h2>TOBJE冷笑話測驗</h2>
   <div id="test-box">
    <img id="test-cover-img" src="<?=ROOT?>/image/confident.png" alt="">
    <div id="testing-box">
      <p id="test-question"></p>
      <audio controls id="test-question-audio"></audio>
      <section id="answers">
        <!-- <label class="test-answers"><input type="radio">123</label>
        <label class="test-answers"><input type="radio">123</label>
        <label class="test-answers"><input type="radio">123</label>
        <label class="test-answers"><input type="radio">123</label> -->
      </section>
    </div>
    <section id="buttons">
      <button class="test-box-btn" id="start-test">開始測驗</button>
      <button class="test-box-btn" id="test-explain-btn">測驗說明</button>
      <button class="during-test" id="next-question-btn">下一題</button>
      <button class="during-test" id="cancel-test-btn">取消測驗</button>
    </section>
   </div>
   <p id="copyright">Copyright © EricWoo 2024 | All Rights Reserved.</p>
   <a href="#" id="top">top</a>
 <dialog id="test-explain-modal">
  <div id="test-explain-content">
     <p>TOBJE(Test Of Bad Joke For Entertainment)為娛樂冷笑話測驗之縮寫，是世上公認評鑑一個人對於冷笑話的敏銳程度最有公信力的測驗，測驗方式相當容易，每一次總共有20道題目，前14題為閱讀選擇題，後6題為聽力選擇題，題型選項皆為單選，每一題都只有一個正確答案，非常好懂，故頗受世人歡迎。相信你一定也可以通過本測驗的。</p>
      <button id="close-test-explain">關閉</button>
  </div>
   </dialog>
   <dialog id="score-modal">
    <div id="score-content">
       <h4 id="score-modal-title">恭喜您得到分！</h4>
    <div id="button-area">
      <button id="export-pdf">下載證書</button>
      <button id="share-fb">分享至FB</button>
      <button id="share-line">分享至LINE</button>
      <button id="close-score">關閉</button>
    </div>
    </div>
   </dialog>
</div>
<script src="<?=ROOT.'/js/page/joke/test.js'?>" type="module"></script>
<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/component/foot.php'); ?>