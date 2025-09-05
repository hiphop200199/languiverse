<?php require_once ($_SERVER['DOCUMENT_ROOT'].'/component/head.php');
require_once $_SERVER['DOCUMENT_ROOT'] . '/config/constant.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/controller/admin/joke.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/controller/admin/joke_category.php';
$url = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$query = parse_url($url, PHP_URL_QUERY);
$queryArray = explode('&',$query);
$categoryNumber = 0;
if(!empty($queryArray)){
  $categoryNumber =  intval(substr($queryArray[0], strpos($queryArray[0], '=') + 1));
}
$db = new Db();
$jokeController = new Joke(new Joke_model($db),new Joke_with_tag_model($db),new Account_model($db));
$list = $jokeController->index($queryArray);
$jokeCategoryController = new Joke_category(new Joke_category_model($db),new Account_model($db));
$categoryList = $jokeCategoryController->index();
?>
    <div id="backend">
    <h1 id="orientation-remind">僅支援直向模式</h1>
<?php require_once $_SERVER['DOCUMENT_ROOT'].'/component/slide-menu.php' ;
require_once $_SERVER['DOCUMENT_ROOT'] . '/component/loading.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/component/confirmLB.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/component/alertLB.php';
?>
      <section id="list">
      <div id="function">
        <a href="add.php">新增冷笑話</a>
        <div id="export-area">
          <select name="" id="format">
            <option value="">請選擇匯出格式</option>
            <option value="1">csv</option>
            <option value="2">excel</option>
          </select>
          <a id="export">匯出</a>
        </div>
         <div id="search-area">
           <select name="" id="category">
            <option value="">請選擇類別</option>
            <?php foreach ($categoryList as $k=>$v):?> 
            <option <?php if($categoryNumber!=0 && $categoryNumber == $v['id']):?>selected<?php endif;?> value="<?=$v['id']?>"><?=$v['name']?></option>
            <?php endforeach;?>
          </select>
          <a  id="search">搜尋</a>
        </div>
       </div>
        <table>
          <thead>
            <tr>
              <th>id</th>
              <th>問題</th>
              <th>狀態</th>
              <th>操作</th>
            </tr>
          </thead>
          <tbody>
          <?php foreach ($list as $k=>$v):?> 
            <tr>
              <td><?=$v['id']?></td>
              <td><?=$v['question']?></td>
              <td><?= $v['status'] == ACTIVE ? '啟用' : '停用'; ?></td>
              <td class="operation">
               <?php if($v['editor']==$jokeController->data['account']['id']):?>
                <a href="edit.php?id=<?= $v['id']; ?>" class="edit">🖊</a> <a data-id="<?= $v['id']; ?>" class="delete">🗑</a>
                <?php endif;?>
              </td>
            </tr>
            <?php endforeach;?>
          </tbody>
        </table>
        <div id="grid">
          <?php foreach($list as $k=>$v):?>
          <div class="item">
            <p>id:<?=$v['id']?></p>
            <p>問題:<?=$v['question']?></p>
            <p>狀態:<?= $v['status'] == ACTIVE ? '啟用' : '停用'; ?></p>
            <section class="button">
             <?php if($v['editor']==$jokeController->data['account']['id']):?>
                <a href="edit.php?id=<?= $v['id']; ?>" class="edit">🖊</a> <a data-id="<?= $v['id']; ?>" class="delete">🗑</a>
                <?php endif;?>
            </section>
          </div>
        <?php endforeach;?>
        </div>
      </section>
    </div>
    <script src="<?=ROOT.'/js/page/admin/joke/list.js'?>" type="module"></script>
    <?php require_once ($_SERVER['DOCUMENT_ROOT'].'/component/foot.php'); ?>
