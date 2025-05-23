<?php require_once ($_SERVER['DOCUMENT_ROOT'].'/component/head.php');
require_once $_SERVER['DOCUMENT_ROOT'] . '/config/constant.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/controller/admin/curse_category.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/controller/admin/curse_tag.php';
$db = new Db();
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
        <h1>新增黑語言</h1>
        <a href="list.php" id="back">返回</a>
        <form id="curse-add">
          <label for="">內容</label>
          <div> <input type="text" id="content" placeholder="請輸入內容"><label for="" id="content-error" class="error">必填</label></div>
          <label for="">類別</label>
          <div>  <select name="" id="category">
            <option value="">請選擇類別</option>
            <?php foreach($categoryList as $k=>$v):?>
              <option value="<?=$v['id']?>"><?=$v['name']?></option>
              <?php endforeach;?>
          </select><label for="" id="category-error" class="error">必填</label></div>
          <label for="">標籤</label>
          <section id="tag-area">
            <?php foreach ($tagList as $k=>$v):?>
          <label for=""><input type="checkbox" name="tag" value="<?=$v['id']?>"><?=$v['name']?></label>
          <?php endforeach;?>
          </section>
          <label>圖片</label>
          <label for="image" id="upload-image">
            <img src="<?=ROOT.'/image/upload-image.png'?>" id="upload-image-source" alt="upload-image">
          </label>
          <input type="file" name="" id="image" accept="image/png,image/jpg,image/jpeg,image/gif">
          <span id="image-remind">圖片格式：JPG,PNG,GIF，限1MB</span>
          <section id="strategy-area">
            <!-- <div data-id="" class="strategy">
            <button data-id="" class="delete-strategy">✖</button>
              <p data-id="1">編號：</p>
              <label>內容</label>
              <textarea  class="content"></textarea>
              <label>圖片</label>
          <label for="" class="upload-images">
            <img src="<=ROOT.'/image/upload-image.png'?>"  alt="upload-image">
          </label>
          <input type="file" id="" class="strategy-images" accept="image/png,image/jpg,image/jpeg,image/gif">
          <span class="image-remind">圖片格式：JPG,PNG,GIF，限1MB</span>
            </div> -->
          </section>
          <button id="add-strategy">新增防禦術</button>
          <label for="">狀態</label>
          <section id="status">
            <label for=""><input type="radio" name="status" checked id="" value="2">上架<input type="radio" name="status" id="" value="1">下架</label>
          </section>
          <section id="button">
            <button type="submit">提交</button>
           <button id="cancel">取消</button>
          </section>
        </form>
      </section>
      </section>
    </div>
    <script src="<?=ROOT.'/js/page/admin/curse/add.js'?>" type="module"></script>
    <?php require_once ($_SERVER['DOCUMENT_ROOT'].'/component/foot.php'); ?>