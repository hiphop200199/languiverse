<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/database/db.php';
class Joke_tag_model
{
    private $db;
    public function __construct(Db $db)
    {
        $this->db = $db;
    }
    public function getList() {
        $sql = 'SELECT * FROM joke_tag';
        $stmt =  $this->db->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function get($id) {
        $sql = 'SELECT * FROM joke_tag WHERE id = ? ';
        $stmt =  $this->db->conn->prepare($sql);
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    public function create($name, $status,$editor)
    {
        $sql = 'INSERT INTO joke_tag VALUES( ?,?,?,?,?,? ) ';
        $stmt =  $this->db->conn->prepare($sql);
        $stmt->execute([null,$name,$status,$editor,time(),time()]);
        if ($stmt->rowCount() == 1) {
            $result = SUCCESS;
            return $result;
        }else{
            $result = SERVER_INTERNAL_ERROR;
            return $result;
        }
    }

    public function edit($id,$name,$status) {
        $sql = 'UPDATE joke_tag SET name = ?,status = ?,updatetime = ? WHERE id = ?';
        $stmt =  $this->db->conn->prepare($sql);
        $stmt->execute([$name,$status,time(),$id]);
        if ($stmt->rowCount() == 1) {
            $result = SUCCESS;
            return $result;
        }else{
            $result = SERVER_INTERNAL_ERROR;
            return $result;
        }
    }

    public function delete($id) {
        $sql = 'DELETE FROM joke_tag WHERE id = ?';
        $stmt =  $this->db->conn->prepare($sql);
        $stmt->execute([$id]);
        if ($stmt->rowCount() == 1) {
            $result = SUCCESS;
            return $result;
        }else{
            $result = SERVER_INTERNAL_ERROR;
            return $result;
        }
    }

    public function getExportList(){
        $sql = "SELECT j.*,a.name AS editor_name FROM joke_tag AS j LEFT JOIN admin_account AS a ON j.editor = a.id";
        $stmt =  $this->db->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

}
