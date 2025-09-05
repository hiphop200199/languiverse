 <?php
  require_once($_SERVER['DOCUMENT_ROOT'] . '/component/head.php');
  require_once $_SERVER['DOCUMENT_ROOT'] . '/controller/curse.php';

  $url = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
  $query = parse_url($url, PHP_URL_QUERY);
  if (!preg_match('/^id=\d+$/', $query)) {
    header('Location: ' . ROOT . '/page/curse/search.php');
    exit;
  }
  $id =  intval(substr($query, strpos($query, '=') + 1));
  $db = new Db();
  $curseController = new CurseFrontend(new Curse_model($db));
  $info = $curseController->get($id);
  ?>
 <div id="page">
   <h1 id="orientation-remind">僅支援直向模式</h1>
   <?php
    require_once $_SERVER['DOCUMENT_ROOT'] . '/component/loadingIcon.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/component/alertLBFrontend.php';
    ?>
   <h2>內容↓</h2>
   <div class="box">
     <p class="content">語句：<?= $info['content'] ?></p>
     <p class="editor">作者：<?= $info['editor_name'] ?></p>
     <h3>策略↓</h3>
     <section id="strategy-area">
       <?php foreach ($info['strategyInfo'] as $vs): ?>
         <p><?= $vs['content'] ?></p>
       <?php endforeach; ?>
     </section>
     <button id="open-strategy-modal">新增策略</button>
   </div>
   <section class="buttons">
     <a href="search.php">進入探索</a>
     <a href="index.php">回到首頁</a>
   </section>
   <p id="copyright">Copyright => EricWoo 2025</p>
   <dialog id="strategy-modal">
     <form id="strategy-add">
       <label>策略</label>
       <textarea name="strategy" placeholder="想寫什麼..."></textarea>

       <div id="button-area">
         <button type="submit">送出</button><button id="cancel">取消</button>
       </div>
     </form>
   </dialog>
 </div>
 <script src="<?= ROOT . '/js/page/curse/content.js' ?>" type="module"></script>
 <?php
  require_once($_SERVER['DOCUMENT_ROOT'] . '/component/foot.php'); ?>