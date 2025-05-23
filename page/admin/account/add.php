<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/component/head.php'); ?>
<div id="backend">
  <h1 id="orientation-remind">僅支援直向模式</h1>
  <?php require_once $_SERVER['DOCUMENT_ROOT'] . '/component/slide-menu.php';
  require_once $_SERVER['DOCUMENT_ROOT'] . '/component/loading.php';
  require_once $_SERVER['DOCUMENT_ROOT'] . '/component/confirmLB.php';
  require_once $_SERVER['DOCUMENT_ROOT'] . '/component/alertLB.php';
  ?>

  <section id="main">
    <section id="form">
      <h1>新增帳號</h1>
      <a href="list.php" id="back">返回</a>
      <form id="account-add">
        <label for="">帳號</label>
        <div> <input type="text" id="account" placeholder="請輸入帳號"><label for="" id="account-error" class="error">必填</label></div>
        <label for="">密碼</label>
        <div><input type="password" name="" id="password" placeholder="請輸入密碼"><label for="" id="password-error" class="error">必填</label></div>
        <label for="">暱稱</label>
        <div><input type="text" id="name" placeholder="請輸入暱稱"><label for="" id="nickname-error" class="error">必填</label></div>
        <label for="">email</label>
        <div><input type="email" name="" id="email" placeholder="請輸入email"><label for="" id="email-error" class="error">必填</label></div>
        <!-- <php if ($is_admin): ?> -->
          <label for="">狀態</label>
          <section id="status">
            <label for=""><input type="radio" name="status" checked value="2">啟用<input type="radio" name="status" value="1">停用</label>
          </section>
        <!-- <php endif ?> -->
        <section id="button">
          <button type="submit">提交</button>
         <button id="cancel">取消</button>
        </section>
      </form>
    </section>
  </section>
</div>
<script src="<?=ROOT.'/js/page/admin/account/add.js'?>" type="module"></script>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/component/foot.php'); ?>