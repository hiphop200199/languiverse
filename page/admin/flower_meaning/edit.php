<?php require_once ($_SERVER['DOCUMENT_ROOT'].'/component/head.php');
require_once $_SERVER['DOCUMENT_ROOT'] . '/config/constant.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/controller/admin/flower_meaning.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/controller/admin/flower_meaning_category.php';
$url = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$query = parse_url($url, PHP_URL_QUERY);
if (!preg_match('/^id=\d+$/', $query)) {
  header('Location: ' . ROOT . '/page/admin/flower_meaning/list.php');
  exit;
}
$id =  intval(substr($query, strpos($query, '=') + 1));
$db = new Db();
$flowerMeaningController = new Flower_meaning(new Flower_meaning_model($db),new Account_model($db));
$info = $flowerMeaningController->get($id);
if(empty($info)){
  header('Location: ' . ROOT . '/page/admin/flower_meaning/list.php');
  exit;
}
$flowerMeaningCategoryController = new Flower_meaning_category(new Flower_meaning_category_model($db),new Account_model($db));
$categoryList = $flowerMeaningCategoryController->index(); 

?>
<div id="backend">
<h1 id="orientation-remind">僅支援直向模式</h1>
<?php require_once $_SERVER['DOCUMENT_ROOT'].'/component/slide-menu.php' ; ?>
<section id="main">
      <section id="form">
        <h1>編輯花語</h1>
        <a href="list.php" id="back">返回</a>
        <form action="">
          <label for="">內容</label>
          <div> <textarea name="" id="content"><?=$info['content']?></textarea><label for="" id="content-error" class="error">必填</label></div>
          <label for="">故事</label>
          <textarea name="" id="story"><?=$info['story']?></textarea>
          <label for="">類別</label>
          <div>  <select name="" id="category">
            <option value="">請選擇類別</option>
            <?php foreach($categoryList as $k=>$v):?>
              <option <?php if($info['category']==$v['id']){?>selected<?php }?> value="<?=$v['id']?>"><?=$v['name']?></option>
              <?php endforeach;?>
          </select><label for="" id="category-error" class="error">必填</label></div>
          <label for="">圖片</label>
          <label for="image" id="upload-image">
            <img src="<?= empty($info['image'])? ROOT.'/image/upload-image.png':ROOT.$info['image']?>" id="upload-image-source" alt="upload-image">
          </label>
          <input type="file" name="" id="image" accept="image/png,image/jpg,image/jpeg,image/gif">
          <span id="image-remind">圖片格式：JPG,PNG,GIF，限1MB</span>
          <label for="">狀態</label>
          <section id="status">
            <label for=""><input type="radio" name="status" id="" <?php if ($info['status'] == ACTIVE) { ?>checked <?php } ?> value="2">上架<input type="radio" name="status" id="" <?php if ($info['status'] != ACTIVE) { ?>checked <?php } ?> value="1">下架</label>
          </section>
          <section id="button">
            <button type="submit">提交</button>
           <button id="cancel">取消</button>
          </section>
        </form>
      </section>
      </section>
    </div>
    <script src="<?=ROOT.'/js/page/admin/flower_meaning/edit.js'?>" type="module"></script>
    <?php require_once ($_SERVER['DOCUMENT_ROOT'].'/component/foot.php'); ?>