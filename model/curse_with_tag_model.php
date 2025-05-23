<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/database/db.php';
class Curse_with_tag_model
{
    private $db;
    public function __construct(Db $db)
    {
        $this->db = $db;
    }
    public function getList($curseId) {
        $sql = 'SELECT * FROM curse_with_tag WHERE curse_id = ?';
        $stmt =  $this->db->conn->prepare($sql);
        $stmt->execute([$curseId]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }


    public function create($curseId,$tagId)
    {
        $sql = 'INSERT INTO curse_with_tag VALUES( ?,?,?,? ) ';
        $stmt =  $this->db->conn->prepare($sql);
        $stmt->execute([null,$curseId,$tagId,time()]);
        if ($stmt->rowCount() == 1) {
            $result = SUCCESS;
            return $result;
        }else{
            $result = SERVER_INTERNAL_ERROR;
            return $result;
        }
    }


    public function delete($curseId) {
        $sql = 'DELETE FROM curse_with_tag WHERE curse_id = ? ';
        $stmt =  $this->db->conn->prepare($sql);
        $stmt->execute([$curseId]);
        if ($stmt->rowCount() >=0) {
            $result = SUCCESS;
            return $result;
        }else{
            $result = SERVER_INTERNAL_ERROR;
            return $result;
        }
    }
}
