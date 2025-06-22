<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/config/constant.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/model/joke_model.php';

class JokeFrontend{
    private $joke_model;

    public function __construct(Joke_model $joke_model)
    {
        $this->joke_model = $joke_model;
    }

     public function requestEntry()
    {
        $request_body = file_get_contents('php://input');
    
        $data = json_decode($request_body, true);
      
        switch ($data['task']) {
            case 'create-rate':
                $this->createRate($data);
                break;
        } 
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

    private function createRate($data)
    {
        $id = intval($data['id']);
        $score = intval($data['score']);
        $comment = $data['comment'];
        $result = $this->joke_model->createRate($id,$comment,$score);
        if($result === SUCCESS){
            $response = json_encode(['errCode'=>SUCCESS]);
            echo $response;
            exit;
        }else{
             $response = json_encode(['errCode'=>SERVER_INTERNAL_ERROR]);
            echo $response;
            exit;
        }
    }

}

$joke  = new JokeFrontend(new Joke_model(new Db()));

$joke->requestEntry();

unset($joke);