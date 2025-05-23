<?php require_once ($_SERVER['DOCUMENT_ROOT'].'/component/head.php'); ?>
<div id="backend">
<h1 id="orientation-remind">僅支援直向模式</h1>
<?php require_once $_SERVER['DOCUMENT_ROOT'].'/component/slide-menu.php' ;
 require_once $_SERVER['DOCUMENT_ROOT'] . '/component/loading.php';
 require_once $_SERVER['DOCUMENT_ROOT'] . '/component/confirmLB.php';
 require_once $_SERVER['DOCUMENT_ROOT'] . '/component/alertLB.php';
?>
<section id="main">
      <section id="form">
        <h1>新增冷笑話標籤</h1>
        <a href="list.php" id="back">返回</a>
        <form id="joke-tag-add">
        <label for="">名稱</label>
          <div> <input type="text" id="name" placeholder="請輸入名稱"><label for="" id="name-error" class="error">必填</label></div>
          <label for="">狀態</label>
          <section id="status">
            <label for=""><input type="radio" name="status" checked id="" value="2">啟用<input type="radio" name="status" id="" value="1">停用</label>
          </section>
          <section id="button">
            <button type="submit">提交</button>
           <button id="cancel">取消</button>
          </section>
        </form>
      </section>
      </section>
    </div>
    <script src="<?=ROOT.'/js/page/admin/joke_tag/add.js'?>" type="module"></script>
    <?php require_once ($_SERVER['DOCUMENT_ROOT'].'/component/foot.php'); ?>