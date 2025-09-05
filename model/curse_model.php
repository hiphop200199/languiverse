<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/database/db.php';
class Curse_model
{
    private $db;
    public function __construct(Db $db)
    {
        $this->db = $db;
    }
    public function getList()
    {
        $sql = 'SELECT * FROM  curse';
        $stmt =  $this->db->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function get($id)
    {
        $sql = 'SELECT * FROM curse WHERE id = ? ';
        $stmt =  $this->db->conn->prepare($sql);
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    public function create($content,  $status,  $editor)
    {
        $sql = 'INSERT INTO curse VALUES( ?,?,?,?,?,? ) ';
        $stmt =  $this->db->conn->prepare($sql);
        $stmt->execute([null, $content, $status, $editor, time(), time()]);
        if ($stmt->rowCount() == 1) {
            $id = intval($this->db->conn->lastInsertId());
            $result = ['errCode' => SUCCESS, 'id' => $id];
            return $result;
        }
        $result = SERVER_INTERNAL_ERROR;
        return $result;
    }

    public function edit($id, $content,  $status)
    {
        $sql = 'UPDATE curse SET content = ?,status = ?,updatetime = ? WHERE id = ?';
        $stmt =  $this->db->conn->prepare($sql);
        $stmt->execute([$content,  $status,  time(), $id]);
        if ($stmt->rowCount() == 1) {
            $result = SUCCESS;
            return $result;
        }
        $result = SERVER_INTERNAL_ERROR;
        return $result;
    }

    public function delete($id)
    {
        $sql = 'DELETE FROM curse WHERE id = ? ';
        $stmt =  $this->db->conn->prepare($sql);
        $stmt->execute([$id]);
        if ($stmt->rowCount() == 1) {
            $result = SUCCESS;
            return $result;
        }
        $result = SERVER_INTERNAL_ERROR;
        return $result;
    }


    public function getExportList()
    {
        $mainSql = "SELECT c.*,a.name AS editor_name FROM curse AS c LEFT JOIN admin_account AS a ON c.editor = a.id";
        $strategySql = 'SELECT * FROM curse_strategy WHERE curse_id = ?';
        $stmt =  $this->db->conn->prepare($mainSql);
        $strategyStmt = $this->db->conn->prepare($strategySql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        for ($i = 0; $i < count($result); $i++) {
            $strategyStmt->execute([$result[$i]['id']]);
            $strategyResult = $strategyStmt->fetchAll(PDO::FETCH_ASSOC);
            $result[$i]['strategies'] = '';
            if (!empty($strategyResult)) {
                $strategies = [];
                foreach ($strategyResult as $str) {
                    $strategies[] = $str['content'];
                }
                $result[$i]['strategies'] = implode(',', $strategies);
            }
            $strategyStmt->closeCursor();
        }

        return $result;
    }

    //web


    public function getListFrontend()
    {
        $sql = 'SELECT * FROM curse WHERE status = 2 ';
        $sql .= " ORDER BY createtime DESC ";
        $stmt =  $this->db->conn->prepare($sql);
        $stmt->execute();

        if ($stmt->rowCount() >= 0) {
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        }
        $result = SERVER_INTERNAL_ERROR;
        return $result;
    }


    public function getFrontend($id)
    {
        $mainSql = 'SELECT c.*,a.name AS editor_name  FROM curse AS c
        JOIN admin_account AS a ON c.editor = a.id
        WHERE c.id = ? AND c.status = 2
        ';
        $strategySql = 'SELECT * FROM curse_strategy WHERE curse_id = ?';

        $mainStmt =  $this->db->conn->prepare($mainSql);
        $strategyStmt = $this->db->conn->prepare($strategySql);
        $mainStmt->execute([$id]);
        if ($mainStmt->rowCount() == 1) {
            $mainInfo = $mainStmt->fetch(PDO::FETCH_ASSOC);
            $mainStmt->closeCursor();
            $strategyStmt->execute([$id]);
            if ($strategyStmt->rowCount() < 0) {
                $result = SERVER_INTERNAL_ERROR;
                return $result;
            }
            $mainInfo['strategyInfo'] = $strategyStmt->fetchAll(PDO::FETCH_ASSOC);
            $strategyStmt->closeCursor();
            return $mainInfo;
        }
        $result = SERVER_INTERNAL_ERROR;
        return $result;
    }


    public function createStrategy($id, $content)
    {
        $sql = 'INSERT INTO curse_strategy VALUES(?,?,?,?)';
        $stmt =  $this->db->conn->prepare($sql);
        $stmt->execute([null, $id, $content, time()]);
        if ($stmt->rowCount() == 1) {
            $result = SUCCESS;
            return $result;
        }
        $result = SERVER_INTERNAL_ERROR;
        return $result;
    }
}
