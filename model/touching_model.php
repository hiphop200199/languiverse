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
        $sql = 'SELECT * FROM touching WHERE id = ? ';
        $stmt =  $this->db->conn->prepare($sql);
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    public function create($content, $source,$link, $image, $status, $editor)
    {
        $sql = 'INSERT INTO touching VALUES( ?,?,?,?,?,?,?,?,? ) ';
        $stmt =  $this->db->conn->prepare($sql);
        $stmt->execute([null, $content, $source,$link, $image, $status, $editor, time(), time()]);
        if ($stmt->rowCount() == 1) {
            $result = SUCCESS;
            return $result;
        } else {
            $result = SERVER_INTERNAL_ERROR;
            return $result;
        }
    }

    public function edit($id, $content, $source,$link, $image, $status)
    {
        $sql = 'UPDATE touching SET content = ?,source = ?,link = ?,image=?,status = ?,updatetime = ? WHERE id = ?';
        $stmt =  $this->db->conn->prepare($sql);
        $stmt->execute([$content, $source,$link, $image, $status, time(), $id]);
        if ($stmt->rowCount() == 1) {
            $result = SUCCESS;
            return $result;
        } else {
            $result = SERVER_INTERNAL_ERROR;
            return $result;
        }
    }

    public function delete($id)
    {
        $sql = 'DELETE FROM touching WHERE id = ?';
        $stmt =  $this->db->conn->prepare($sql);
        $stmt->execute([$id]);
        if ($stmt->rowCount() == 1) {
            $result = SUCCESS;
            return $result;
        } else {
            $result = SERVER_INTERNAL_ERROR;
            return $result;
        }
    }

    public function getMaxId()
    {
        $sql = 'SELECT id FROM touching ORDER BY id DESC LIMIT 1 ';
        $stmt =  $this->db->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if (empty($result)) {
            return 0;
        } else {
            return intval($result['id']);
        }
    }

    public function getExportList()
    {
        $sql = "SELECT t.*,a.name AS editor_name FROM touching AS t
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

    public function getListFrontend($source)
    {
        $sql = 'SELECT * FROM touching WHERE status = 2 ';
        $params = [];

        if (!empty($source)) {
            $sql .= " AND source = ? ";
            $params = [$source];
        }


        $sql .= " ORDER BY createtime DESC ";
        $stmt =  $this->db->conn->prepare($sql);
        $stmt->execute($params);

        if (empty($source)) {
            $result = [];
            return $result;
        } else if ($stmt->rowCount() >= 0) {
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } else {
            $result = SERVER_INTERNAL_ERROR;
            return $result;
        }
    }

    public function getFrontend($id)
    {
        $sql = 'SELECT t.*,a.name FROM touching AS t
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
        } else {
            $result = SERVER_INTERNAL_ERROR;
            return $result;
        }
    }

    public function getRandomTouching()
    {
        $sql = 'SELECT t.*,a.name FROM touching AS t
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
        } else {
            $result = SERVER_INTERNAL_ERROR;
            return $result;
        }
    }

    public function getSourceListFrontend()
    {
          $sql = 'SELECT source FROM touching WHERE status = 2';
        $stmt =  $this->db->conn->prepare($sql);
        $stmt->execute();
        if ($stmt->rowCount() >= 0) {
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } else {
            $result = SERVER_INTERNAL_ERROR;
            return $result;
        }
    }

     public function createThought($id, $thought)
    {
        $sql = 'INSERT INTO touching_thought VALUES(?,?,?,?,?)';
        $stmt =  $this->db->conn->prepare($sql);
        $stmt->execute([null, $id, $thought, time(), time()]);
        if ($stmt->rowCount() == 1) {
            $result = SUCCESS;
            return $result;
        } else {
            $result = SERVER_INTERNAL_ERROR;
            return $result;
        }
    }

}
