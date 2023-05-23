<?php
require_once("base.php");

class Categories extends Base
{

    public function get()
    {
        $query = $this->db->prepare("
        SELECT id, name
                FROM categories
                
        ");

        $query->execute();

        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getItem($id)
    {
        $query = $this->db->prepare("
            SELECT id, name
            FROM categories
            WHERE id = ?
        ");

        $query->execute([$id]);

        return $query->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $query = $this->db->prepare("
            INSERT INTO categories 
            (name)
            VALUES (?)
            ");

        $query->execute([
            $data['name']
        ]);

        $data["id"] = $this->db->lastInsertId();

        return $data;
    }


    public function updateItem($id, $data)
    {
        $query = $this->db->prepare("
        UPDATE categories
        SET name = ?
        WHERE id = ?
    ");

        $query->execute([
            $data['name'],
            $id
        ]);

        if ($query->rowCount() > 0) {
            return true;
        }
        return false;
    }

    public function deleteItem($id, $data)
    {
        $query = $this->db->prepare("
        DELETE FROM categories
        WHERE id = ?
        ");

        if ($query->execute([$id])) {

            return true;
        }
        return false;
    }

}