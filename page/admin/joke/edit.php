<?php require_once ($_SERVER['DOCUMENT_ROOT'].'/component/head.php');
require_once $_SERVER['DOCUMENT_ROOT'] . '/config/constant.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/controller/admin/joke.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/controller/admin/joke_category.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/controller/admin/joke_tag.php';
$url = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$query = parse_url($url, PHP_URL_QUERY);
if (!preg_match('/^id=\d+$/', $query)) {
  header('Location: ' . ROOT . '/page/admin/joke/list.php');
  exit;
}
$id =  intval(substr($query, strpos($query, '=') + 1));
$db = new Db();
$jokeController = new Joke(new Joke_model($db),new Joke_with_tag_model($db),new Account_model($db));
$info = $jokeController->get($id);
if(empty($info)){
  header('Location: ' . ROOT . '/page/admin/joke/list.php');
  exit;
}
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
        <h1>編輯冷笑話</h1>
        <a href="list.php" id="back">返回</a>
        <form id="joke-edit">
          <label for="">問題</label>
          <div> <input type="text" id="question" placeholder="請輸入問題" value="<?=$info['question']?>"><label for="" id="question-error" class="error">必填</label></div>
          <label for="">回答</label>
          <div><input type="text"  id="answer" placeholder="請輸入回答" value="<?=$info['answer']?>"><label for="" id="answer-error" class="error">必填</label></div>
          <label for="">靈感</label>
          <textarea name="" id="inspiration"><?=$info['inspiration']?></textarea>
          <label for="">類別</label>
          <div>  <select name="" id="category">
            <option value="">請選擇類別</option>
            <?php foreach($categoryList as $k=>$v):?>
              <option <?php if($info['category']==$v['id']){?>selected<?php }?> value="<?=$v['id']?>"><?=$v['name']?></option>
              <?php endforeach;?>
          </select><label for="" id="category-error" class="error">必填</label></div>
          <label for="">標籤</label>
          <section id="tag-area">
            <?php foreach ($tagList as $k=>$v):?>
          <label for=""><input type="checkbox" <?php if(in_array($v['id'],$info['tags'])){?>checked<?php }?> name="tag" value="<?=$v['id']?>"><?=$v['name']?></label>
          <?php endforeach;?>
          </section>
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
    <script src="<?=ROOT.'/js/page/admin/joke/edit.js'?>" type="module"></script>
    <?php require_once ($_SERVER['DOCUMENT_ROOT'].'/component/foot.php'); ?>