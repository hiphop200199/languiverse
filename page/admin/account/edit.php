<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/component/head.php');
require_once $_SERVER['DOCUMENT_ROOT'] . '/config/constant.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/controller/admin/account.php';
$url = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$query = parse_url($url, PHP_URL_QUERY);
if (!preg_match('/^id=\d$/', $query)) {
  header('Location: ' . ROOT . '/page/admin/account/list.php');
  exit;
}
$id =  intval(substr($query, strpos($query, '=') + 1));
$accountController = new Account(new Account_model(new Db()));
$info = $accountController->get($id);
?>
<div id="backend">
  <h1 id="orientation-remind">僅支援直向模式</h1>
  <?php require_once $_SERVER['DOCUMENT_ROOT'] . '/component/slide-menu.php';
  require_once $_SERVER['DOCUMENT_ROOT'] . '/component/loading.php';
  require_once $_SERVER['DOCUMENT_ROOT'] . '/component/confirmLB.php';
  require_once $_SERVER['DOCUMENT_ROOT'] . '/component/alertLB.php';
  ?>
  <section id="main">
    <section id="form">
      <h1>編輯帳號</h1>
      <a href="list.php" id="back">返回</a>
      <form id="account-edit">
        <label for="">帳號</label>
        <div> <input type="text" id="account" placeholder="請輸入帳號" value="<?= $info['account']; ?>"><label for="" id="account-error" class="error">必填</label></div>
        <label for="">密碼</label>
        <div><span>******</span><button id="password-btn">修改密碼</button></div>
        <label for="">暱稱</label>
        <div><input type="text" id="name" value="<?=$info['name']?>" placeholder="請輸入暱稱"><label for="" id="nickname-error" class="error">必填</label></div>
        <label for="">email</label>
        <div><input type="email" value="<?= $info['email']; ?>" id="email" placeholder="請輸入email"><label for="" id="email-error" class="error">必填</label></div>
         <?php if($accountController->data['account']['is_admin']==IS_ADMIN):?> 
        <label for="">狀態</label>
        <section id="status">
          <label for=""><input type="radio" <?php if ($info['status'] == ACTIVE) { ?>checked <?php } ?> name="status"  value="2">啟用<input type="radio" <?php if (!$info['status'] == ACTIVE) { ?>checked<?php } ?> name="status"  value="1">停用</label>
        </section>
           <?php endif;?> 
        <section id="button">
          <button type="submit">提交</button>
         <button id="cancel">取消</button>
        </section>
      </form>
    </section>
    <div id="password-mask" class="mask">
      <div id="password-lb" class="lb">
      <div class="input-block"><input type="password" id="new-password" placeholder="請輸入新密碼"><label for="" id="new-password-error" class="error">必填</label></div>
      <div class="input-block"><input type="password" name="" id="confirm-password" placeholder="請確認新密碼"><label for="" id="confirm-password-error" class="error">必填</label></div>
        <section id="button">
          <button id="submit-password">確認</button>
          <button id="close-password">取消</button>
        </section>
       </div>
    </div>
  </section>
</div>
<script src="<?=ROOT.'/js/page/admin/account/edit.js'?>" type="module"></script>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/component/foot.php'); ?>