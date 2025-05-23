<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/controller/admin/common.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/config/constant.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/model/flower_meaning_model.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Flower_meaning extends Common
{
    private $flower_meaning_model;
    public function __construct(Flower_meaning_model $flower_meaning_model,Account_model $account_model)
    {
        parent::__construct($account_model);
        parent::init();
        $this->flower_meaning_model = $flower_meaning_model;
    }

    public function requestEntry()
    {
        switch ($_POST['task']) {
            case 'create':
                $this->create();
                break;
            case 'edit':
                $this->edit();
                break;
            case 'delete':
                $this->delete();
                break;
          
        }
    }

    public function index()
    {
        $list = $this->flower_meaning_model->getList();
        return $list;
    }

    public function get($id)
    {
        $info = $this->flower_meaning_model->get($id);
        return $info;
    }

    private function create()
    {
        $content = $_POST['content'];
        $story = $_POST['story'];
        $category = intval($_POST['category']);
        $status = intval($_POST['status']);
        $image = $_FILES['image'];
        $allowFileTypes = ['image/png', 'image/jpg', 'image/jpeg', 'image/gif'];
        $imageSourceString = '';
   $editor = intval($this->data['account']['id']);
        if (!empty($image)) {
            if ($image['size'] > 1 * 1024 * 1024) {
                $response = json_encode(['errCode' => FILE_OVERSIZE]);
                echo $response;
                exit;
            }

            if (!in_array($image['type'], $allowFileTypes)) {
                $response = json_encode(['errCode' => FILE_FORMAT_ERROR]);
                echo $response;
                exit;
            }
            $maxId = $this->checkMaxId();
            $targetDirectory = $_SERVER['DOCUMENT_ROOT'] . '/upload/flower_meaning/';
            $targetFileName = ($maxId + 1) . '-' . basename($image['name']);
            $targetFile = $targetDirectory . $targetFileName;
            if (move_uploaded_file($image['tmp_name'], $targetFile)) {
                $imageSourceString = '/upload/flower_meaning/' . $targetFileName;
            }
        }

        $result = $this->flower_meaning_model->create($content, $story, $category, $imageSourceString, $status,$editor);
        if ($result === SUCCESS) {
            $response = json_encode(['errCode' => SUCCESS, 'redirect' => 'list.php']);
            echo $response;
            exit;
        } else {
            $response = json_encode(['errCode' => SERVER_INTERNAL_ERROR]);
            echo $response;
            exit;
        }
    }

    private function edit()
    {
        $id = intval($_POST['id']);
        $checkExist = $this->flower_meaning_model->get($id);
        if (empty($checkExist)) {
            $response = json_encode(['errCode' => CURSE_CATEGORY_NOT_EXIST]);
            echo $response;
            exit;
        }
        $content = $_POST['content'];
        $story = $_POST['story'];
        $category = intval($_POST['category']);
        $status = intval($_POST['status']);
        $image = $_FILES['image'];
        $allowFileTypes = ['image/png', 'image/jpg', 'image/jpeg', 'image/gif'];
        $imageSourceString = empty($image) ? $checkExist['image'] : '';

        if (!empty($image)) {
            if ($image['size'] > 1 * 1024 * 1024) {
                $response = json_encode(['errCode' => FILE_OVERSIZE]);
                echo $response;
                exit;
            }

            if (!in_array($image['type'], $allowFileTypes)) {
                $response = json_encode(['errCode' => FILE_FORMAT_ERROR]);
                echo $response;
                exit;
            }

            $targetDirectory = $_SERVER['DOCUMENT_ROOT'] . '/upload/flower_meaning/';
            $targetFileName = $id . '-' . basename($image['name']);
            $targetFile = $targetDirectory . $targetFileName;
            if (move_uploaded_file($image['tmp_name'], $targetFile)) {
                $imageSourceString = '/upload/flower_meaning/' . $targetFileName;
            }
        }

        $result = $this->flower_meaning_model->edit($id, $content, $story, $category, $imageSourceString, $status);
        if ($result === SUCCESS) {
            $response = json_encode(['errCode' => SUCCESS, 'redirect' => 'list.php']);
            echo $response;
            exit;
        } else {
            $response = json_encode(['errCode' => SERVER_INTERNAL_ERROR]);
            echo $response;
            exit;
        }
    }


    private function delete()
    {
        $id = intval($_POST['id']);

        $result = $this->flower_meaning_model->delete($id);
        if ($result === SUCCESS) {
            $response = json_encode(['errCode' => SUCCESS, 'redirect' => 'list.php']);
            echo $response;
            exit;
        } else {
            $response = json_encode(['errCode' => SERVER_INTERNAL_ERROR]);
            echo $response;
            exit;
        }
    }

    private function export()
    {
        $format = intval($data['format']);
        $list = $this->flower_meaning_model->getList();
        switch ($format) {
            case 1:
                $heading = ['id', '名稱', '狀態', '建立者', '建立時間', '更新時間'];
                $csv = fopen('export/curse_tag.csv', 'w+');
                fputcsv($csv, $heading);
                foreach ($list as $key => $value) {
                    fputcsv($csv, $value);
                }
                fclose($csv);

                break;

            case 2:

                break;
        }
    }

    private function checkMaxId()
    {
        $id = $this->flower_meaning_model->getMaxId();
        return $id;
    }
}

$url = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$query = parse_url($url, PHP_URL_QUERY);
$query_array = explode('&', $query);

$query_array = array_map(function ($item) {
    $item = substr($item, strpos($item, '=') + 1);
    return $item;
}, $query_array);

$db = new Db();

$flowerMeaning = new Flower_meaning(new Flower_meaning_model($db),new Account_model($db));

if (in_array('export', $query_array)) {
    $joke->export($query_array[1]);
    exit;
}

$flowerMeaning->requestEntry();

unset($flowerMeaning);
