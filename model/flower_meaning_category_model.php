<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/database/db.php';
class Flower_meaning_category_model
{
    private $db;
    public function __construct(Db $db)
    {
        $this->db = $db;
    }
    public function getList() {
        $sql = 'SELECT * FROM flower_meaning_category';
        $stmt =  $this->db->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function get($id) {
        $sql = 'SELECT * FROM flower_meaning_category WHERE id = ? ';
        $stmt =  $this->db->conn->prepare($sql);
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    public function create($name, $status,$editor)
    {
        $sql = 'INSERT INTO flower_meaning_category VALUES( ?,?,?,?,?,? ) ';
        $stmt =  $this->db->conn->prepare($sql);
        $stmt->execute([null,$name,$status,$editor,time(),time()]);
        if ($stmt->rowCount() == 1) {
            $result = SUCCESS;
            return $result;
        }
        $result = SERVER_INTERNAL_ERROR;
        return $result;
    }

    public function edit($id,$name,$status) {
        $sql = 'UPDATE flower_meaning_category SET name = ?,status = ?,updatetime = ? WHERE id = ?';
        $stmt =  $this->db->conn->prepare($sql);
        $stmt->execute([$name,$status,time(),$id]);
        if ($stmt->rowCount() == 1) {
            $result = SUCCESS;
            return $result;
        }
        $result = SERVER_INTERNAL_ERROR;
        return $result;
    }

    public function delete($id) {
        $sql = 'DELETE FROM flower_meaning_category WHERE id = ?';
        $stmt =  $this->db->conn->prepare($sql);
        $stmt->execute([$id]);
        if ($stmt->rowCount() == 1) {
            $result = SUCCESS;
            return $result;
        }
        $result = SERVER_INTERNAL_ERROR;
        return $result;
    }

    public function getExportList(){
        $sql = "SELECT f.*,a.name AS editor_name FROM flower_meaning_category AS f LEFT JOIN admin_account AS a ON f.editor = a.id";
        $stmt =  $this->db->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

}
