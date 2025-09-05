<?php require_once ($_SERVER['DOCUMENT_ROOT'].'/component/head.php');
require_once $_SERVER['DOCUMENT_ROOT'] . '/config/constant.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/controller/admin/trends_age.php';
$db = new Db();
$trendsAgeController = new Trends_age(new Trends_age_model($db),new Account_model($db));
$ageList = $trendsAgeController->index(); 
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
        <h1>新增流行語</h1>
        <a href="list.php" id="back">返回</a>
        <form id="trends-add">
          <label for="">內容</label>
          <div> <input type="text" id="content" placeholder="請輸入內容"><label for="" id="content-error" class="error">必填</label></div>
          <label for="">解釋</label>
          <textarea name="" id="explanation"></textarea>
         <label for="">時代</label>
          <div>  <select name="" id="age">
            <option value="">請選擇時代</option>
            <?php foreach($ageList as $k=>$v):?>
              <option value="<?=$v['id']?>"><?=$v['name']?></option>
              <?php endforeach;?>
          </select><label for="" id="age-error" class="error">必填</label></div>
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
    <script src="<?=ROOT.'/js/page/admin/trends/add.js'?>" type="module"></script>
    <?php require_once ($_SERVER['DOCUMENT_ROOT'].'/component/foot.php'); ?>