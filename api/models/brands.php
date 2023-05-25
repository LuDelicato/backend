<?php
require_once("base.php");

class Brands extends Base
{

    public function get()
    {
        $query = $this->db->prepare("
        SELECT id, name
                FROM brands
                
        ");

        $query->execute();

        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getItem($id)
    {
        $query = $this->db->prepare("
            SELECT id, name
            FROM brands
            WHERE id = ?
        ");

        $query->execute([$id]);

        return $query->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $query = $this->db->prepare("
            INSERT INTO brands 
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
        UPDATE brands
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
        DELETE FROM brands
        WHERE id = ?
        ");

        if ($query->execute([$id])) {

            return true;
        }
        return false;
    }

}