<?php require_once ($_SERVER['DOCUMENT_ROOT'].'/component/head.php'); 
require_once $_SERVER['DOCUMENT_ROOT'] . '/component/loading.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/component/confirmLB.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/component/alertLB.php';
?>
<div id="page">
<h1 id="orientation-remind">僅支援直向模式</h1>
        <div id="validate">
        <h1>言宇宙學院</h1>
        <h2>後台管理系統</h2>
        <form method="post" id="validate-form">
            <div class="input-block"><input type="text" id="validate-code" placeholder="請輸入驗證碼"><label for="" id="validate-code-error" class="error">必填</label></div>
            <button type="submit" id="validate-btn">確認</button>
        </form>
    </div>
    <h4>Copyright © EricWoo 2025 | All Rights Reserved.</h4>
    </div>
    <script src="<?=ROOT.'/js/page/admin/validate-code.js'?>" type="module"></script>
    <?php require_once ($_SERVER['DOCUMENT_ROOT'].'/component/foot.php'); ?>