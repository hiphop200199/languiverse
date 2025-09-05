<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/database/db.php';

class Curse_strategy_model
{
    private $db;

    public function __construct(Db $db)
    {
        $this->db = $db;
    }


    public function delete($curseId)
    {
        $sql = 'DELETE FROM curse_strategy WHERE  curse_id = ?';
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
