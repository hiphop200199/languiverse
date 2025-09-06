<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/component/head.php');
require_once $_SERVER['DOCUMENT_ROOT'] . '/config/constant.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/controller/admin/account.php';
$accountController = new Account(new Account_model(new Db()));
$list = $accountController->index();
?>
<div id="backend">
  <h1 id="orientation-remind">僅支援直向模式</h1>
  <?php require_once $_SERVER['DOCUMENT_ROOT'] . '/component/slide-menu.php';
  require_once $_SERVER['DOCUMENT_ROOT'] . '/component/loading.php';
  require_once $_SERVER['DOCUMENT_ROOT'] . '/component/confirmLB.php';
  require_once $_SERVER['DOCUMENT_ROOT'] . '/component/alertLB.php';
  ?>
  <section id="list">
    <div id="function">
    <?php if($accountController->data['account']['is_admin'] == IS_ADMIN):?>
      <a href="add.php">新增帳號</a>
   <?php endif;?>
    <!--   <div id="export-area">
        <select name="" id="format">
          <option value="">請選擇匯出格式</option>
          <option value="1">csv</option>
          <option value="2">excel</option>
        </select>
        <a id="export">匯出</a>
      </div> -->
    </div>
    <table>
      <thead>
        <tr>
          <th>id</th>
          <th>帳號</th>
          <th>狀態</th>
          <th>操作</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($list as $k => $v): ?>
          <tr>
            <td><?= htmlspecialchars($v['id']); ?></td>
            <td><?= htmlspecialchars($v['account']); ?></td>
            <td><?= $v['status'] == ACTIVE ? '啟用' : '停用'; ?></td>
            <td class="operation"><a href="edit.php?id=<?= htmlspecialchars($v['id']); ?>" class="edit">🖊</a> <?php if($accountController->data['account']['is_admin']==IS_ADMIN){?><a data-id="<?= htmlspecialchars($v['id']); ?>" class="delete">🗑</a><?php }?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <div id="grid">
        <?php foreach ($list as $k => $v): ?>
      <div class="item">
          <p>id:<?= htmlspecialchars($v['id']); ?></p>
          <p>帳號:<?= htmlspecialchars($v['account']); ?></p>
          <p>狀態:<?= $v['status'] == ACTIVE ? '啟用' : '停用'; ?></p>
          <section class="button">
            <a href="edit.php?id=<?= htmlspecialchars($v['id']); ?>" class="edit">🖊</a> <?php if($accountController->data['account']['is_admin']==IS_ADMIN){?><a data-id="<?= htmlspecialchars($v['id']); ?>" class="delete">🗑</a><?php }?>
          </section>
      </div>
    <?php endforeach; ?>
    </div>
  </section>
</div>
<script src="<?=ROOT.'/js/page/admin/account/list.js'?>" type="module"></script>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/component/foot.php'); ?>