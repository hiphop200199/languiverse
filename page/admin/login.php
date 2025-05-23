<?php require_once ($_SERVER['DOCUMENT_ROOT'].'/component/head.php'); 
require_once $_SERVER['DOCUMENT_ROOT'] . '/component/loading.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/component/confirmLB.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/component/alertLB.php';
?>
<div id="page">
<h1 id="orientation-remind">僅支援直向模式</h1>
        <div id="login">
        <h1>言宇宙學院</h1>
        <h2>後台管理系統</h2>
        <form   id="login-form">
            <div class="input-block"><input type="text" id="account" placeholder="請輸入帳號"><label for="" id="account-error" class="error">必填</label></div>
            <div class="input-block"><input type="password" name="" id="password" placeholder="請輸入密碼"><label for="" id="password-error" class="error">必填</label></div>
            <div  class="input-block"> <input type="text" id="captcha-code" placeholder="驗證碼"><img id="captcha-image" src="<?=ROOT.'/component/captcha.php'?>" alt=""><button id="refresh">⟲</button><label for="" id="captcha-error" class="error">必填</label></div>
            <button type="submit" id="login-btn">登入</button>
            <a href="forgot-password.php">忘記密碼</a>
        </form>
    </div>
    <h4>Copyright © EricWoo 2025 | All Rights Reserved.</h4>
    </div>
    <script src="<?=ROOT.'/js/page/admin/login.js'?>" type="module"></script>
    <?php require_once ($_SERVER['DOCUMENT_ROOT'].'/component/foot.php'); ?>