<?php require_once ($_SERVER['DOCUMENT_ROOT'].'/component/head.php');
require_once $_SERVER['DOCUMENT_ROOT'] . '/config/constant.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/controller/admin/trends_age.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/util/util.php';
$id =  Util::getIdOfModel();
$db = new Db();
$trendsAgeController = new Trends_age(new Trends_age_model($db),new Account_model($db));
$info = $trendsAgeController->get($id);
if(empty($info)){
  header('Location: ' . ROOT . '/page/admin/trends_age/list.php');
  exit;
}
?>
<div id="backend">
<h1 id="orientation-remind">僅支援直向模式</h1>
<?php require_once $_SERVER['DOCUMENT_ROOT'].'/component/slide-menu.php' ;
 require_once $_SERVER['DOCUMENT_ROOT'] . '/component/loading.php';
 require_once $_SERVER['DOCUMENT_ROOT'] . '/component/confirmLB.php';
 require_once $_SERVER['DOCUMENT_ROOT'] . '/component/alertLB.php';
?>
<section id="main">
      <section id="form">
        <h1>編輯流行語時代</h1>
        <a href="list.php" id="back">返回</a>
        <form id="trends-age-edit">
        <label for="">名稱</label>
          <div> <input type="text" id="name" value="<?=htmlspecialchars($info['name'])?>" placeholder="請輸入名稱"><label for="" id="name-error" class="error">必填</label></div>
          <label for="">狀態</label>
          <section id="status">
            <label for=""><input type="radio" name="status"<?php if ($info['status'] == ACTIVE) { ?>checked <?php } ?> id="" value="2">啟用<input type="radio" name="status" <?php if ($info['status'] != ACTIVE) { ?>checked <?php } ?> id="" value="1">停用</label>
          </section>
          <section id="button">
            <button type="submit">提交</button>
           <button id="cancel">取消</button>
          </section>
        </form>
      </section>
      </section>
    </div>
    <script src="<?=ROOT.'/js/page/admin/trends_age/edit.js'?>" type="module"></script>
    <?php require_once ($_SERVER['DOCUMENT_ROOT'].'/component/foot.php'); ?>