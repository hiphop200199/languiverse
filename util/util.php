<?php

class Util
{
    //取得該筆model的id
    public static function getIdOfModel()
    {
        $url = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $result = parse_url($url, PHP_URL_QUERY);
        $query = $result['query'];
        $path = $result['path'];
        $path_array = explode('/', $path);

        if (!preg_match('/^id=\d$/', $query)) {
            header('Location: ' . ROOT . '/page/admin/' . $path_array[count($path_array) - 2] . '/list.php');
            exit;
        }
        return intval(substr($query, strpos($query, '=') + 1));
    }

    //取得網址列查詢參數
    public static function getSearchQuery()
    {
        $url = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $query = parse_url($url, PHP_URL_QUERY);
        return explode('&', $query);
    }

    //回傳拆分後的網址列參數值
    public static function getSearchQueryValue(array $array)
    {
        return   array_map(function ($item) {
            $item = substr($item, strpos($item, '=') + 1);
            return $item;
        }, $array);
    }

    //每個管理物件執行方法前的入口
    public static function requestEntry(object $object)
    {
        switch ($_POST['task']) {
            case 'create':
                $object->create();
                break;
            case 'edit':
                $object->edit();
                break;
            case 'delete':
                $object->delete();
                break;
            case 'edit-password':
                $object->editPassword();
                break;
            case 'login':
                $object->login();
                break;
            case 'logout':
                $object->logout();
                break;
            case 'forgot-password':
                $object->forgotPassword();
                break;
            case 'validate-code':
                $object->validateCode();
                break;
            case 'reset-password':
                $object->resetPassword();
                break;
            case 'create-strategy':
                $object->createStrategy();
                break;
            case 'create-rate':
                $object->createRate();
                break;
            case 'get-random-question':
                $object->getRandomQuestion();
                break;
            case 'create-thought':
                $object->createThought();
                break;
        }
    }
}
