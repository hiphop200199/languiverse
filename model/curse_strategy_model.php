<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/database/db.php';

class Curse_strategy_model
{
    private $db;

    public function __construct(Db $db)
    {
        $this->db = $db;
    }

    public function getList($curseId)
    {
        $sql = 'SELECT * FROM curse_strategy WHERE from_frontend = 1 AND curse_id = ?';
        $stmt = $this->db->conn->prepare($sql);
        $stmt->execute([$curseId]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function create($curseId, $content)
    {
        $sql = 'INSERT INTO curse_strategy VALUES(?,?,?,?,?)';
        $stmt = $this->db->conn->prepare($sql);
        $stmt->execute([null, $curseId, $content,1, time()]);
        if ($stmt->rowCount() == 1) {
            $result = SUCCESS;
            return $result;
        }
        $result = SERVER_INTERNAL_ERROR;
        return $result;
    }

    public function delete($curseId)
    {
        $sql = 'DELETE FROM curse_strategy WHERE from_frontend = 1 AND curse_id = ?';
        $stmt = $this->db->conn->prepare($sql);
        $stmt->execute([$curseId]);
        if ($stmt->rowCount() >= 0) {
            $result = SUCCESS;
            return $result;
        }
        $result = SERVER_INTERNAL_ERROR;
        return $result;
    }
}
