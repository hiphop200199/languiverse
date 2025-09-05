<?php require_once ($_SERVER['DOCUMENT_ROOT'].'/component/head.php');
require_once $_SERVER['DOCUMENT_ROOT'] . '/config/constant.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/controller/admin/joke_category.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/controller/admin/joke_tag.php';
$db = new Db();
$jokeCategoryController = new Joke_category(new Joke_category_model($db),new Account_model($db));
$categoryList = $jokeCategoryController->index(); 
$jokeTagController = new Joke_tag(new Joke_tag_model($db),new Account_model($db));
$tagList = $jokeTagController->index(); 
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
        <h1>新增冷笑話</h1>
        <a href="list.php" id="back">返回</a>
        <form id="joke-add">
          <label for="">問題</label>
          <div> <input type="text" id="question" placeholder="請輸入問題"><label for="" id="question-error" class="error">必填</label></div>
          <label for="">回答</label>
          <div><input type="text" id="answer" placeholder="請輸入回答"><label for="" id="answer-error" class="error">必填</label></div>
          <label for="">靈感</label>
          <textarea name="" id="inspiration"></textarea>
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
          <label>mp3</label>
          <input type="file"  id="mp3" accept="audio/mpeg">
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
    <script src="<?=ROOT.'/js/page/admin/joke/add.js'?>" type="module"></script>
    <?php require_once ($_SERVER['DOCUMENT_ROOT'].'/component/foot.php'); ?>