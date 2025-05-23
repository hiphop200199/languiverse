<?php require_once ($_SERVER['DOCUMENT_ROOT'].'/component/head.php');
require_once $_SERVER['DOCUMENT_ROOT'] . '/component/loading.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/component/confirmLB.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/component/alertLB.php';
session_start();
?>
<div id="page">
<h1 id="orientation-remind">僅支援直向模式</h1>
        <div id="reset">
        <h1>言宇宙學院</h1>
        <h2>後台管理系統</h2>
        <form  method="post" id="reset-form">
            <?php if($_SESSION['reset-mail-expire'] > time()) :?>
            <div class="input-block"><input type="password" id="new-password" placeholder="請輸入新密碼"><label for="" id="new-password-error" class="error">必填</label></div>
            <div class="input-block"><input type="password" name="" id="confirm-password" placeholder="請確認新密碼"><label for="" id="confirm-password-error" class="error">必填</label></div>
            <input type="hidden" id="userId" name="" value="<?=empty($_SESSION['user-id'])?'':$_SESSION['user-id']?>">
            <button type="submit" id="reset-btn">確認</button>
            <?php else :?>
                <h1 id="expire-remind">此連結已失效</h1>
            <?php endif;?>
        </form>
    </div>
    <h4>Copyright © EricWoo 2025 | All Rights Reserved.</h4>
    </div>
    <script src="<?=ROOT.'/js/page/admin/reset-password.js'?>" type="module"></script>
    <?php require_once ($_SERVER['DOCUMENT_ROOT'].'/component/foot.php'); ?>