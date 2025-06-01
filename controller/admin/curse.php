<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/controller/admin/common.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/config/constant.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/model/curse_model.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/model/curse_strategy_model.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/model/curse_with_tag_model.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Curse extends Common
{
    private $curse_model, $curse_strategy_model, $curse_with_tag_model;
    public function __construct(Curse_model $curse_model, Curse_with_tag_model $curse_with_tag_model, Curse_strategy_model $curse_strategy_model, Account_model $account_model)
    {
        parent::__construct($account_model);
        parent::init();
        $this->curse_model = $curse_model;
        $this->curse_with_tag_model = $curse_with_tag_model;
        $this->curse_strategy_model = $curse_strategy_model;
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
        $list = $this->curse_model->getList();
        return $list;
    }

    public function get($id)
    {
        $info = $this->curse_model->get($id);
        $tagData = $this->curse_with_tag_model->getList($id);
        $tags = [];
        foreach ($tagData as $v) {
            $tags[] = $v['tag_id'];
        }
        $info['tags'] = $tags;
        $strategies = $this->curse_strategy_model->getList($id);
        $info['strategies'] = $strategies;
        return $info;
    }

    private function create()
    {
        $content = $_POST['content'];
        $category = intval($_POST['category']);
        $tag = empty($_POST['tag']) ? '' : array_map('intval', explode(',', $_POST['tag']));
        $strategy = empty($_POST['strategy']) ? '' : json_decode($_POST['strategy'], true);
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
            $targetDirectory = $_SERVER['DOCUMENT_ROOT'] . '/upload/curse/';
            $targetFileName = ($maxId + 1) . '-' . basename($image['name']);
            $targetFile = $targetDirectory . $targetFileName;
            if (move_uploaded_file($image['tmp_name'], $targetFile)) {
                $imageSourceString = '/upload/curse/' . $targetFileName;
            }
        }

        $result = $this->curse_model->create($content, $category, $status, $imageSourceString, $editor);

        if ($result['errCode'] === SUCCESS) {
            if (!empty($tag)) {
                $res = '';
                foreach ($tag as $v) {
                    $res = $this->curse_with_tag_model->create($result['id'], $v);
                }
                if ($res === SERVER_INTERNAL_ERROR) {
                    $response = json_encode(['errCode' => SERVER_INTERNAL_ERROR]);
                    echo $response;
                    exit;
                }
            }
            if (!empty($strategy)) {
                $createStrategyResult = '';
                foreach ($strategy as $vs) {
                    $createStrategyResult = $this->curse_strategy_model->create($result['id'], $vs['content']);
                }
                if ($createStrategyResult === SERVER_INTERNAL_ERROR) {
                    $response = json_encode(['errCode' => SERVER_INTERNAL_ERROR]);
                    echo $response;
                    exit;
                }
            }
            $response = json_encode(['errCode' => SUCCESS, 'redirect' => 'list.php']);
            echo $response;
            exit;
        }
        $response = json_encode(['errCode' => SERVER_INTERNAL_ERROR]);
        echo $response;
        exit;
    }

    private function edit()
    {
        $id = intval($_POST['id']);
        $checkExist = $this->curse_model->get($id);
        if (empty($checkExist)) {
            $response = json_encode(['errCode' => CURSE_CATEGORY_NOT_EXIST]);
            echo $response;
            exit;
        }
        $content = $_POST['content'];
        $category = intval($_POST['category']);
        $tag = empty($_POST['tag']) ? '' : array_map('intval', explode(',', $_POST['tag']));
        $strategy = empty($_POST['strategy']) ? '' : json_decode($_POST['strategy'], true);
        $status = intval($_POST['status']);
        $image = empty($_FILES['image']) ? '' : $_FILES['image'];
        $allowFileTypes = ['image/png', 'image/jpg', 'image/jpeg', 'image/gif'];
        $oldImage = empty($checkExist['image']) ? '' : $checkExist['image'];
        $imageSourceString = empty($image) ? $oldImage : '';

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

            $targetDirectory = $_SERVER['DOCUMENT_ROOT'] . '/upload/curse/';
            $targetFileName = $id . '-' . basename($image['name']);
            $targetFile = $targetDirectory . $targetFileName;
            if (move_uploaded_file($image['tmp_name'], $targetFile)) {
                $imageSourceString = '/upload/curse/' . $targetFileName;
            }
        }
        $deleteTagResult = $this->curse_with_tag_model->delete($id);
        if ($deleteTagResult === SERVER_INTERNAL_ERROR) {
            $response = json_encode(['errCode' => SERVER_INTERNAL_ERROR]);
            echo $response;
            exit;
        }
        $deleteStrategyResult = $this->curse_strategy_model->delete($id);
        if ($deleteStrategyResult === SERVER_INTERNAL_ERROR) {
            $response = json_encode(['errCode' => SERVER_INTERNAL_ERROR]);
            echo $response;
            exit;
        }
        if (!empty($tag)) {
            $res = '';
            foreach ($tag as $v) {
                $res = $this->curse_with_tag_model->create($id, $v);
            }
            if ($res === SERVER_INTERNAL_ERROR) {
                $response = json_encode(['errCode' => SERVER_INTERNAL_ERROR]);
                echo $response;
                exit;
            }
        }
        if (!empty($strategy)) {
            $createStrategyResult = '';
            foreach ($strategy as $vs) {
                $createStrategyResult = $this->curse_strategy_model->create($id, $vs['content']);
            }
            if ($createStrategyResult === SERVER_INTERNAL_ERROR) {
                $response = json_encode(['errCode' => SERVER_INTERNAL_ERROR]);
                echo $response;
                exit;
            }
        }
        $result = $this->curse_model->edit($id, $content, $category, $status, $imageSourceString);
        if ($result === SUCCESS) {
            $response = json_encode(['errCode' => SUCCESS, 'redirect' => 'list.php']);
            echo $response;
            exit;
        }
        $response = json_encode(['errCode' => SERVER_INTERNAL_ERROR]);
        echo $response;
        exit;
    }

    private function delete()
    {
        $id = intval($_POST['id']);
        $deleteTagResult = $this->curse_with_tag_model->delete($id);
        if ($deleteTagResult === SERVER_INTERNAL_ERROR) {
            $response = json_encode(['errCode' => SERVER_INTERNAL_ERROR]);
            echo $response;
            exit;
        }
        $deleteStrategyResult = $this->curse_strategy_model->delete($id);
        if ($deleteStrategyResult === SERVER_INTERNAL_ERROR) {
            $response = json_encode(['errCode' => SERVER_INTERNAL_ERROR]);
            echo $response;
            exit;
        }
        $result = $this->curse_model->delete($id);
        if ($result === SUCCESS) {
            $response = json_encode(['errCode' => SUCCESS, 'redirect' => 'list.php']);
            echo $response;
            exit;
        }
        $response = json_encode(['errCode' => SERVER_INTERNAL_ERROR]);
        echo $response;
        exit;
    }

    public function export($format)
    {
        $heading = ['id', '內容', '策略', '類別', '標籤', '狀態', '建立者', '建立時間', '更新時間'];
        $list = $this->curse_model->getExportList();
        switch ($format) {
            case CSV:
                header('Content-Type: text/csv; charset=utf-8');
                header('Content-Disposition: attachment; filename=curse.csv');
                $csv = fopen('php://output', 'w+');
                fputcsv($csv, $heading);
                foreach ($list as  $value) {
                    $status = $value['status'] == ACTIVE ? '啟用' : '停用';
                    $createTime = date('Y-m-d', $value['createtime']);
                    $updateTime = date('Y-m-d', $value['updatetime']);
                    $tmp = [$value['id'], $value['content'], $value['strategies'], $value['category_name'], $value['tags'], $status, $value['editor_name'], $createTime, $updateTime];
                    fputcsv($csv, $tmp);
                }
                rewind($csv);
                fclose($csv);
                break;
            case EXCEL:
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition:attachment;filename="curse.xlsx"');
                $spreadsheet = new Spreadsheet();
                $activeWorksheet = $spreadsheet->getActiveSheet();
                $cellNumber = 1;
                $activeWorksheet->setCellValue('A' . $cellNumber, $heading[0]);
                $activeWorksheet->setCellValue('B' . $cellNumber, $heading[1]);
                $activeWorksheet->setCellValue('C' . $cellNumber, $heading[2]);
                $activeWorksheet->setCellValue('D' . $cellNumber, $heading[3]);
                $activeWorksheet->setCellValue('E' . $cellNumber, $heading[4]);
                $activeWorksheet->setCellValue('F' . $cellNumber, $heading[5]);
                $activeWorksheet->setCellValue('G' . $cellNumber, $heading[6]);
                $activeWorksheet->setCellValue('H' . $cellNumber, $heading[7]);
                $activeWorksheet->setCellValue('I' . $cellNumber, $heading[8]);
                $cellNumber += 1;
                foreach ($list as $d) {
                    $activeWorksheet->setCellValue('A' . $cellNumber, $d['id']);
                    $activeWorksheet->setCellValue('B' . $cellNumber, $d['content']);
                    $activeWorksheet->setCellValue('C' . $cellNumber, $d['strategies']);
                    $activeWorksheet->setCellValue('D' . $cellNumber, $d['category_name']);
                    $activeWorksheet->setCellValue('E' . $cellNumber, $d['tags']);
                    $activeWorksheet->setCellValue('F' . $cellNumber, $d['status'] == ACTIVE ? '啟用' : '停用');
                    $activeWorksheet->setCellValue('G' . $cellNumber,  $d['editor_name']);
                    $activeWorksheet->setCellValue('H' . $cellNumber, date('Y-m-d', $d['createtime']));
                    $activeWorksheet->setCellValue('I' . $cellNumber, date('Y-m-d', $d['updatetime']));
                    $cellNumber += 1;
                }
                $writer = new Xlsx($spreadsheet);
                $writer->save("php://output");
                break;
        }
    }

    private function checkMaxId()
    {
        $id = $this->curse_model->getMaxId();
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

$curse = new Curse(new Curse_model($db), new Curse_with_tag_model($db), new Curse_strategy_model($db), new Account_model($db));

if (in_array('export', $query_array)) {
    $curse->export($query_array[1]);
    exit;
}

$curse->requestEntry();

unset($curse);
