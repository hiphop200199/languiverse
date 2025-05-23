<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/database/db.php';
class Flower_meaning_model{
    private $db;
    public function __construct(Db $db){
        $this->db = $db;
    }
    public function getList(){
        $sql = 'SELECT * FROM flower_meaning';
        $stmt =  $this->db->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function get($id){
        $sql = 'SELECT * FROM flower_meaning WHERE id = ? ';
        $stmt =  $this->db->conn->prepare($sql);
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    public function create($content,$story,$category,$imageSourceString,$status,$editor){
        $sql = 'INSERT INTO flower_meaning VALUES( ?,?,?,?,?,?,?,?,? ) ';
        $stmt =  $this->db->conn->prepare($sql);
        $stmt->execute([null,$content,$story,$category,$imageSourceString,$status,$editor,time(),time()]);
        if ($stmt->rowCount() == 1) {
            $result = SUCCESS;
            return $result;
        }else{
            $result = SERVER_INTERNAL_ERROR;
            return $result;
        }
    }

    public function edit($id,$content,$story,$category,$imageSourceString,$status){
        $sql = 'UPDATE flower_meaning SET content = ?,story = ?,category = ?,status = ?,image = ?,updatetime = ? WHERE id = ?';
        $stmt =  $this->db->conn->prepare($sql);
        $stmt->execute([$content,$story,$category,$status,$imageSourceString,time(),$id]);
        if ($stmt->rowCount() == 1) {
            $result = SUCCESS;
            return $result;
        }else{
            $result = SERVER_INTERNAL_ERROR;
            return $result;
        }
    }

    public function delete($id){
        $sql = 'DELETE FROM flower_meaning WHERE id = ? ';
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
        $sql = 'SELECT id FROM flower_meaning ORDER BY id DESC LIMIT 1 ';
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
        $sql = "SELECT f.*,a.name AS editor_name FROM flower_meaning AS f LEFT JOIN admin_account AS a ON f.editor = a.id";
        $stmt =  $this->db->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

}