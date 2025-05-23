<?php require_once ($_SERVER['DOCUMENT_ROOT'].'/component/head.php'); 
require_once $_SERVER['DOCUMENT_ROOT'] . '/config/constant.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/controller/admin/curse.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/controller/admin/curse_category.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/controller/admin/curse_tag.php';
$url = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$query = parse_url($url, PHP_URL_QUERY);
if (!preg_match('/^id=\d+$/', $query)) {
  header('Location: ' . ROOT . '/page/admin/curse/list.php');
  exit;
}
$id =  intval(substr($query, strpos($query, '=') + 1));
$db = new Db();
$curseController = new Curse(new Curse_model($db),new Curse_with_tag_model($db),new Curse_strategy_model($db),new Account_model($db));
$info = $curseController->get($id);
if(empty($info)){
  header('Location: ' . ROOT . '/page/admin/curse/list.php');
  exit;
}
$curseCategoryController = new Curse_category(new Curse_category_model($db),new Account_model($db));
$categoryList = $curseCategoryController->index(); 
$curseTagController = new Curse_tag(new Curse_tag_model($db),new Account_model($db));
$tagList = $curseTagController->index(); 

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
        <h1>編輯黑語言</h1>
        <a href="list.php" id="back">返回</a>
        <form id="curse-edit">
        <label for="">內容</label>
          <div> <input type="text" value="<?=$info['content']?>" id="content" placeholder="請輸入內容"><label for="" id="content-error" class="error">必填</label></div>
          <label for="">類別</label>
          <div>  <select name="" id="category">
            <option value="">請選擇類別</option>
            <?php foreach($categoryList as $k=>$v):?>
              <option <?php if($info['category']==$v['id']){?>selected<?php } ?> value="<?=$v['id']?>"><?=$v['name']?></option>
              <?php endforeach;?>
          </select><label for="" id="category-error" class="error">必填</label></div>
          <label for="">標籤</label>
          <section id="tag-area">
            <?php foreach ($tagList as $k=>$v):?>
          <label for=""><input type="checkbox" <?php if(in_array($v['id'],$info['tags'])){?>checked<?php }?> name="tag" value="<?=$v['id']?>"><?=$v['name']?></label>
          <?php endforeach;?>
          </section>
          <label>圖片</label>
          <label for="image" id="upload-image">
            <img src="<?= empty($info['image'])?ROOT.'/image/upload-image.png':ROOT.$info['image']?>" id="upload-image-source" alt="upload-image">
          </label>
          <input type="file" name="" id="image" accept="image/png,image/jpg,image/jpeg,image/gif">
          <span id="image-remind">圖片格式：JPG,PNG,GIF，限1MB</span>
          <section id="strategy-area">
            <?php foreach($info['strategies'] as $ks=>$vs):?>
            <div data-id="<?=$ks+1?>" class="strategy">
            <button data-id="<?=$ks+1?>" class="delete-strategy">✖</button>
              <p data-id="<?=$ks+1?>">編號：<?=$ks+1?></p>
              <label>內容</label>
              <textarea  class="content"><?=$vs['content']?></textarea>
              <label>圖片</label>
          <label for="strategy-image-<?=$ks+1?>" class="upload-images">
            <img src="<?= empty($vs['image'])?ROOT.'/image/upload-image.png':ROOT.$vs['image']?>"  alt="upload-image">
          </label>
          <input type="file" id="strategy-image-<?=$ks+1?>" class="strategy-images" accept="image/png,image/jpg,image/jpeg,image/gif">
          <span class="image-remind">圖片格式：JPG,PNG,GIF，限1MB</span>
            </div>
            <?php endforeach;?>
          </section>
          <button id="add-strategy">新增防禦術</button>
          <label for="">狀態</label>
          <section id="status">
            <label for=""><input type="radio" name="status" <?php if ($info['status'] == ACTIVE) { ?>checked <?php } ?> id="" value="2">上架<input type="radio" name="status" <?php if ($info['status'] != ACTIVE) { ?>checked <?php } ?> id="" value="1">下架</label>
          </section>
          <section id="button">
            <button type="submit">提交</button>
           <button id="cancel">取消</button>
          </section>
        </form>
      </section>
      </section>
    </div>
    <script src="<?=ROOT.'/js/page/admin/curse/edit.js'?>" type="module"></script>
    <?php require_once ($_SERVER['DOCUMENT_ROOT'].'/component/foot.php'); ?>