<?php require_once ($_SERVER['DOCUMENT_ROOT'].'/component/head.php');
require_once $_SERVER['DOCUMENT_ROOT'] . '/config/constant.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/controller/admin/touching_source.php';
$db = new Db();
$touchingSourceController = new Touching_source(new Touching_source_model($db),new Account_model($db));
$list = $touchingSourceController->index();
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
        <a href="add.php">新增感人話語出處</a>
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
              <th>名稱</th>
              <th>狀態</th>
              <th>操作</th>
            </tr>
          </thead>
          <tbody>
          <?php foreach ($list as $k => $v): ?>
              <td><?= $v['id']; ?></td>
              <td><?= $v['name']; ?></td>
              <td><?= $v['status'] == ACTIVE ? '啟用' : '停用'; ?></td>
       <td class="operation">
               <?php if($v['editor']==$touchingSourceController->data['account']['id']):?>
                <a href="edit.php?id=<?= $v['id']; ?>" class="edit">🖊</a> <a data-id="<?= $v['id']; ?>" class="delete">🗑</a>
                <?php endif;?>
              </td>
            </tr>
              <?php endforeach;?>
          </tbody>
        </table>
        <div id="grid">
        <?php foreach ($list as $k => $v): ?>
          <div class="item">
          <p>id:<?= $v['id']; ?></p>
          <p>名稱:<?= $v['name']; ?></p>
            <p>狀態:<?= $v['status'] == ACTIVE ? '啟用' : '停用'; ?></p>
            <section class="button">
          <?php if($v['editor']==$touchingSourceController->data['account']['id']):?>
                <a href="edit.php?id=<?= $v['id']; ?>" class="edit">🖊</a> <a data-id="<?= $v['id']; ?>" class="delete">🗑</a>
                <?php endif;?>
            </section>
          </div>
          <?php endforeach;?>
        </div>
      </section>
    </div>
    <script src="<?=ROOT.'/js/page/admin/touching_source/list.js'?>" type="module"></script>
    <?php require_once ($_SERVER['DOCUMENT_ROOT'].'/component/foot.php'); ?>
