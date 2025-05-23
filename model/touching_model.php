<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/database/db.php';
class Touching_model
{

    private $db;
    public function __construct(Db $db)
    {
        $this->db = $db;
    }

     public function getList() {
        $sql = 'SELECT * FROM touching';
        $stmt =  $this->db->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function get($id) {
        $sql = 'SELECT * FROM touching WHERE id = ? ';
        $stmt =  $this->db->conn->prepare($sql);
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    public function create($content, $source,$image,$status,$editor)
    {
        $sql = 'INSERT INTO touching VALUES( ?,?,?,?,?,?,?,? ) ';
        $stmt =  $this->db->conn->prepare($sql);
        $stmt->execute([null,$content,$source,$image,$status,$editor,time(),time()]);
        if ($stmt->rowCount() == 1) {
            $result = SUCCESS;
            return $result;
        }else{
            $result = SERVER_INTERNAL_ERROR;
            return $result;
        }
    }

    public function edit($id,$content, $source,$image,$status) {
        $sql = 'UPDATE touching SET content = ?,source = ?,image=?,status = ?,updatetime = ? WHERE id = ?';
        $stmt =  $this->db->conn->prepare($sql);
        $stmt->execute([$content,$source,$image,$status,time(),$id]);
        if ($stmt->rowCount() == 1) {
            $result = SUCCESS;
            return $result;
        }else{
            $result = SERVER_INTERNAL_ERROR;
            return $result;
        }
    }

    public function delete($id) {
        $sql = 'DELETE FROM touching WHERE id = ?';
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
        $sql = "SELECT * FROM touching";
        $subSql = "SELECT * FROM touching_thought WHERE touching_id = ?";
        $stmt =  $this->db->conn->prepare($sql);
        $subStmt = $this->db->conn->prepare($subSql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        for($i=0;$i<count($result);$i++){
            $subStmt->execute([$result[$i]['id']]);
            $subResult = $subStmt->fetchAll(PDO::FETCH_ASSOC);
            $result[$i]['thoughts'] = '';
            if(!empty($subResult)){
               $thoughts = [];
               foreach($subResult as $sub){
                $thoughts[] = $sub['thought'];
               }
               $result[$i]['thoughts'] = implode(',',$thoughts);
            }
            $subStmt->closeCursor();
        }
        return $result;
    }

}
