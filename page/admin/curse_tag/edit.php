<?php require_once ($_SERVER['DOCUMENT_ROOT'].'/component/head.php'); 
require_once $_SERVER['DOCUMENT_ROOT'] . '/config/constant.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/controller/admin/curse_tag.php';
$url = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$query = parse_url($url, PHP_URL_QUERY);
if (!preg_match('/^id=\d+$/', $query)) {
  header('Location: ' . ROOT . '/page/admin/curse_tag/list.php');
  exit;
}
$id =  intval(substr($query, strpos($query, '=') + 1));
$db = new Db();
$curseTagController = new Curse_tag(new Curse_tag_model($db),new Account_model($db));
$info = $curseTagController->get($id);
if(empty($info)){
 header('Location: ' . ROOT . '/page/admin/curse_tag/list.php');
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
        <h1>編輯黑語言標籤</h1>
        <a href="list.php" id="back">返回</a>
        <form id="curse-tag-edit">
        <label for="">名稱</label>
          <div> <input type="text" id="name" value="<?=$info['name']?>" placeholder="請輸入名稱"><label for="" id="name-error" class="error">必填</label></div>
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
    <script src="<?=ROOT.'/js/page/admin/curse_tag/edit.js'?>" type="module"></script>
    <?php require_once ($_SERVER['DOCUMENT_ROOT'].'/component/foot.php'); ?>