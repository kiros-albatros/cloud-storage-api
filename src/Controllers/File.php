<?php

class File extends Controller
{
    protected $fileModel;
    private int $ownerId;
    private static string $uploadsDir = 'uploads/';

    public function __construct()
    {
        $this->fileModel = $this->model('FileModel');

        //  $this->ownerId = $_SESSION['user_id'];
        // временная заглушка без сессии
        $this->ownerId = 4;
    }

    public function list()
    {
        $files = $this->fileModel->findAllFiles($this->ownerId);
        if ($files) {
            var_dump(['files' => $files]);
        } else {
            echo "Нет файлов";
        }

    }

    public function show($id)
    {
        $file = $this->fileModel->findOneFile($id, $this->ownerId);
        var_dump(['id' => $id, 'ownerId' => $this->ownerId]);
        if ($file) {
            var_dump(['file' => $file]);
        } else {
            echo 'Такого файла не существует или у вас нет к нему доступа';
        }
    }

    public function add()
    {
        //  var_dump(['files' => $_FILES]);
        if (!empty($_FILES['file'])) {
            $file = $_FILES['file'];
            $srcFileName = $file['name'];
            $pathInDb = '';
            $extension = pathinfo($srcFileName, PATHINFO_EXTENSION);

            if (!empty(trim($_POST['file_name']))) {
                $srcFileName = trim($_POST['file_name']) . '.' . $extension;
            }

            if (!empty(trim($_POST['directory']))) {
                if (is_dir(self::$uploadsDir . trim($_POST['directory']))) {
                    $newFilePath = self::$uploadsDir . trim($_POST['directory']) . '/' . $srcFileName;
                    $pathInDb = trim($_POST['directory']) . '/';
                } else {
                    echo "Такой папки не существует";
                    return;
                }
            } else {
                $newFilePath = 'uploads/' . $srcFileName;
            }

            if (file_exists($newFilePath)) {
                // переделать на выкидывание ошибки
                echo 'Файл с таким именем уже существует';
                return;
            }

            if (!move_uploaded_file($file['tmp_name'], $newFilePath)) {
                echo 'Ошибка при загрузке файла';
            } else {
                // запись в бд
                $fileData = ['srcFileName' => $srcFileName, 'pathInDb' => $pathInDb, 'ownerId' => $this->ownerId, 'extension' => $extension];
                $this->fileModel->addFile($fileData);
            }
        }
    }

    public function update($id)
    {
        parse_str(file_get_contents('php://input'), $_PUT);
        var_dump(['$_PUT' => $_PUT]);
        $toFileDirectory = 'uploads/';

        if (!empty(trim($_PUT['directory']))) {
            $toFileDirectory = 'uploads/' . trim($_PUT['directory']) . '/';
        }
        $file = $this->fileModel->findOneFile($id, $this->ownerId);
        if ($file && is_dir($toFileDirectory)) {
            var_dump(['$file' => $file]);
            if (!empty($file->directory)) {
                $fromFileDirectory = 'uploads/' . $file->directory . '/' . $file->name;
            } else {
                $fromFileDirectory = 'uploads/' . $file->name;
            }
            var_dump(['$fromFileDirectory' => $fromFileDirectory]);
            if (file_exists($fromFileDirectory) && $file->user_owner_id === $this->ownerId) {
                var_dump(['$fromFileDirectory' => $fromFileDirectory, 'to' => $toFileDirectory . trim($_PUT['file_name'])]);
                //   if (!move_uploaded_file($path, $userFileDirectory . trim($_PUT['file_name'])) ){
                if (!rename($fromFileDirectory, $toFileDirectory . trim($_PUT['file_name']) . '.' . $file->extension)) {
                    echo 'Ошибка при перемещении файла';
                } else {
                    // запись в бд
                    $fileData = ['id' => $id, 'name' => trim($_PUT['file_name']) . '.' . $file->extension, 'directory' => trim($_PUT['directory'] . '/')];
                    $this->fileModel->updateFile($fileData);
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
        $file = $this->fileModel->findOneFile($id, $this->ownerId);
        var_dump($file);
        if ($file) {
            $path = self::$uploadsDir . $file->directory . $file->name;
            var_dump($path);
            if (file_exists($path)) {
                if (unlink($path)) {
                    $this->fileModel->deleteFile($id, $this->ownerId);
                } else {
                    "Ошибка";
                }
            } else {
                echo "Файла не существует ";
            }
        } else {
            echo "Файл не найден";
        }
    }

    // DIRECTORY

    public function infoDirectory($id)
    {
        if (file_exists("uploads/" . $id) and is_dir("uploads/" . $id)) {
            $files = array_diff(scandir("uploads/" . $id), array('.', '..'));
            var_dump(['files in dir' => $files]);
        } else {
            echo "Папка не найдена";
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
                   $this->fileModel->renameDirectoryInFiles($id, trim($_PUT['directory_name']));
                } else {
                    echo "Не удалось переименовать";
                }
            }
        }
    }

    // удаление папки с файлами на сервере
    public function RDir($path)
    {
        if (file_exists($path) and is_dir($path)) {
            $dir = opendir($path);
            while (false !== ($element = readdir($dir))) {
                if ($element != '.' and $element != '..') {
                    $tmp = $path . '/' . $element;
                    chmod($tmp, 0777);
                    if (is_dir($tmp)) {
                        $this->RDir($tmp);
                    } else {
                        unlink($tmp);
                    }
                }
            }
            closedir($dir);
            if (file_exists($path)) {
                rmdir($path);
            }
        }
    }

    public function deleteDirectory($id)
    {
        $path = 'uploads/' . $id;
        if (file_exists($path) and is_dir($path)) {
            if ($this->fileModel->deleteDirectory($id, $this->ownerId)) {
                $this->RDir($path);
            } else {
                echo 'Что-то пошло не так';
            }
        }
    }
}