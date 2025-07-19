<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/controller/admin/common.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/config/constant.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/model/joke_model.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/model/joke_with_tag_model.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Joke extends Common
{
    private $joke_model,$joke_with_tag_model;
    public function __construct(Joke_model $joke_model,Joke_with_tag_model $joke_with_tag_model,Account_model $account_model)
    {
        parent::__construct($account_model);
        parent::init();
        $this->joke_model = $joke_model;
        $this->joke_with_tag_model = $joke_with_tag_model;
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

    public function index(array $queryArray)
    {
        $category = intval(substr($queryArray[0], strpos($queryArray[0], '=') + 1));
        $list = $this->joke_model->getList($category);
        return $list;
    }

    public function get($id)
    {
        $info = $this->joke_model->get($id);
        $tagData = $this->joke_with_tag_model->getList($id);
        $tags = [];
        foreach($tagData as $v){
            $tags[] = $v['tag_id'];
        }
        $info['tags'] = $tags;
        return $info;
    }

    private function create()
    {
        $question = $_POST['question'];
        $answer = $_POST['answer'];
        $inspiration = $_POST['inspiration'];
        $category = intval($_POST['category']);
        $tag = empty($_POST['tag'])?'':array_map('intval',explode(',',$_POST['tag']));
        $status = intval($_POST['status']);
        $image = $_FILES['image'];
        $allowFileTypes = ['image/png','image/jpg','image/jpeg','image/gif'];
        $imageSourceString = '';
        $editor = intval($this->data['account']['id']);
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
            $targetDirectory = $_SERVER['DOCUMENT_ROOT'].'/upload/joke/';
            $targetFileName = ($maxId+1).'-'. basename($image['name']);
            $targetFile = $targetDirectory .$targetFileName;
            if(move_uploaded_file($image['tmp_name'],$targetFile)){
              $imageSourceString = '/upload/joke/'.$targetFileName;
            }
        }

        $result = $this->joke_model->create($question,$answer,$inspiration,$category,$status,$imageSourceString,$editor);
        if ($result['errCode'] === SUCCESS) {
            if(!empty($tag)){
                $res = '';
                foreach($tag as $v){
                    $res = $this->joke_with_tag_model->create($result['id'],$v);
                }
                if($res===SERVER_INTERNAL_ERROR){
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
        $checkExist = $this->joke_model->get($id);
        if (empty($checkExist)) {
            $response = json_encode(['errCode' => CURSE_CATEGORY_NOT_EXIST]);
            echo $response;
            exit;
        }
        $question = $_POST['question'];
        $answer = $_POST['answer'];
        $inspiration = $_POST['inspiration'];
        $category = intval($_POST['category']);
        $tag = empty($_POST['tag'])?'':array_map('intval',explode(',',$_POST['tag']));
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
            
            $targetDirectory = $_SERVER['DOCUMENT_ROOT'].'/upload/joke/';
            $targetFileName = $id.'-'. basename($image['name']);
            $targetFile = $targetDirectory .$targetFileName;
            if(move_uploaded_file($image['tmp_name'],$targetFile)){
              $imageSourceString = '/upload/joke/'.$targetFileName;
            }
        }
        $deleteTagResult = $this->joke_with_tag_model->delete($id);
        if($deleteTagResult===SERVER_INTERNAL_ERROR){
            $response = json_encode(['errCode' => SERVER_INTERNAL_ERROR]);
            echo $response;
            exit;
        }
        if(!empty($tag)){
            $createTagResult = '';
            foreach($tag as $v){
                $createTagResult = $this->joke_with_tag_model->create($id,$v);
            }
            if($createTagResult === SERVER_INTERNAL_ERROR){
                 $response = json_encode(['errCode' => SERVER_INTERNAL_ERROR]);
                echo $response;
                exit;
            }
        }
        $result = $this->joke_model->edit($id,$question,$answer,$inspiration,$category,$status,$imageSourceString);
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
        $checkExist = $this->joke_model->get($id);
        if (empty($checkExist)) {
            $response = json_encode(['errCode' => CURSE_CATEGORY_NOT_EXIST]);
            echo $response;
            exit;
        }
        if(!empty($checkExist['image'])&&file_exists($_SERVER['DOCUMENT_ROOT'].$checkExist['image'])){
            unlink($_SERVER['DOCUMENT_ROOT'].$checkExist['image']);
        }
        $deleteTagResult = $this->joke_with_tag_model->delete($id);
        if($deleteTagResult===SERVER_INTERNAL_ERROR){
            $response = json_encode(['errCode' => SERVER_INTERNAL_ERROR]);
            echo $response;
            exit;
        }
        $result = $this->joke_model->delete($id);
        if($result===SUCCESS){
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
        $heading = ['id', '問題','回答','靈感','類別','標籤','平均評分', '狀態', '建立者', '建立時間', '更新時間'];
        $list = $this->joke_model->getExportList();
        switch ($format) {
            case 1:
                header('Content-Type: text/csv; charset=utf-8');
                header('Content-Disposition: attachment; filename=joke.csv');
                $csv = fopen('php://output', 'w+');
                fputcsv($csv, $heading);
                foreach ($list as  $value) {
                    $status = $value['status'] == ACTIVE ? '啟用' : '停用';
                    $createTime = date('Y-m-d', $value['createtime']);
                    $updateTime = date('Y-m-d', $value['updatetime']);
                    $tmp = [$value['id'],$value['question'],$value['answer'],$value['inspiration'],$value['category_name'],$value['tags'],$value['avg_score'], $status, $value['editor_name'], $createTime, $updateTime];
                    fputcsv($csv, $tmp);
                }
                rewind($csv);
                fclose($csv);
                break;
            case 2:
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition:attachment;filename="joke.xlsx"');
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
                $activeWorksheet->setCellValue('J' . $cellNumber, $heading[9]);
                $activeWorksheet->setCellValue('K' . $cellNumber, $heading[10]);
                $cellNumber += 1;
                foreach ($list as $d) {
                    $activeWorksheet->setCellValue('A' . $cellNumber, $d['id']);
                    $activeWorksheet->setCellValue('B' . $cellNumber, $d['question']);
                    $activeWorksheet->setCellValue('C' . $cellNumber, $d['answer']);
                    $activeWorksheet->setCellValue('D' . $cellNumber, $d['inspiration'] );
                    $activeWorksheet->setCellValue('E' . $cellNumber, $d['category_name'] );
                    $activeWorksheet->setCellValue('F' . $cellNumber,  $d['tags'] );
                    $activeWorksheet->setCellValue('G' . $cellNumber,  $d['avg_score']);
                    $activeWorksheet->setCellValue('H' . $cellNumber, $d['status'] == ACTIVE ? '啟用' : '停用');
                    $activeWorksheet->setCellValue('I' . $cellNumber, $d['editor_name']);
                    $activeWorksheet->setCellValue('J' . $cellNumber, date('Y-m-d', $d['createtime']));
                    $activeWorksheet->setCellValue('K' . $cellNumber, date('Y-m-d', $d['updatetime']));
                    $cellNumber += 1;
                }
                $writer = new Xlsx($spreadsheet);
                $writer->save("php://output");
                break;
        }
    }

    private function checkMaxId(){
        $id = $this->joke_model->getMaxId();
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

$joke = new Joke(new Joke_model($db),new Joke_with_tag_model($db),new Account_model($db));

if (in_array('export', $query_array)) {
    $joke->export($query_array[1]);
    exit;
}

$joke->requestEntry();

unset($joke);
