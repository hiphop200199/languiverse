<?php require_once ($_SERVER['DOCUMENT_ROOT'].'/component/head.php');
require_once $_SERVER['DOCUMENT_ROOT'] . '/component/loading.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/component/confirmLB.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/component/alertLB.php';
?>
<div id="page">
<h1 id="orientation-remind">僅支援直向模式</h1>
        <div id="forgot">
        <h1>言宇宙學院</h1>
        <h2>後台管理系統</h2>
        <form  method="post" id="forgot-form">
            <div class="input-block"><input type="email" id="email" placeholder="請輸入email"><label for="" id="email-error" class="error">必填</label></div>
            <button type="submit" id="forgot-btn">確認</button>
        </form>
        <!-- <h3 id="email-remind">請查看收件匣中是否有主旨為「Languiverse validation code」的電子郵件</h3> -->
    </div>
    <h4>Copyright © EricWoo 2025 | All Rights Reserved.</h4>
    </div>
    <script src="<?=ROOT.'/js/page/admin/forgot-password.js'?>" type="module"></script>
    <?php require_once ($_SERVER['DOCUMENT_ROOT'].'/component/foot.php'); ?>