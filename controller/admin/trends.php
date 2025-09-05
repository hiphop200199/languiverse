<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/controller/admin/common.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/config/constant.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/model/trends_model.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Trends extends Common{
 private $trends_model;
    public function __construct(Trends_model $trends_model, Account_model $account_model)
    {
        parent::__construct($account_model);
        parent::init();
        $this->trends_model = $trends_model;
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
        $list = $this->trends_model->getList();
        return $list;
    }

    public function get($id)
    {
        $info = $this->trends_model->get($id);
        return $info;
    }

    private function create()
    {
        $content = $_POST['content'];
        $age = $_POST['age'];
        $explanation = $_POST['explanation'];
        $status = intval($_POST['status']);
        $editor = intval($this->data['account']['id']);
      
        $result = $this->trends_model->create($content,$age,$explanation, $status, $editor);
        if ($result === SUCCESS) {
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
        $checkExist = $this->trends_model->get($id);
        if (empty($checkExist)) {
            $response = json_encode(['errCode' => CURSE_CATEGORY_NOT_EXIST]);
            echo $response;
        }
        $content = $_POST['content'];
        $age = intval($_POST['age']);
        $explanation = $_POST['explanation'];
        $status = intval($_POST['status']);


        $result = $this->trends_model->edit($id, $content,$age,$explanation, $status);
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
        $checkExist = $this->trends_model->get($id);
        if (empty($checkExist)) {
            $response = json_encode(['errCode' => CURSE_CATEGORY_NOT_EXIST]);
            echo $response;
            exit;
        }
     
        $result = $this->trends_model->delete($id);
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
        $heading = ['id', '內容','時代','解釋', '狀態', '建立者', '建立時間', '更新時間'];
        $list = $this->trends_model->getExportList();
        switch ($format) {
            case 1:
                header('Content-Type: text/csv; charset=utf-8');
                header('Content-Disposition: attachment; filename=trends.csv');
                $csv = fopen('php://output', 'w+');
                fputcsv($csv, $heading);
                foreach ($list as  $value) {
                    $status = $value['status'] == ACTIVE ? '啟用' : '停用';
                    $createTime = date('Y-m-d', $value['createtime']);
                    $updateTime = date('Y-m-d', $value['updatetime']);
                    $tmp = [$value['id'],$value['content'],$value['age_name'],$value['explanation'], $status, $value['editor_name'], $createTime, $updateTime];
                    fputcsv($csv, $tmp);
                }
                rewind($csv);
                fclose($csv);
                break;
            case 2:
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition:attachment;filename="trends.xlsx"');
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
                $cellNumber += 1;
                foreach ($list as $d) {
                    $status = $d['status'] == ACTIVE ? '啟用' : '停用';
                    $createTime = date('Y-m-d', $d['createtime']);
                    $updateTime = date('Y-m-d', $d['updatetime']);
                    $activeWorksheet->setCellValue('A' . $cellNumber, $d['id']);
                    $activeWorksheet->setCellValue('B' . $cellNumber, $d['content']);
                    $activeWorksheet->setCellValue('C' . $cellNumber, $d['age_name']);
                    $activeWorksheet->setCellValue('D' . $cellNumber, $d['explanation']);
                    $activeWorksheet->setCellValue('E' . $cellNumber, $status);
                    $activeWorksheet->setCellValue('F' . $cellNumber, $d['editor_name']);
                    $activeWorksheet->setCellValue('G' . $cellNumber, $createTime);
                    $activeWorksheet->setCellValue('H' . $cellNumber, $updateTime);
                    $cellNumber += 1;
                }
                $writer = new Xlsx($spreadsheet);
                $writer->save("php://output");
                break;
        }
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

$trends = new Trends(new Trends_model($db), new Account_model($db));

if (in_array('export', $query_array)) {
    $trends->export($query_array[1]);
    exit;
}

$trends->requestEntry();

unset($trends);