<?php

class File
{
    private int $ownerId;
    private Db $db;

    public function __construct(){
        $this->db = new Db();

      //  $this->ownerId = $_SESSION['user_id'];

        // временная заглушка без сессии
        $this->ownerId = 3;
    }

    public function list(){
        $this->db->query('SELECT name FROM `File` WHERE user_owner_id = :user_owner_id');
        $this->db->bind(':user_owner_id', $this->ownerId);
        $files = $this->db->resultSet();
        var_dump(['files-list' => $files]);
    }

    public function show(int $id){
        $this->db->query('SELECT * FROM `File` WHERE id = :id');
        $this->db->bind(':id', $id);
        $file = $this->db->single();
        if ($file) {
            var_dump(['file-info' => $file]);
        } else {
            echo 'smth went wrong';
        }
    }

    public function add() {
        var_dump(['files'=>$_FILES]);
        if (!empty($_FILES['file'])) {
            $file = $_FILES['file'];

            $srcFileName = $file['name'];
            $pathInDb = 'uploads/';

            $extension = pathinfo($srcFileName, PATHINFO_EXTENSION);

            if (!empty(trim($_POST['file_name']))) {
                $srcFileName = trim($_POST['file_name']) . '.' . $extension;
            }

            if (!empty(trim($_POST['directory']) && is_dir('uploads/' . trim($_POST['directory'])))) {
                $newFilePath = 'uploads/' . trim($_POST['directory']).'/'. $srcFileName;
                $pathInDb = 'uploads/' . trim($_POST['directory']).'/';
            } else {
                $newFilePath = 'uploads/' . $srcFileName;
            }

            if ( file_exists($newFilePath) ) {
                // переделать на выкидывание ошибки
                echo 'Файл с таким именем уже существует';
                return;
            }

            // собираем путь до нового файла - папка uploads в текущей директории
            // в качестве имени оставляем исходное файла имя во время загрузки в браузере
//            $srcFileName = $file['name'];
//            $newFilePath = 'uploads/' . $srcFileName;

            if (!move_uploaded_file($file['tmp_name'], $newFilePath)) {
                $error = 'Ошибка при загрузке файла';
            } else {
                $result = 'http://cloud-storage.local/uploads/' . $srcFileName;
                // запись в бд
                $this->db->query('INSERT INTO `File` (name, path, user_owner_id) VALUES(:name, :path, :user_owner_id)');
                $this->db->bind(":name", $srcFileName);
                $this->db->bind(":path", $pathInDb);
                $this->db->bind(":user_owner_id",  $this->ownerId);

                if ($this->db->execute()) {
                    echo 'Файл успешно загружен';
                } else {
                    echo 'Что-то пошло не так';
                }

            }
        }
    }

    // TODO
    public function update($id) {

    }

    public function delete($id)
    {
        $this->db->query('SELECT * FROM `File` WHERE id = :id');
        $this->db->bind(':id', $id);
        $file = $this->db->single();

        if ($file) {
            $path = $file->path . $file->name;
            if (file_exists($path)) {
                if (unlink($path)) {
                    $this->db->query('DELETE FROM File WHERE id = :id');
                    $this->db->bind(':id', $id);
                    if ($this->db->execute()) {
                        echo 'Файл удалён';
                    } else {
                        echo 'Что-то пошло не так';
                    }
                } else {
                    "Ошибка";
                }

            } else {
                echo "Файла не существует";
            }

        } else {
            echo "Нет данных о файле";
        }
    }
}