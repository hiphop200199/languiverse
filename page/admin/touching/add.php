<?php require_once ($_SERVER['DOCUMENT_ROOT'].'/component/head.php');
require_once $_SERVER['DOCUMENT_ROOT'] . '/config/constant.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/controller/admin/touching_source.php';
$db = new Db();
$touchingSourceController = new Touching_source(new Touching_source_model($db),new Account_model($db));
$sourceList = $touchingSourceController->index(); 
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
        <h1>新增感人話語</h1>
        <a href="list.php" id="back">返回</a>
        <form id="touching-add">
          <label for="">內容</label>
          <div> <input type="text" id="content" placeholder="請輸入內容"><label for="" id="content-error" class="error">必填</label></div>
         <label for="">出處</label>
          <div>  <select name="" id="source">
            <option value="">請選擇出處</option>
            <?php foreach($sourceList as $k=>$v):?>
              <option value="<?=$v['id']?>"><?=$v['name']?></option>
              <?php endforeach;?>
          </select><label for="" id="source-error" class="error">必填</label></div>
          <label>圖片</label>
          <label for="image" id="upload-image">
            <img src="<?=ROOT.'/image/upload-image.png'?>" id="upload-image-source" alt="upload-image">
          </label>
          <input type="file" name="" id="image" accept="image/png,image/jpg,image/jpeg,image/gif">
          <span id="image-remind">圖片格式：JPG,PNG,GIF，限1MB</span>
            <label for="">連結</label>
          <div><input type="text" id="link" placeholder="請輸入連結"></div>
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
    <script src="<?=ROOT.'/js/page/admin/touching/add.js'?>" type="module"></script>
    <?php require_once ($_SERVER['DOCUMENT_ROOT'].'/component/foot.php'); ?>