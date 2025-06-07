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

    public function create($content, $category, $status, $imageSourceString, $editor)
    {
        $sql = 'INSERT INTO curse VALUES( ?,?,?,?,?,?,?,? ) ';
        $stmt =  $this->db->conn->prepare($sql);
        $stmt->execute([null, $content, $category, $imageSourceString, $status, $editor, time(), time()]);
        if ($stmt->rowCount() == 1) {
            $id = intval($this->db->conn->lastInsertId());
            $result = ['errCode' => SUCCESS, 'id' => $id];
            return $result;
        }
        $result = SERVER_INTERNAL_ERROR;
        return $result;
    }

    public function edit($id, $content, $category, $status, $imageSourceString)
    {
        $sql = 'UPDATE curse SET content = ?,category = ?,status = ?,image = ?,updatetime = ? WHERE id = ?';
        $stmt =  $this->db->conn->prepare($sql);
        $stmt->execute([$content, $category, $status, $imageSourceString, time(), $id]);
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

    public function getMaxId()
    {
        $sql = 'SELECT id FROM curse ORDER BY id DESC LIMIT 1 ';
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
        $mainSql = "SELECT c.*,a.name AS editor_name FROM curse AS c LEFT JOIN admin_account AS a ON c.editor = a.id";
        $subSql = 'SELECT cwt.*,ct.name FROM curse_with_tag AS cwt
        JOIN curse_tag AS ct ON cwt.tag_id = ct.id
        WHERE cwt.curse_id = ?
        ';
        $strategySql = 'SELECT * FROM curse_strategy WHERE curse_id = ?';
        $stmt =  $this->db->conn->prepare($mainSql);
        $subStmt = $this->db->conn->prepare($subSql);
        $strategyStmt = $this->db->conn->prepare($strategySql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        for ($i = 0; $i < count($result); $i++) {
            $subStmt->execute([$result[$i]['id']]);
            $subResult = $subStmt->fetchAll(PDO::FETCH_ASSOC);
            $result[$i]['tags'] = '';
            if (!empty($subResult)) {
                $tags = [];
                foreach ($subResult as $tag) {
                    $tags[] = $tag['tag_name'];
                }
                $result[$i]['tags'] = implode(',', $tags);
            }
            $subStmt->closeCursor();
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


    public function getListFrontend($category)
    {
        $sql = 'SELECT * FROM curse WHERE status = 2 ';
        $params = [];


        if (!empty($category)) {
            $params[] = $category;
            $sql .= " AND category = ? ";
        }

        $sql .= " ORDER BY createtime DESC ";
        $stmt =  $this->db->conn->prepare($sql);
        $stmt->execute($params);

        if (empty($category)) {
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
        $mainSql = 'SELECT c.*,cc.name AS category_name,a.name AS editor_name  FROM curse AS c
        JOIN curse_category AS cc ON c.category = cc.id
        JOIN admin_account AS a ON c.editor = a.id
        WHERE c.id = ? AND c.status = 2
        ';

        $subSql = 'SELECT cwt.*,ct.name FROM curse_with_tag AS cwt
            JOIN curse_tag AS ct ON cwt.tag_id = ct.id
            WHERE cwt.curse_id = ?
        ';

        $strategySql = 'SELECT * FROM curse_strategy WHERE curse_id = ?';

        $mainStmt =  $this->db->conn->prepare($mainSql);
        $subStmt = $this->db->conn->prepare($subSql);
        $strategyStmt = $this->db->conn->prepare($strategySql);
        $mainStmt->execute([$id]);
        if ($mainStmt->rowCount() == 1) {
            $mainInfo = $mainStmt->fetch(PDO::FETCH_ASSOC);
            $mainStmt->closeCursor();
            $subStmt->execute([$id]);
            if ($subStmt->rowCount() < 0) {
                $result = SERVER_INTERNAL_ERROR;
                return $result;
            }
            $mainInfo['subinfo'] = $subStmt->fetchAll(PDO::FETCH_ASSOC);
            $subStmt->closeCursor();
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
        $sql = 'INSERT INTO curse_strategy VALUES(?,?,?,?,?)';
        $stmt =  $this->db->conn->prepare($sql);
        $stmt->execute([null, $id, $content, 2, time()]);
        if ($stmt->rowCount() == 1) {
            $result = SUCCESS;
            return $result;
        }
        $result = SERVER_INTERNAL_ERROR;
        return $result;
    }
}
