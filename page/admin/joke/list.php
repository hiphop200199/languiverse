<?php require_once ($_SERVER['DOCUMENT_ROOT'].'/component/head.php');
require_once $_SERVER['DOCUMENT_ROOT'] . '/config/constant.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/controller/admin/joke.php';
$db = new Db();
$jokeController = new Joke(new Joke_model($db),new Joke_with_tag_model($db),new Account_model($db));
$list = $jokeController->index();
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
              <td><?= $v['status'] == ACTIVE ? '上架' : '下架'; ?></td>
              <td >
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
            <p>狀態:<?= $v['status'] == ACTIVE ? '上架' : '下架'; ?></p>
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
