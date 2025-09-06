<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/config/constant.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/model/joke_model.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/util/util.php';

use Mpdf\Mpdf;

class JokeFrontend{
    private $joke_model;

    public function __construct(Joke_model $joke_model)
    {
        $this->joke_model = $joke_model;
    }

    
    public function randomJoke()
    {
        $info = $this->joke_model->getRandomJoke();
        return $info;
    }

    public function jokeEvaluation()
    {
        $list = $this->joke_model->getJokeEvaluation();
        return $list;
    }

    public function getList(array $queryArray)
    {
        //$keyword = substr($queryArray[0], strpos($queryArray[0], '=') + 1);
        $category = substr($queryArray[0], strpos($queryArray[0], '=') + 1);
        $editor = substr($queryArray[1], strpos($queryArray[1], '=') + 1);

        $list = $this->joke_model->getListFrontend(null,$category,$editor);
        return $list;
    }

    public function get($id)
    {
        $info = $this->joke_model->getFrontend($id);
        return $info;
    }

    private function createRate()
    {
        $id = intval($_POST['id']);
        $score = intval($_POST['score']);
        $comment = $_POST['comment'];
        $result = $this->joke_model->createRate($id,$comment,$score);
        if($result === SUCCESS){
            $response = json_encode(['errCode'=>SUCCESS]);
            echo $response;
            exit;
        }
        $response = json_encode(['errCode'=>SERVER_INTERNAL_ERROR]);
        echo $response;
        exit;
    }

    private function getRandomQuestion()
    {
        $list = $this->joke_model->getRandomQuestion();
        echo json_encode(['errCode'=>SUCCESS,'list'=>$list]);
        exit;
    }

    public function exportPdf($score)
    {
        
        $date = date('d-M-Y');
        $html = '<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
   <style>
    *{
      padding: 0;
      margin: 0;
      box-sizing: border-box;
      overflow: hidden;
    }
    #pdf{
      position: relative;
      width: 100%;
      height: 100vh;
      border: 50px solid #365486;
    }
    #title{
      font-size: 50px;
      font-style: italic;
      text-align: center;
      margin-top: 50px;
    }
    #second-title{
      font-size: 32px;
      text-align: center;
      margin-top: 50px;
    }
    h3{
      font-size: 28px;
      text-align: center;
    }
    h4{
      font-size: 24px;
      font-style: italic;
      text-align: center;
    }
    h5{
      font-size: 20px;
      text-align: center;
      margin-block: 10px;
    }
    p{
      font-size: 14px;
      font-style: italic;
      text-align: center;
      margin-block: 10px;
    }
    p.date{
      font-style: normal;
      margin-bottom:30px;
    }
    span{
      text-align: center;
    }
   
   </style>
    <title>Document</title>
  </head>
  <body>
  <div id="pdf">
    <h1 id="title">TOBJE</h1>
    <h2 id="second-title">CERTIFICATE OF ACHIEVEMENT</h2>
    <p>This is to certify that</p>
    <h3>YOU</h3>
    <p>achieved the following score on the</p>
    <h4>TOBJE Listening & Reading Test</h4>
    <h5>'.$score.'</h5>
    <p class="date">date : '.$date.'</p>
    <p class="date">Eric Tsai</p>
    <p class="date">Executive Director</p>
    <p class="date">Global TOBJE Management</p>
</div>
  </body>
</html>
';

        $mpdf = new Mpdf(['orientation' => 'L','margin_left'=>0,'margin_right'=>0,'margin_top'=>0,'margin_bottom'=>0]);
        $mpdf->WriteHTML($html);

        $mpdf->Output('certificate.pdf',\Mpdf\Output\Destination::DOWNLOAD);
    }

}


$query_array = Util::getSearchQuery();

$query_array = Util::getSearchQueryValue($query_array);


$joke  = new JokeFrontend(new Joke_model(new Db()));

if (in_array('export', $query_array)) {
    $joke->exportPdf($query_array[1]);
    exit;
}

Util::requestEntry($joke);

unset($joke);