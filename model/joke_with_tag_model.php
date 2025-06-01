<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/database/db.php';
class Joke_with_tag_model
{
    private $db;
    public function __construct(Db $db)
    {
        $this->db = $db;
    }
    public function getList($jokeId) {
        $sql = 'SELECT * FROM joke_with_tag WHERE joke_id = ?';
        $stmt =  $this->db->conn->prepare($sql);
        $stmt->execute([$jokeId]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }


    public function create($jokeId,$tagId)
    {
        $sql = 'INSERT INTO joke_with_tag VALUES( ?,?,?,? ) ';
        $stmt =  $this->db->conn->prepare($sql);
        $stmt->execute([null,$jokeId,$tagId,time()]);
        if ($stmt->rowCount() == 1) {
            $result = SUCCESS;
            return $result;
        }
        $result = SERVER_INTERNAL_ERROR;
        return $result;
    }


    public function delete($jokeId) {
        $sql = 'DELETE FROM joke_with_tag WHERE joke_id = ? ';
        $stmt =  $this->db->conn->prepare($sql);
        $stmt->execute([$jokeId]);
        if ($stmt->rowCount() >=0) {
            $result = SUCCESS;
            return $result;
        }
        $result = SERVER_INTERNAL_ERROR;
        return $result;
    }
}
