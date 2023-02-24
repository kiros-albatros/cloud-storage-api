<?php

class ShareModel
{
    private Db $db;

    public function __construct()
    {
        $this->db = new Db;
    }

    public function shareList($file_id)
    {
        $this->db->query('SELECT user_email FROM `File_accesses` WHERE file_id = :file_id');
        $this->db->bind(':file_id', $file_id);
        return $this->db->resultSet();
    }

    public function shareFileInDb($fileId, $userId)
    {
        $this->db->query("SELECT * FROM `File_accesses` WHERE file_id = :file_id AND user_id = :user_id");
        $this->db->bind(':file_id', $fileId);
        $this->db->bind(':user_id', $userId);
        $existingRow = $this->db->single();
        if ($existingRow) {
            $this->db->query("UPDATE File_accesses SET file_id = :file_id, user_id = :user_id, permission_level = :permission_level, share_url = :share_url WHERE file_id = :file_id AND user_id = :user_id");
        } else {
            $this->db->query("INSERT INTO File_accesses (file_id, user_id, permission_level, share_url) VALUES(:file_id, :user_id, :permission_level, :share_url) ");
        }
        $this->db->bind(':file_id', $fileId);
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':permission_level', 'read');
        $this->db->bind(':share_url', 'file/share/' . $fileId);
        if ($this->db->execute()) {
            echo 'Получен доступ';
        } else {
            echo 'Что-то пошло не так';
        }
    }

    public function unshareFileInDb($fileId, $userId)
    {
        $this->db->query("SELECT * FROM `File_accesses` WHERE file_id = :file_id AND user_id = :user_id");
        $this->db->bind(':file_id', $fileId);
        $this->db->bind(':user_id', $userId);
        $existingRow = $this->db->single();
        if ($existingRow) {
            $this->db->query("DELETE FROM File_accesses WHERE file_id = :file_id AND user_id = :user_id");
            $this->db->bind(':file_id', $fileId);
            $this->db->bind(':user_id', $userId);
            if ($this->db->execute()) {
                echo 'Прекращен доступ';
            } else {
                echo 'Что-то пошло не так';
            }
        }
    }
}