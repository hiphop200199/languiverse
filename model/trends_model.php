<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/database/db.php';
class Trends_model
{
    private $db;
    public function __construct(Db $db)
    {
        $this->db = $db;
    }
    public function getList()
    {
        $sql = 'SELECT * FROM  trends';
        $stmt =  $this->db->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function get($id)
    {
        $sql = 'SELECT * FROM trends WHERE id = ? ';
        $stmt =  $this->db->conn->prepare($sql);
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    public function create($content,$age,$explanation,  $status,  $editor)
    {
        $sql = 'INSERT INTO trends VALUES( ?,?,?,?,?,?,?,? ) ';
        $stmt =  $this->db->conn->prepare($sql);
        $stmt->execute([null, $content,$age,$explanation, $status, $editor, time(), time()]);
        if ($stmt->rowCount() == 1) {
            $result = SUCCESS;
            return $result;
        }
        $result = SERVER_INTERNAL_ERROR;
        return $result;
    }

    public function edit($id, $content, $age,$explanation, $status)
    {
        $sql = 'UPDATE trends SET content = ?,age=?,explanation=?,status = ?,updatetime = ? WHERE id = ?';
        $stmt =  $this->db->conn->prepare($sql);
        $stmt->execute([$content,$age,$explanation,  $status,  time(), $id]);
        if ($stmt->rowCount() == 1) {
            $result = SUCCESS;
            return $result;
        }
        $result = SERVER_INTERNAL_ERROR;
        return $result;
    }

    public function delete($id)
    {
        $sql = 'DELETE FROM trends WHERE id = ? ';
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
        $mainSql = "SELECT t.*,ta.name AS age_name,a.name AS editor_name FROM trends AS t
         LEFT JOIN admin_account AS a ON t.editor = a.id
         LEFT JOIN trends_age AS ta ON t.age = ta.id";
       
        $stmt =  $this->db->conn->prepare($mainSql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
      
        return $result;
    }

    //web


    public function getListFrontend($age)
    {
         if (empty($age)) {
            $result = [];
            return $result;
        }
        $sql = 'SELECT * FROM trends WHERE status = 2 AND age = ?';
        $sql .= " ORDER BY createtime DESC ";
        $params = [$age];
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
        $mainSql = 'SELECT t.*,ta.name AS age_name,a.name AS editor_name  FROM trends AS c
        LEFT JOIN admin_account AS a ON t.editor = a.id
        LEFT JOIN trends_age AS ta ON t.age = ta.id
        WHERE t.id = ? AND t.status = 2
        ';
     

        $mainStmt =  $this->db->conn->prepare($mainSql);
     
        $mainStmt->execute([$id]);
        if ($mainStmt->rowCount() == 1) {
            $mainInfo = $mainStmt->fetch(PDO::FETCH_ASSOC);
            $mainStmt->closeCursor();
            return $mainInfo;
        }
        $result = SERVER_INTERNAL_ERROR;
        return $result;
    }



}
