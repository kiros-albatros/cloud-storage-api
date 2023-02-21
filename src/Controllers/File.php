<?php

//require_once '../Models/File.php';

class File extends Controller
{
    private int $ownerId;
    private Db $db;
    private static string $uploadsDir = 'uploads/';

    public function __construct()
    {
        $this->db = new Db();


        //  $this->ownerId = $_SESSION['user_id'];

        // временная заглушка без сессии
        $this->ownerId = 3;
    }

    public function list()
    {
        $this->db->query('SELECT name FROM `File` WHERE user_owner_id = :user_owner_id');
        $this->db->bind(':user_owner_id', $this->ownerId);
        $files = $this->db->resultSet();
        var_dump(['files-list' => $files]);
    }

    public function show(int $id)
    {
        $file = $this->db->getById($id, 'File');
        if ($file) {
            if ($file->user_owner_id === $this->ownerId) {
                var_dump(['file-info' => $file]);
            } else {
                echo "У вас нет прав на этот файл";
            }
        } else {
            echo 'smth went wrong';
        }
    }

    public function add()
    {
        var_dump(['files' => $_FILES]);
        if (!empty($_FILES['file'])) {
            $file = $_FILES['file'];

            $srcFileName = $file['name'];
            $pathInDb = '';

            $extension = pathinfo($srcFileName, PATHINFO_EXTENSION);

            if (!empty(trim($_POST['file_name']))) {
                $srcFileName = trim($_POST['file_name']) . '.' . $extension;
            }

            if (!empty(trim($_POST['directory']) && is_dir(self::$uploadsDir . trim($_POST['directory'])))) {
                $newFilePath = self::$uploadsDir . trim($_POST['directory']) . '/' . $srcFileName;
                $pathInDb = trim($_POST['directory']) . '/';
            } else {
                $newFilePath = 'uploads/' . $srcFileName;
            }

            if (file_exists($newFilePath)) {
                // переделать на выкидывание ошибки
                echo 'Файл с таким именем уже существует';
                return;
            }

            // собираем путь до нового файла - папка uploads в текущей директории
            // в качестве имени оставляем исходное файла имя во время загрузки в браузере
//            $srcFileName = $file['name'];
//            $newFilePath = 'uploads/' . $srcFileName;

            if (!move_uploaded_file($file['tmp_name'], $newFilePath)) {
                echo 'Ошибка при загрузке файла';
            } else {
                $result = 'http://cloud-storage.local/uploads/' . $srcFileName;
                // запись в бд
                $this->db->query('INSERT INTO `File` (name, path, user_owner_id, extension) VALUES(:name, :path, :user_owner_id, :extension)');
                $this->db->bind(":name", $srcFileName);
                $this->db->bind(":path", $pathInDb);
                $this->db->bind(":user_owner_id", $this->ownerId);
                $this->db->bind(":extension", $extension);

                if ($this->db->execute()) {
                    echo 'Файл успешно загружен';
                } else {
                    echo 'Что-то пошло не так';
                }

            }
        }
    }

    public function update($id)
    {
        parse_str(file_get_contents('php://input'), $_PUT);
     //   var_dump(['$_PUT' => $_PUT]);
        $toFileDirectory = 'uploads/';

        if (!empty(trim($_PUT['directory']))) {
            $toFileDirectory = 'uploads/' . trim($_PUT['directory']) . '/';
        }

            $file = $this->db->getById($id, 'File');
            if ($file && is_dir($toFileDirectory)) {
                var_dump(['$file' => $file]);
                if (!empty($file->directory)) {
                    $fromFileDirectory = 'uploads/' . $file->directory . '/'. $file->name;
                } else {
                    $fromFileDirectory = 'uploads/' . $file->name;
                }
                var_dump(['$fromFileDirectory'=>$fromFileDirectory]);
                if (file_exists($fromFileDirectory) && $file->user_owner_id === $this->ownerId) {
                    var_dump(['$fromFileDirectory' => $fromFileDirectory, 'to' => $toFileDirectory . trim($_PUT['file_name'])]);
                    //   if (!move_uploaded_file($path, $userFileDirectory . trim($_PUT['file_name'])) ){
                    if (!rename($fromFileDirectory, $toFileDirectory . trim($_PUT['file_name']) . '.' . $file->extension)) {
                        echo 'Ошибка при перемещении файла';
                    } else {
                        // запись в бд
                        $this->db->query('UPDATE File SET name = :name, directory = :directory WHERE id = :id');
                        $this->db->bind(':id', $id);
                        $this->db->bind(":name", trim($_PUT['file_name']) . '.' . $file->extension);
                        $this->db->bind(":directory", trim($_PUT['directory']));
                        // $this->db->bind(":user_owner_id",  $this->ownerId);

                        if ($this->db->execute()) {
                            echo 'Файл успешно изменен';
                        } else {
                            echo 'Что-то пошло не так';
                        }
                    }
                } else {
                    echo "Нет такого файла";
                }
            } else {
                echo "Нет такой папки";
            }

    }

    public function delete($id)
    {
        $file = $this->db->getById($id, 'File');
        if ($file) {
            $path = $file->path . $file->name;
            if (file_exists($path)) {
                if (unlink($path)) {
                    $this->db->query('DELETE FROM File WHERE id = :id AND user_owner_id = :user_owner_id');
                    $this->db->bind(':id', $id);
                    $this->db->bind(':user_owner_id', $this->ownerId);
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

    // DIRECTORY

    public function infoDirectory($id)
    {
        if (file_exists("uploads/" . $id) and is_dir("uploads/" . $id)) {
            $files = array_diff(scandir("uploads/" . $id), array('.', '..'));
            var_dump(['files in dir' => $files]);
        }
    }

    public function addDirectory()
    {
        if (!empty(trim($_POST['directory_name'])) && (!is_dir('uploads/' . trim($_POST['directory_name'])))) {
            mkdir("uploads/" . trim($_POST['directory_name']));
        }
    }

    public function renameDirectory($id)
    {
        parse_str(file_get_contents('php://input'), $_PUT);
        if (!empty(trim($_PUT['directory_name']))) {
            if (file_exists(self::$uploadsDir . $id) and is_dir(self::$uploadsDir . $id)) {
                if (rename(self::$uploadsDir . $id, self::$uploadsDir . trim($_PUT['directory_name']))) {
                    // меняем в бд
                    $this->db->query('SELECT * FROM `File` WHERE directory = :directory');
                    $this->db->bind(':directory', $id);
                    $filesInDirectory = $this->db->resultSet();
                    var_dump(['$filesInDirectory' => $filesInDirectory]);
                    foreach ($filesInDirectory as $file) {
                     //   $file->directory = trim($_PUT['directory_name']);
                        $this->db->query('UPDATE File SET directory = :directory WHERE id = :id');
                        $this->db->bind(':id', $file->id);
                        $this->db->bind(':directory', trim($_PUT['directory_name']));

                        if ($this->db->execute()) {
                            echo 'updated';
                        } else {
                            echo 'Что-то пошло не так';
                        }
                    }
                } else {
                    echo "Не удалось переименовать";
                }
            }
        }
    }

    public function RDir($path)
    {
        // если путь существует и это папка
        if (file_exists($path) and is_dir($path)) {
            // открываем папку
            $dir = opendir($path);
            while (false !== ($element = readdir($dir))) {
                // удаляем только содержимое папки
                if ($element != '.' and $element != '..') {
                    $tmp = $path . '/' . $element;
                    chmod($tmp, 0777);
                    // если элемент является папкой, то
                    // удаляем его используя нашу функцию RDir
                    if (is_dir($tmp)) {
                        $this->RDir($tmp);
                        // если элемент является файлом, то удаляем файл
                    } else {
                        unlink($tmp);
                    }
                }
            }
            // закрываем папку
            closedir($dir);
            // удаляем саму папку
            if (file_exists($path)) {
                rmdir($path);
            }
        }
    }

    // дописать проверку на существование папки
    public function deleteDirectory($id)
    {
        $path = 'uploads/' . $id;

        $this->db->query('DELETE FROM `File` WHERE directory = :directory AND user_owner_id = :user_owner_id');
        $this->db->bind(':directory', $id);
        $this->db->bind(':user_owner_id', $this->ownerId);
        if ($this->db->execute()) {
            echo 'deleted';
            $this->RDir($path);
        } else {
            echo 'Что-то пошло не так';
        }
    }
}