<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/database/db.php';

class Account_model
{
    private $db;
    public function __construct(Db $db)
    {
        $this->db = $db;
    }
    public function getList()
    {
        $sql = 'SELECT * FROM admin_account';
        $stmt =  $this->db->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    public function get($id)
    {
        $sql = 'SELECT * FROM admin_account WHERE id = ? ';
        $stmt =  $this->db->conn->prepare($sql);
        $stmt->execute([$id]);//所有參數都當字串看待，要自己轉型
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    public function create($account,$password,$nickname,$email,$status,$isAdmin)
    {
        $sql = 'INSERT INTO admin_account VALUES( ?,?,?,?,?,?,?,?,?,? ) ';
        $stmt =  $this->db->conn->prepare($sql);
        $stmt->execute([null,$account,$password,$nickname,$email,$status,$isAdmin,1,time(),time()]);
        if ($stmt->rowCount() == 1) {
            $result = SUCCESS;
            return $result;
        }
        $result = SERVER_INTERNAL_ERROR;
        return $result;
    }

    public function edit($id,$account,$nickname,$email,$status) {
        $sql = 'UPDATE admin_account SET account = ?,name = ?,email = ?,status = ?,updatetime = ? WHERE id = ?';
        $stmt =  $this->db->conn->prepare($sql);
        $stmt->execute([$account,$nickname,$email,$status,time(),$id]);
        if ($stmt->rowCount() == 1) {
            $result = SUCCESS;
            return $result;
        }
        $result = SERVER_INTERNAL_ERROR;
        return $result;
    }

    public function delete($id)
    {
        $sql = 'DELETE FROM admin_account WHERE id = ? ';
        $stmt =  $this->db->conn->prepare($sql);
        $stmt->execute([$id]);
        if ($stmt->rowCount() == 1) {
            $result = SUCCESS;
            return $result;
        }
        $result = SERVER_INTERNAL_ERROR;
        return $result;
    }

    public function checkLogin($account,$password){
        $sql = 'SELECT * FROM admin_account WHERE account = ? AND password = ?';
        $stmt =  $this->db->conn->prepare($sql);
        $stmt->execute([$account,$password]);
        if ($stmt->rowCount() == 1) {
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result;
        }
        return [];
    }
    public function getByEmail($email) {
        $sql = 'SELECT * FROM admin_account WHERE email = ?';
        $stmt =  $this->db->conn->prepare($sql);
        $stmt->execute([$email]);
        if ($stmt->rowCount() == 1) {
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result;
        }
        return [];
    }

    public function checkByAccountAndEmail($account,$email){
        $sql = 'SELECT * FROM admin_account WHERE account = ? OR email = ?';
        $stmt =  $this->db->conn->prepare($sql);
        $stmt->execute([$account,$email]);
        if ($stmt->rowCount() == 1) {
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result;
        }
        return [];
    }
    

    public function editPassword($id,$newPassword) {
        $sql = 'UPDATE admin_account SET password = ?,updatetime = ? WHERE id = ?';
        $stmt =  $this->db->conn->prepare($sql);
        $stmt->execute([$newPassword,time(),$id]);
        if ($stmt->rowCount() == 1) {
            $result = SUCCESS;
            return $result;
        }
        $result = SERVER_INTERNAL_ERROR;
        return $result;
    }

    //web

    public function getListFrontend()
    {
        $sql = 'SELECT * FROM admin_account WHERE status = 2';
        $stmt =  $this->db->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

}
