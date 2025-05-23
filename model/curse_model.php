<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/database/db.php';
class Curse_model{
    private $db;
    public function __construct(Db $db){
        $this->db = $db;
    }
    public function getList(){
        $sql = 'SELECT * FROM  curse';
        $stmt =  $this->db->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function get($id){
        $sql = 'SELECT * FROM curse WHERE id = ? ';
        $stmt =  $this->db->conn->prepare($sql);
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    public function create($content,$category,$status,$imageSourceString,$editor){
        $sql = 'INSERT INTO curse VALUES( ?,?,?,?,?,?,?,? ) ';
        $stmt =  $this->db->conn->prepare($sql);
        $stmt->execute([null,$content,$category,$imageSourceString,$status,$editor,time(),time()]);
        if ($stmt->rowCount() == 1) {
            $id = intval($this->db->conn->lastInsertId());
            $result = ['errCode'=>SUCCESS,'id'=>$id];
            return $result;
        }else{
            $result = SERVER_INTERNAL_ERROR;
            return $result;
        }
    }

    public function edit($id,$content,$category,$status,$imageSourceString){
        $sql = 'UPDATE curse SET content = ?,category = ?,status = ?,image = ?,updatetime = ? WHERE id = ?';
        $stmt =  $this->db->conn->prepare($sql);
        $stmt->execute([$content,$category,$status,$imageSourceString,time(),$id]);
        if ($stmt->rowCount() == 1) {
            $result = SUCCESS;
            return $result;
        }else{
            $result = SERVER_INTERNAL_ERROR;
            return $result;
        }
    }

    public function delete($id){
        $sql = 'DELETE FROM curse WHERE id = ? ';
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

    public function getMaxId(){
        $sql = 'SELECT id FROM curse ORDER BY id DESC LIMIT 1 ';
        $stmt =  $this->db->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if(empty($result)){
            return 0;
        }else{
            return intval($result['id']);
        }
    }

    public function getExportList(){
        $sql = "SELECT c.*,a.name AS editor_name FROM curse AS c LEFT JOIN admin_account AS a ON c.editor = a.id";
        $stmt =  $this->db->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

}