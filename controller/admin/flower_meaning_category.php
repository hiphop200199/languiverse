<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/controller/admin/common.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/config/constant.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/model/flower_meaning_category_model.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Flower_meaning_category extends Common
{
   private $flower_meaning_category_model;
    public function __construct(Flower_meaning_category_model $flower_meaning_category_model,Account_model $account_model){
        parent::__construct($account_model);
        parent::init();
        $this->flower_meaning_category_model = $flower_meaning_category_model;
    }

    public function requestEntry()
    {
        $request_body = file_get_contents('php://input');
    
        $data = json_decode($request_body, true);
      
        switch ($data['task']) {
          
            case 'create':
                $this->create($data);
                break;
            case 'edit':
                $this->edit($data);
                break;
            case 'delete':
                $this->delete($data);
                break;
           
        } 
    }

    public function index()
    {
        $list = $this->flower_meaning_category_model->getList();
        return $list;
    }

    public function get($id){
        $info = $this->flower_meaning_category_model->get($id);
        return $info;
    }

    private function create($data) {
        $name = $data['name'];
        $status = intval($data['status']);
           $editor = intval($this->data['account']['id']);
        $result = $this->flower_meaning_category_model->create($name,$status,$editor);
        if($result===SUCCESS){
            $response = json_encode(['errCode'=>SUCCESS,'redirect'=>'list.php']);
           echo $response;
           exit;
        }else{
            $response = json_encode(['errCode'=>SERVER_INTERNAL_ERROR]);
            echo $response;
            exit;
        }
    }

    private function edit($data) {
        $id = intval($data['id']);
        $name = $data['name'];
        $status = intval($data['status']);
        $checkExist = $this->flower_meaning_category_model->get($id);
        if(empty($checkExist)){
            $response = json_encode(['errCode'=>CURSE_CATEGORY_NOT_EXIST]);
            echo $response;
        }
        $result = $this->flower_meaning_category_model->edit($id,$name,$status);
        if($result===SUCCESS){
            $response = json_encode(['errCode'=>SUCCESS,'redirect'=>'list.php']);
           echo $response;
           exit;
        }else{
            $response = json_encode(['errCode'=>SERVER_INTERNAL_ERROR]);
            echo $response;
            exit;
        }
    }

    private function delete($data) {
        $id = intval($data['id']);
        $result = $this->flower_meaning_category_model->delete($id);
        if($result===SUCCESS){
            $response = json_encode(['errCode'=>SUCCESS,'redirect'=>'list.php']);
           echo $response;
           exit;
        }else{
            $response = json_encode(['errCode'=>SERVER_INTERNAL_ERROR]);
            echo $response;
            exit;
        }
    }

     public function export($format)
    {
        $heading = ['id', '名稱', '狀態', '建立者', '建立時間', '更新時間'];
        $list = $this->flower_meaning_category_model->getExportList();
        switch ($format) {
            case 1:
                header('Content-Type: text/csv; charset=utf-8');
                header('Content-Disposition: attachment; filename=flower_meaning_category.csv');
                $csv = fopen('php://output', 'w+');
                fputcsv($csv, $heading);
                foreach ($list as $key => $value) {
                    $status = $value['status'] == ACTIVE ? '啟用' : '停用';
                    $createTime = date('Y-m-d', $value['createtime']);
                    $updateTime = date('Y-m-d', $value['updatetime']);
                    $tmp = [$value['id'], $value['name'], $status, $value['editor_name'], $createTime, $updateTime];
                    fputcsv($csv, $tmp);
                }
                rewind($csv);
                fclose($csv);
                break;

            case 2:
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition:attachment;filename="flower_meaning_category.xlsx"');
                $spreadsheet = new Spreadsheet();
                $activeWorksheet = $spreadsheet->getActiveSheet();
                $cellNumber = 1;
                $activeWorksheet->setCellValue('A' . $cellNumber, $heading[0]);
                $activeWorksheet->setCellValue('B' . $cellNumber, $heading[1]);
                $activeWorksheet->setCellValue('C' . $cellNumber, $heading[2]);
                $activeWorksheet->setCellValue('D' . $cellNumber, $heading[3]);
                $activeWorksheet->setCellValue('E' . $cellNumber, $heading[4]);
                $activeWorksheet->setCellValue('F' . $cellNumber, $heading[5]);
                $cellNumber += 1;
                foreach ($list as $d) {
                    $status = $d['status'] == ACTIVE ? '啟用' : '停用';
                    $createTime = date('Y-m-d', $d['createtime']);
                    $updateTime = date('Y-m-d', $d['updatetime']);
                    $activeWorksheet->setCellValue('A' . $cellNumber, $d['id']);
                    $activeWorksheet->setCellValue('B' . $cellNumber, $d['name']);
                    $activeWorksheet->setCellValue('C' . $cellNumber, $status);
                    $activeWorksheet->setCellValue('D' . $cellNumber, $d['editor_name']);
                    $activeWorksheet->setCellValue('E' . $cellNumber, $createTime);
                    $activeWorksheet->setCellValue('F' . $cellNumber, $updateTime);
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

$flower_meaning_category = new Flower_meaning_category(new Flower_meaning_category_model($db),new Account_model($db));

if (in_array('export', $query_array)) {
    $flower_meaning_category->export($query_array[1]);
    exit;
}

$flower_meaning_category->requestEntry();

unset($flower_meaning_category);