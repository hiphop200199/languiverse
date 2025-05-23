<?php


require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

require_once $_SERVER['DOCUMENT_ROOT'] . '/controller/admin/common.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/config/constant.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/config/app.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Index extends Common
{

    public function requestEntry()
    {
        $request_body = file_get_contents('php://input');

        $data = json_decode($request_body, true);

        switch ($data['task']) {
            case 'login':
                $this->login($data);
                break;
            case 'logout':
                $this->logout();
                break;
            case 'forgot-password':
                $this->forgotPassword($data);
                break;
            case 'validate-code':
                $this->validateCode($data);
                break;
            case 'reset-password':
                $this->resetPassword($data);
                break;
        }
    }

    private function login($data)
    {
        session_start();
        $account = $data['account'];
        $password = hash('sha256', $data['password']);
        $captcha = $data['captcha'];
        if ($_SESSION['code'] != $captcha) {
            session_regenerate_id();
            $result = ['errCode' => CAPTCHA_ERROR];
            echo json_encode($result);
            exit;
        }
        $account_info = $this->account_model->checkLogin($account, $password);
        if (empty($account_info)) {
            session_regenerate_id();
            $result = ['errCode' => ACCOUNT_OR_PASSWORD_ERROR];
            echo json_encode($result);
            exit;
        }
        if ($account_info['status'] !== ACTIVE) {
            session_regenerate_id();
            $result = ['errCode' => ACCOUNT_INACTIVE];
            echo json_encode($result);
            exit;
        }
        session_regenerate_id();
        setcookie('auth', $account_info['id'] . '|' . hash('sha256', $account_info['id'] . $account_info['account'] . $account_info['password'] . $account_info['email'] . (string)time())  . '|' . (string)time(), time() + 86400, '/', 'languiverse.kesug.com', true, true);
        $result = ['errCode' => SUCCESS, 'redirect' => 'account/list.php'];
        echo json_encode($result);
        exit;
    }

    private function logout()
    {
        setcookie('auth', '', time() - 86400, '/', 'languiverse.kesug.com', true, true);
        $response = json_encode(['errCode'=>SUCCESS,'redirect'=>ROOT.'/page/admin/login.php']);
        echo $response;
        exit;
    }
    private function forgotPassword($data)
    {
        session_start();
        $email = $data['email'];
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            session_regenerate_id();
            echo json_encode(['errCode' => EMAIL_FORMAT_ERROR]);
            exit;
        }
        $result = $this->account_model->getByEmail($email);
        if (empty($result)) {
            session_regenerate_id();
            echo json_encode(['errCode' => ACCOUNT_NOT_EXIST]);
            exit;
        }
        //要寄信通知了
        $validateCode = mt_rand(1000, 9999);
        $validateCodeHash = hash('sha256',$result['id']);
        $link = ROOT . '/page/admin/validate-code.php?i='.$validateCodeHash;
        $_SESSION['validate-code'] = $validateCode;
        $_SESSION['validate-code-hash'] = $validateCodeHash;
        $_SESSION['validate-code-expire'] = time() + 86400;
        $_SESSION['user-id'] = $result['id'];
        $mail = new PHPMailer(true);
        try {
            //Server settings
            $mail->SMTPDebug = SMTP::DEBUG_OFF;                      //Enable verbose debug output
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = 'hiphop200199@gmail.com';                     //SMTP username
            $mail->Password   = 'eqilaussjjkqyoma';                               //SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
            $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

            //Recipients
            $mail->setFrom('languiverse@kesug.com', 'Languiverse');
            $mail->addAddress($email);               //Name is optional
      
            //Content
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = 'Languiverse validation code';
            $mail->Body    = '<h1>親愛的夥伴'.$result['name'].'您好，附上以下資訊：</h1><br><p>驗證碼：'.$validateCode.'</p><p>重設密碼連結：'.$link.'</p><br><p>感謝您的使用</p><p>by 言宇宙學院院長：中午</p>';
            $mail->AltBody = '親愛的夥伴'.$result['name'].'您好，附上以下資訊：'.PHP_EOL.'驗證碼：'.$validateCode.PHP_EOL.'重設密碼連結：'.$link.PHP_EOL.'感謝您的使用'.PHP_EOL.'by 言宇宙學院院長：中午';

            $mail->send();
            $response = json_encode(['errCode'=>SUCCESS]);
            echo $response;
        } catch (Exception $e) {
            $response = json_encode(['errCode'=>SERVER_INTERNAL_ERROR,'errMsg'=>$mail->ErrorInfo]);
            echo $response;
        }
    }

    private function validateCode($data){
        session_start();
        $validateCodeHash = $data['validateCodeHash'];
        $validateCode = $data['validateCode'];
        if($_SESSION['validate-code-hash']!=$validateCodeHash){
            session_regenerate_id();
            unset($_SESSION['validate-code'],$_SESSION['validate-code-hash'],$_SESSION['validate-code-expire'],$_SESSION['user-id']);
            $response = json_encode(['errCode'=>SERVER_INTERNAL_ERROR]);
            echo $response;
            exit;
        }
        if(intval($_SESSION['validate-code-expire'])< time()){
            session_regenerate_id();
             unset($_SESSION['validate-code'],$_SESSION['validate-code-hash'],$_SESSION['validate-code-expire'],$_SESSION['user-id']);
            $response = json_encode(['errCode'=>LINK_EXPIRED]);
            echo $response;
            exit;
        }
        if($_SESSION['validate-code']!=$validateCode){
            session_regenerate_id();
            $response = json_encode(['errCode'=>CAPTCHA_ERROR]);
            echo $response;
            exit;
        }
        session_regenerate_id();
        unset($_SESSION['validate-code'],$_SESSION['validate-code-expire'],$_SESSION['validate-code-hash']);
        $_SESSION['reset-mail-expire'] = time() + 86400;
        $response = json_encode(['errCode'=>SUCCESS,'redirect'=>'reset-password.php']);
        echo $response;
        exit;
    }
    private function resetPassword($data)
    {
        session_start();
        $id = intval($data['id']);
         $checkResult = $this->account_model->get($id);
        if (empty($checkResult)) {
            unset($_SESSION['reset-mail-expire'],$_SESSION['user-id']);
            $response = json_encode(['errCode' => ACCOUNT_NOT_EXIST]);
            echo $response;
            exit;
        }
        if($_SESSION['user-id']!=$id){
            unset($_SESSION['reset-mail-expire'],$_SESSION['user-id']);
            $response = json_encode(['errCode'=>SERVER_INTERNAL_ERROR]);
            echo $response;
            exit;
        }
        $password = hash('sha256', $data['password']);
        $result = $this->account_model->editPassword($id, $password);
        if ($result === SUCCESS) {
            session_regenerate_id();
            unset($_SESSION['reset-mail-expire'],$_SESSION['user-id']);
            setcookie('auth', $id . '|' . hash('sha256', $id . $checkResult['account'] . $password . $checkResult['email'] . (string)time())  . '|' . (string)time(), time() + 86400, '/', 'languiverse.kesug.com', true, true);
            $response = json_encode(['errCode' => SUCCESS, 'redirect' => 'account/list.php']);
            echo $response;
            exit;
        } else {
            session_regenerate_id();
            $response = json_encode(['errCode' => SERVER_INTERNAL_ERROR]);
            echo $response;
            exit;
        }
    }
}

$index = new Index(new Account_model(new Db()));

$index->requestEntry();

unset($index);
