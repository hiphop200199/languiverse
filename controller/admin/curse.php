<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/controller/admin/common.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/config/constant.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/model/curse_model.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/model/curse_strategy_model.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/util/util.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Curse extends Common
{
    private $curse_model, $curse_strategy_model;
    public function __construct(Curse_model $curse_model, Curse_strategy_model $curse_strategy_model, Account_model $account_model)
    {
        parent::__construct($account_model);
        parent::init();
        $this->curse_model = $curse_model;
        $this->curse_strategy_model = $curse_strategy_model;
    }


    public function index()
    {
        $list = $this->curse_model->getList();
        return $list;
    }

    public function get($id)
    {
        $info = $this->curse_model->get($id);
        return $info;
    }

    private function create()
    {
        $content = htmlspecialchars(strip_tags($_POST['content']));
        $status = intval($_POST['status']);
        $editor = intval($this->data['account']['id']);
        $result = $this->curse_model->create($content,  $status,  $editor);

        if ($result['errCode'] === SUCCESS) {
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
        $content = htmlspecialchars(strip_tags($_POST['content']));
        $status = intval($_POST['status']);
        $result = $this->curse_model->edit($id, $content,  $status);
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
        $heading = ['id', '內容', '策略', '狀態', '建立者', '建立時間', '更新時間'];
        $list = $this->curse_model->getExportList();
        switch ($format) {
            case 1:
                header('Content-Type: text/csv; charset=utf-8');
                header('Content-Disposition: attachment; filename=curse.csv');
                $csv = fopen('php://output', 'w+');
                fputcsv($csv, $heading);
                foreach ($list as  $value) {
                    $status = $value['status'] == ACTIVE ? '啟用' : '停用';
                    $createTime = date('Y-m-d', $value['createtime']);
                    $updateTime = date('Y-m-d', $value['updatetime']);
                    $tmp = [$value['id'], $value['content'], $value['strategies'], $status, $value['editor_name'], $createTime, $updateTime];
                    fputcsv($csv, $tmp);
                }
                rewind($csv);
                fclose($csv);
                break;
            case 2:
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
                $cellNumber += 1;
                foreach ($list as $d) {
                    $activeWorksheet->setCellValue('A' . $cellNumber, $d['id']);
                    $activeWorksheet->setCellValue('B' . $cellNumber, $d['content']);
                    $activeWorksheet->setCellValue('C' . $cellNumber, $d['strategies']);
                    $activeWorksheet->setCellValue('D' . $cellNumber, $d['status'] == ACTIVE ? '啟用' : '停用');
                    $activeWorksheet->setCellValue('E' . $cellNumber, $d['editor_name']);
                    $activeWorksheet->setCellValue('F' . $cellNumber, date('Y-m-d', $d['createtime']));
                    $activeWorksheet->setCellValue('G' . $cellNumber,  date('Y-m-d', $d['updatetime']));
                    $cellNumber += 1;
                }
                $writer = new Xlsx($spreadsheet);
                $writer->save("php://output");
                break;
        }
    }

}


$query_array = Util::getSearchQuery();

$query_array = Util::getSearchQueryValue($query_array);

$db = new Db();

$curse = new Curse(new Curse_model($db),  new Curse_strategy_model($db), new Account_model($db));

if (in_array('export', $query_array)) {
    $curse->export($query_array[1]);
    exit;
}

Util::requestEntry($curse);

unset($curse);
