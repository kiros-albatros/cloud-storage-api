<?php

class ShareModel
{
    private Db $db;

    public function __construct(){
        $this->db = new Db;
    }

    public function shareList($file_id)
    {
        $this->db->query('SELECT user_email FROM `File_accesses` WHERE file_id = :file_id');
        $this->db->bind(':file_id', $file_id);
        return $this->db->resultSet();
    }
}