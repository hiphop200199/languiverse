<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/database/db.php';
class Touching_model
{

    private $db;
    public function __construct(Db $db)
    {
        $this->db = $db;
    }

    public function getList()
    {
        $sql = 'SELECT * FROM touching';
        $stmt =  $this->db->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function get($id)
    {
        $sql = 'SELECT t.*,ts.name AS source FROM touching AS t JOIN touching_source AS ts ON t.source_id = ts.id WHERE t.id = ? ';
        $stmt =  $this->db->conn->prepare($sql);
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    public function create($content, $sourceId,$link, $image, $status, $editor)
    {
        $sql = 'INSERT INTO touching VALUES( ?,?,?,?,?,?,?,?,? ) ';
        $stmt =  $this->db->conn->prepare($sql);
        $stmt->execute([null, $content, $sourceId,$link, $image, $status, $editor, time(), time()]);
        if ($stmt->rowCount() == 1) {
            $result = SUCCESS;
            return $result;
        }
        $result = SERVER_INTERNAL_ERROR;
        return $result;
    }

    public function edit($id, $content, $sourceId,$link, $image, $status)
    {
        $sql = 'UPDATE touching SET content = ?,source = ?,link = ?,image=?,status = ?,updatetime = ? WHERE id = ?';
        $stmt =  $this->db->conn->prepare($sql);
        $stmt->execute([$content, $sourceId,$link, $image, $status, time(), $id]);
        if ($stmt->rowCount() == 1) {
            $result = SUCCESS;
            return $result;
        }
        $result = SERVER_INTERNAL_ERROR;
        return $result;
    }

    public function delete($id)
    {
        $sql = 'DELETE FROM touching WHERE id = ?';
        $stmt =  $this->db->conn->prepare($sql);
        $stmt->execute([$id]);
        if ($stmt->rowCount() == 1) {
            $result = SUCCESS;
            return $result;
        }
        $result = SERVER_INTERNAL_ERROR;
        return $result;
    }

    public function getMaxId()
    {
        $sql = 'SELECT id FROM touching ORDER BY id DESC LIMIT 1 ';
        $stmt =  $this->db->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if (empty($result)) {
            return 0;
        }
        return intval($result['id']);
    }

    public function getExportList()
    {
        $sql = "SELECT t.*,ts.name AS source,a.name AS editor_name FROM touching AS t
        JOIN touching_source AS ts ON t.source_id = ts.id
        JOIN admin_account AS a ON t.editor = a.id
        ";
        $subSql = "SELECT * FROM touching_thought WHERE touching_id = ?";
        $stmt =  $this->db->conn->prepare($sql);
        $subStmt = $this->db->conn->prepare($subSql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        for ($i = 0; $i < count($result); $i++) {
            $subStmt->execute([$result[$i]['id']]);
            $subResult = $subStmt->fetchAll(PDO::FETCH_ASSOC);
            $result[$i]['thoughts'] = '';
            if (!empty($subResult)) {
                $thoughts = [];
                foreach ($subResult as $sub) {
                    $thoughts[] = $sub['thought'];
                }
                $result[$i]['thoughts'] = implode(',', $thoughts);
            }
            $subStmt->closeCursor();
        }
        return $result;
    }

    //web

    public function getListFrontend($sourceId)
    {
         if (empty($sourceId)) {
            $result = [];
            return $result;
        }

        $sql = 'SELECT t.*,ts.name AS source FROM touching AS t
         JOIN touching_source AS ts ON t.source_id = ts.id WHERE t.status = 2
         AND ts.id = ? ';
        $params = [$sourceId];

        $sql .= " ORDER BY t.createtime DESC ";
        $stmt =  $this->db->conn->prepare($sql);
        $stmt->execute($params);

        if ($stmt->rowCount() >= 0) {
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } 
        $result = SERVER_INTERNAL_ERROR;
        return $result;
    }

    public function getFrontend($id)
    {
        $sql = 'SELECT t.*,ts.name AS source,a.name FROM touching AS t
            JOIN touching_source AS ts ON t.source_id = ts.id
           JOIN admin_account AS a ON t.editor = a.id
           WHERE t.status = 2 AND t.id = ?
           ';
        $subSql = "SELECT * FROM touching_thought  WHERE touching_id = ?";
        $stmt =  $this->db->conn->prepare($sql);
        $subStmt = $this->db->conn->prepare($subSql);
        $stmt->execute([$id]);
        if ($stmt->rowCount() == 1) {
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $stmt->closeCursor();
            $subStmt->execute([$result['id']]);
            $subResult = $subStmt->fetchAll(PDO::FETCH_ASSOC);
            $result['thoughts'] = empty($subResult) ? '' : $subResult;
            return $result;
        }
        $result = SERVER_INTERNAL_ERROR;
        return $result;
    }

    public function getRandomTouching()
    {
        $sql = 'SELECT t.*,ts.name AS source,a.name AS editor_name FROM touching AS t
            JOIN touching_source AS ts ON t.source_id = ts.id
           JOIN admin_account AS a ON t.editor = a.id
           WHERE t.status = 2
           ORDER BY RAND() LIMIT 1
           ';
        $subSql = "SELECT * FROM touching_thought  WHERE touching_id = ?";
        $stmt =  $this->db->conn->prepare($sql);
        $subStmt = $this->db->conn->prepare($subSql);
        $stmt->execute();
        if ($stmt->rowCount() == 1) {
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $stmt->closeCursor();
            $subStmt->execute([$result['id']]);
            $subResult = $subStmt->fetchAll(PDO::FETCH_ASSOC);
            $result['thoughts'] = empty($subResult) ? '' : $subResult;
            return $result;
        }
        $result = SERVER_INTERNAL_ERROR;
        return $result;
    }

     public function createThought($id, $thought)
    {
        $sql = 'INSERT INTO touching_thought VALUES(?,?,?,?,?)';
        $stmt =  $this->db->conn->prepare($sql);
        $stmt->execute([null, $id, $thought, time(), time()]);
        if ($stmt->rowCount() == 1) {
            $result = SUCCESS;
            return $result;
        }
        $result = SERVER_INTERNAL_ERROR;
        return $result;
    }

}
