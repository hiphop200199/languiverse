<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/controller/admin/common.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/config/constant.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/model/curse_model.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/model/curse_strategy_model.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/model/curse_with_tag_model.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Curse extends Common{
    private $curse_model,$curse_strategy_model,$curse_with_tag_model;
    public function __construct(Curse_model $curse_model,Curse_with_tag_model $curse_with_tag_model,Curse_strategy_model $curse_strategy_model,Account_model $account_model){
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
        foreach($tagData as $v){
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
        $tag = empty($_POST['tag'])?'':array_map('intval',explode(',',$_POST['tag']));
        $strategy = empty($_POST['strategy'])?'':json_decode($_POST['strategy'],true);
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
            $targetDirectory = $_SERVER['DOCUMENT_ROOT'].'/upload/curse/';
            $targetFileName = ($maxId+1).'-'. basename($image['name']);
            $targetFile = $targetDirectory .$targetFileName;
            if(move_uploaded_file($image['tmp_name'],$targetFile)){
              $imageSourceString = '/upload/curse/'.$targetFileName;
            }
        }
       
        $result = $this->curse_model->create($content,$category,$status,$imageSourceString,$editor);
       
        if ($result['errCode'] === SUCCESS) {
            if(!empty($tag)){
                $res = '';
                foreach($tag as $v){
                    $res = $this->curse_with_tag_model->create($result['id'],$v);
                }
                if($res===SUCCESS){
                  
                    if(!empty($strategy)){
                        $createStrategyResult = '';
                        foreach($strategy as $ks=>$vs){
                            $strategyImageSourceString = '';
                            if(!empty($_FILES['strategy-image-'.$vs['num']])){
                                if($_FILES['strategy-image'.$vs['num']]['size'] > 1 * 1024 * 1024){
                                    $response = json_encode(['errCode'=>FILE_OVERSIZE]);
                                    echo $response;
                                    exit;
                                }
                    
                                if(!in_array($_FILES['strategy-image'.$vs['num']]['type'],$allowFileTypes)){
                                    $response = json_encode(['errCode'=>FILE_FORMAT_ERROR]);
                                    echo $response;
                                    exit;
                                }
                                $targetDirectory = $_SERVER['DOCUMENT_ROOT'].'/upload/curse/';
                                $targetFileName = ($result['id']).'-strategy-'.$vs['num'].'-'. basename($image['name']);
                                $targetFile = $targetDirectory .$targetFileName;
                                if(move_uploaded_file($_FILES['strategy-image'.$vs['num']]['tmp_name'],$targetFile)){
                                  $strategyImageSourceString = '/upload/curse/'.$targetFileName;
                                }
                            }
                            $createStrategyResult = $this->curse_strategy_model->create($result['id'],$vs['content'],$strategyImageSourceString);
                        }
                        if($createStrategyResult===SUCCESS){
                            $response = json_encode(['errCode' => SUCCESS, 'redirect' => 'list.php']);
                            echo $response;
                            exit;
                        }else{
                            $response = json_encode(['errCode' => SERVER_INTERNAL_ERROR]);
                            echo $response;
                            exit;
                        }
                    }
                     $response = json_encode(['errCode' => SUCCESS, 'redirect' => 'list.php']);
                    echo $response;
                    exit; 
                }else{
                    $response = json_encode(['errCode' => SERVER_INTERNAL_ERROR]);
                    echo $response;
                    exit;
                }
            }
            if(!empty($strategy)){
                $createStrategyResult = '';
                foreach($strategy as $ks=>$vs){
                    $strategyImageSourceString = '';
                    if(!empty($_FILES['strategy-image'.$vs['num']])){
                        if($_FILES['strategy-image'.$vs['num']]['size'] > 1 * 1024 * 1024){
                            $response = json_encode(['errCode'=>FILE_OVERSIZE]);
                            echo $response;
                            exit;
                        }
            
                        if(!in_array($_FILES['strategy-image'.$vs['num']]['type'],$allowFileTypes)){
                            $response = json_encode(['errCode'=>FILE_FORMAT_ERROR]);
                            echo $response;
                            exit;
                        }
                        $targetDirectory = $_SERVER['DOCUMENT_ROOT'].'/upload/curse/';
                        $targetFileName = ($result['id']).'-strategy-'.$vs['num'].'-'. basename($image['name']);
                        $targetFile = $targetDirectory .$targetFileName;
                        if(move_uploaded_file($_FILES['strategy-image'.$vs['num']]['tmp_name'],$targetFile)){
                          $strategyImageSourceString = '/upload/curse/'.$targetFileName;
                        }
                    }
                    $createStrategyResult = $this->curse_strategy_model->create($result['id'],$vs['content'],$strategyImageSourceString);
                }
                if($createStrategyResult===SUCCESS){
                    
                    $response = json_encode(['errCode' => SUCCESS, 'redirect' => 'list.php']);
                    echo $response;
                    exit;
                }else{
                    $response = json_encode(['errCode' => SERVER_INTERNAL_ERROR]);
                    echo $response;
                    exit;
                }
            }
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
        $checkExist = $this->curse_model->get($id);
        if (empty($checkExist)) {
            $response = json_encode(['errCode' => CURSE_CATEGORY_NOT_EXIST]);
            echo $response;
            exit;
        }
        $content = $_POST['content'];
        $category = intval($_POST['category']);
        $tag = empty($_POST['tag'])?'':array_map('intval',explode(',',$_POST['tag']));
        $strategy = empty($_POST['strategy'])?'':json_decode($_POST['strategy'],true);
        $status = intval($_POST['status']);
        $image = empty($_FILES['image'])?'':$_FILES['image'];
        $allowFileTypes = ['image/png','image/jpg','image/jpeg','image/gif'];
        $oldImage = empty($checkExist['image'])?'':$checkExist['image'];
        $imageSourceString = empty($image)?$oldImage:'';
        
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
            
            $targetDirectory = $_SERVER['DOCUMENT_ROOT'].'/upload/curse/';
            $targetFileName = $id.'-'. basename($image['name']);
            $targetFile = $targetDirectory .$targetFileName;
            if(move_uploaded_file($image['tmp_name'],$targetFile)){
              $imageSourceString = '/upload/curse/'.$targetFileName;
            }
        }
        $deleteTagResult = $this->curse_with_tag_model->delete($id);
        if($deleteTagResult===SUCCESS){
            $deleteStrategyResult = $this->curse_strategy_model->delete($id);
            if($deleteStrategyResult===SUCCESS){
                if(!empty($tag)){
                    $res = '';
                    foreach($tag as $v){
                        $res = $this->curse_with_tag_model->create($id,$v);
                    }
                    if($res===SUCCESS){
                      
                        if(!empty($strategy)){
                            $createStrategyResult = '';
                            foreach($strategy as $ks=>$vs){
                                $strategyImageSourceString = '';
                                if(!empty($_FILES['strategy-image-'.$vs['num']])){
                                    if($_FILES['strategy-image'.$vs['num']]['size'] > 1 * 1024 * 1024){
                                        $response = json_encode(['errCode'=>FILE_OVERSIZE]);
                                        echo $response;
                                        exit;
                                    }
                        
                                    if(!in_array($_FILES['strategy-image'.$vs['num']]['type'],$allowFileTypes)){
                                        $response = json_encode(['errCode'=>FILE_FORMAT_ERROR]);
                                        echo $response;
                                        exit;
                                    }
                                    $targetDirectory = $_SERVER['DOCUMENT_ROOT'].'/upload/curse/';
                                    $targetFileName = ($id).'-strategy-'.$vs['num'].'-'. basename($image['name']);
                                    $targetFile = $targetDirectory .$targetFileName;
                                    if(move_uploaded_file($_FILES['strategy-image'.$vs['num']]['tmp_name'],$targetFile)){
                                      $strategyImageSourceString = '/upload/curse/'.$targetFileName;
                                    }
                                }
                                $createStrategyResult = $this->curse_strategy_model->create($id,$vs['content'],$strategyImageSourceString);
                            }
                            if($createStrategyResult===SUCCESS){
                                $result = $this->curse_model->edit($id,$content,$category,$status,$imageSourceString);
                                if($result === SUCCESS){
                                    $response = json_encode(['errCode'=>SUCCESS,'redirect'=>'list.php']);
                                    echo $response;
                                    exit;
                                }else{
                                    $response = json_encode(['errCode' => SERVER_INTERNAL_ERROR]);
                                    echo $response;
                                    exit;
                                }
                            }else{
                                $response = json_encode(['errCode' => SERVER_INTERNAL_ERROR]);
                                echo $response;
                                exit;
                            }
                        }
                        $result = $this->curse_model->edit($id,$content,$category,$status,$imageSourceString);
                        if($result === SUCCESS){
                            $response = json_encode(['errCode' => SUCCESS, 'redirect' => 'list.php']);
                            echo $response;
                            exit;
                        }else{
                            $response = json_encode(['errCode' => SERVER_INTERNAL_ERROR]);
                            echo $response;
                            exit;
                        }
                    }else{
                        $response = json_encode(['errCode' => SERVER_INTERNAL_ERROR]);
                        echo $response;
                        exit;
                    }
                }
                if(!empty($strategy)){
                    $createStrategyResult = '';
                    foreach($strategy as $ks=>$vs){
                        $strategyImageSourceString = '';
                        if(!empty($_FILES['strategy-image'.$vs['num']])){
                            if($_FILES['strategy-image'.$vs['num']]['size'] > 1 * 1024 * 1024){
                                $response = json_encode(['errCode'=>FILE_OVERSIZE]);
                                echo $response;
                                exit;
                            }
                
                            if(!in_array($_FILES['strategy-image'.$vs['num']]['type'],$allowFileTypes)){
                                $response = json_encode(['errCode'=>FILE_FORMAT_ERROR]);
                                echo $response;
                                exit;
                            }
                            $targetDirectory = $_SERVER['DOCUMENT_ROOT'].'/upload/curse/';
                            $targetFileName = ($id).'-strategy-'.$vs['num'].'-'. basename($image['name']);
                            $targetFile = $targetDirectory .$targetFileName;
                            if(move_uploaded_file($_FILES['strategy-image'.$vs['num']]['tmp_name'],$targetFile)){
                              $strategyImageSourceString = '/upload/curse/'.$targetFileName;
                            }
                        }
                        $createStrategyResult = $this->curse_strategy_model->create($id,$vs['content'],$strategyImageSourceString);
                    }
                    if($createStrategyResult===SUCCESS){
                        $result = $this->curse_model->edit($id,$content,$category,$status,$imageSourceString);
                        if($result === SUCCESS){
                            $response = json_encode(['errCode' => SUCCESS, 'redirect' => 'list.php']);
                            echo $response;
                            exit;
                        }else{
                            $response = json_encode(['errCode' => SERVER_INTERNAL_ERROR]);
                            echo $response;
                            exit;
                        }
                    }else{
                        $response = json_encode(['errCode' => SERVER_INTERNAL_ERROR]);
                        echo $response;
                        exit;
                    }
                }
                $result = $this->curse_model->edit($id,$content,$category,$status,$imageSourceString);
                if($result === SUCCESS){
                    $response = json_encode(['errCode' => SUCCESS, 'redirect' => 'list.php']);
                    echo $response;
                    exit;
                }else{
                    $response = json_encode(['errCode' => SERVER_INTERNAL_ERROR]);
                    echo $response;
                    exit;
                }
            }else{
                $response = json_encode(['errCode' => SERVER_INTERNAL_ERROR]);
                echo $response;
                exit;
            }
               
        }else{
            $response = json_encode(['errCode' => SERVER_INTERNAL_ERROR]);
            echo $response;
            exit;
        }
    }

    private function delete()
    {
        $id = intval($_POST['id']);
        $deleteTagResult = $this->curse_with_tag_model->delete($id);
        if($deleteTagResult===SUCCESS){
            $deleteStrategyResult = $this->curse_strategy_model->delete($id);
            if($deleteStrategyResult===SUCCESS){
                $result = $this->curse_model->delete($id);
                if($result===SUCCESS){
                    $response = json_encode(['errCode' => SUCCESS, 'redirect' => 'list.php']);
                    echo $response;
                    exit;
                }else{
                    $response = json_encode(['errCode' => SERVER_INTERNAL_ERROR]);
                    echo $response;
                    exit;
                }
            }else{
                $response = json_encode(['errCode' => SERVER_INTERNAL_ERROR]);
                echo $response;
                exit;
            }
            
        }else{
            $response = json_encode(['errCode' => SERVER_INTERNAL_ERROR]);
            echo $response;
            exit;
        }
    }

    private function export()
    {
        $format = intval($data['format']);
        $list = $this->joke_model->getList();
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

    private function checkMaxId(){
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

$curse = new Curse(new Curse_model($db),new Curse_with_tag_model($db),new Curse_strategy_model($db),new Account_model($db));

if (in_array('export', $query_array)) {
    $curse->export($query_array[1]);
    exit;
}

$curse->requestEntry();

unset($curse);