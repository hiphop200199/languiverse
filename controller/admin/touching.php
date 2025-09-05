<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/controller/admin/common.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/config/constant.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/model/touching_model.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Touching extends Common{
 private $touching_model;
    public function __construct(Touching_model $touching_model, Account_model $account_model)
    {
        parent::__construct($account_model);
        parent::init();
        $this->touching_model = $touching_model;
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
        $list = $this->touching_model->getList();
        return $list;
    }

    public function get($id)
    {
        $info = $this->touching_model->get($id);
        return $info;
    }

    private function create()
    {
        $content = $_POST['content'];
        $sourceId = $_POST['sourceId'];
        $link = $_POST['link'];
        $status = intval($_POST['status']);
        $editor = intval($this->data['account']['id']);
        $image = $_FILES['image'];
        $allowFileTypes = ['image/png','image/jpg','image/jpeg','image/gif'];
        $imageSourceString = '';
        if(!empty($image)){
            if($image['size'] > 1 * 1024 * 1024){
                $response = json_encode(['errCode'=>FILE_OVERSIZE]);
                echo $response;
                exit;
            }

            if(!in_array($image['type'],$allowFileTypes)){
                $response = json_encode(['errCode'=>FILE_FORMAT_ERROR]);
                echo $response;
                exit;
            }
            $maxId = $this->checkMaxId();
            $targetDirectory = $_SERVER['DOCUMENT_ROOT'].'/upload/touching/';
            $targetFileName = ($maxId+1).'-'. basename($image['name']);
            $targetFile = $targetDirectory .$targetFileName;
            if(move_uploaded_file($image['tmp_name'],$targetFile)){
              $imageSourceString = '/upload/touching/'.$targetFileName;
            }
        }

        $result = $this->touching_model->create($content,$sourceId,$link,$imageSourceString, $status, $editor);
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
        $checkExist = $this->touching_model->get($id);
        if (empty($checkExist)) {
            $response = json_encode(['errCode' => CURSE_CATEGORY_NOT_EXIST]);
            echo $response;
        }
        $content = $_POST['content'];
        $sourceId = intval($_POST['sourceId']);
        $link = $_POST['link'];
        $status = intval($_POST['status']);
        $image = empty($_FILES['image'])?'':$_FILES['image'];
        $allowFileTypes = ['image/png','image/jpg','image/jpeg','image/gif'];
        $oldImage = empty($checkExist['image'])?'':$checkExist['image'];
        $imageSourceString = empty($image)?$oldImage:'';

        if(!empty($image)&&!empty($oldImage)&&file_exists($_SERVER['DOCUMENT_ROOT'].$oldImage)){
            unlink($_SERVER['DOCUMENT_ROOT'].$oldImage);
        }

        if(!empty($image)){
            if($image['size'] > 1 * 1024 * 1024){
                $response = json_encode(['errCode'=>FILE_OVERSIZE]);
                echo $response;
                exit;
            }

            if(!in_array($image['type'],$allowFileTypes)){
                $response = json_encode(['errCode'=>FILE_FORMAT_ERROR]);
                echo $response;
                exit;
            }
            
            $targetDirectory = $_SERVER['DOCUMENT_ROOT'].'/upload/touching/';
            $targetFileName = $id.'-'. basename($image['name']);
            $targetFile = $targetDirectory .$targetFileName;
            if(move_uploaded_file($image['tmp_name'],$targetFile)){
              $imageSourceString = '/upload/touching/'.$targetFileName;
            }
        }

        $result = $this->touching_model->edit($id, $content,$sourceId,$link,$imageSourceString, $status);
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
        $checkExist = $this->touching_model->get($id);
        if (empty($checkExist)) {
            $response = json_encode(['errCode' => CURSE_CATEGORY_NOT_EXIST]);
            echo $response;
            exit;
        }
        if(!empty($checkExist['image'])&&file_exists($_SERVER['DOCUMENT_ROOT'].$checkExist['image'])){
            unlink($_SERVER['DOCUMENT_ROOT'].$checkExist['image']);
        }
        $result = $this->touching_model->delete($id);
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
        $heading = ['id', '內容','出處','感想', '狀態', '建立者', '建立時間', '更新時間'];
        $list = $this->touching_model->getExportList();
        switch ($format) {
            case 1:
                header('Content-Type: text/csv; charset=utf-8');
                header('Content-Disposition: attachment; filename=touching.csv');
                $csv = fopen('php://output', 'w+');
                fputcsv($csv, $heading);
                foreach ($list as  $value) {
                    $status = $value['status'] == ACTIVE ? '啟用' : '停用';
                    $createTime = date('Y-m-d', $value['createtime']);
                    $updateTime = date('Y-m-d', $value['updatetime']);
                    $tmp = [$value['id'],$value['content'],$value['source'],$value['thoughts'], $status, $value['editor_name'], $createTime, $updateTime];
                    fputcsv($csv, $tmp);
                }
                rewind($csv);
                fclose($csv);
                break;
            case 2:
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition:attachment;filename="touching.xlsx"');
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
                    $activeWorksheet->setCellValue('C' . $cellNumber, $d['source']);
                    $activeWorksheet->setCellValue('D' . $cellNumber, $d['thoughts']);
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

    private function checkMaxId(){
        $id = $this->touching_model->getMaxId();
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

$touching = new Touching(new Touching_model($db), new Account_model($db));

if (in_array('export', $query_array)) {
    $touching->export($query_array[1]);
    exit;
}

$touching->requestEntry();

unset($touching);