<?php

class FileModel
{
    private Db $db;

    public function __construct()
    {
        $this->db = new Db;
    }

    public function findOneFile($id, $ownerId)
    {
        $this->db->query("SELECT * FROM `File` WHERE id = :id AND user_owner_id = :user_owner_id");
        $this->db->bind(':id', $id);
        $this->db->bind(':user_owner_id', $ownerId);
        return $this->db->single();
    }

    public function findAllFiles($ownerId)
    {
        $this->db->query('SELECT * FROM `File` WHERE user_owner_id = :user_owner_id');
        $this->db->bind(':user_owner_id', $ownerId);
        return $this->db->resultSet();
    }

    public function addFile($fileData)
    {
        $this->db->query('INSERT INTO `File` (name, directory, user_owner_id, extension) VALUES(:name, :directory, :user_owner_id, :extension)');
        $this->db->bind(":name", $fileData['srcFileName']);
        $this->db->bind(":directory", $fileData['pathInDb']);
        $this->db->bind(":user_owner_id", $fileData['ownerId']);
        $this->db->bind(":extension", $fileData['extension']);
        if ($this->db->execute()) {
            echo 'Файл успешно загружен';
        } else {
            echo 'Что-то пошло не так';
        }
    }

    public function updateFile($fileData)
    {
        $this->db->query('UPDATE File SET name = :name, directory = :directory WHERE id = :id');
        $this->db->bind(':id', $fileData['id']);
        $this->db->bind(":name", $fileData['name']);
        $this->db->bind(":directory", $fileData['directory']);
        if ($this->db->execute()) {
            echo 'Файл успешно изменен';
        } else {
            echo 'Что-то пошло не так';
        }
    }

    public function deleteFile($id, $ownerId)
    {
        $this->db->query('DELETE FROM File WHERE id = :id AND user_owner_id = :user_owner_id');
        $this->db->bind(':id', $id);
        $this->db->bind(':user_owner_id', $ownerId);
        if ($this->db->execute()) {
            echo 'Файл удалён';
        } else {
            echo 'Что-то пошло не так';
        }
    }

    public function deleteDirectory($id, $ownerId)
    {
        $this->db->query('DELETE FROM `File` WHERE directory = :directory AND user_owner_id = :user_owner_id');
        $this->db->bind(':directory', $id);
        $this->db->bind(':user_owner_id', $ownerId);
        if ($this->db->execute()) {
            echo 'deleted';
            return true;
        } else {
            return false;
        }
    }

    public function renameDirectoryInFiles($directory, $newDirectoryName)
    {
        $this->db->query('SELECT * FROM `File` WHERE directory = :directory');
        $this->db->bind(':directory', $directory);
        $filesInDirectory = $this->db->resultSet();
        var_dump(['$filesInDirectory' => $filesInDirectory]);
        foreach ($filesInDirectory as $file) {
            //   $file->directory = trim($_PUT['directory_name']);
            $this->db->query('UPDATE File SET directory = :directory WHERE id = :id');
            $this->db->bind(':id', $file->id);
            $this->db->bind(':directory', $newDirectoryName);

            if ($this->db->execute()) {
                echo 'updated';
            } else {
                echo 'Что-то пошло не так';
            }
        }
    }
}