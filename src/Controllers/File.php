<?php

// todo
// check if admin - then has owners right, make him as owner

class File extends Controller
{
    protected $fileModel;
    protected $shareModel;
    protected $userModel;
    private int $ownerId;
    private static string $uploadsDir = 'uploads/';

    public function __construct()
    {
        $this->fileModel = $this->model('FileModel');
        $this->shareModel = $this->model('ShareModel');
        $this->userModel = $this->model('UserModel');

        // временная заглушка без сессии
        $this->ownerId = 4;

        if(isset($_SESSION['user_id'])) {
            $this->ownerId = $_SESSION['user_id'];
        }
    }

    public function list()
    {
        $files = $this->fileModel->findAllFiles($this->ownerId);
        if ($files) {
           echo json_encode($files);
        } else {
            echo "Нет файлов";
        }

    }

    public function show($id)
    {
        $file = $this->fileModel->findOneFile($id, $this->ownerId);
        $accessForFile = $this->shareModel->checkAccess($id, $this->ownerId);
        if ($file) {
            echo json_encode($file);
        } elseif ($accessForFile) {
            echo json_encode($accessForFile);
        } else {
            echo 'Такого файла не существует или у вас нет к нему доступа';
        }
    }

    // поля $_FILES['file'], $_POST['file_name'], $_POST['directory']

    public function add()
    {
        if (!empty($_FILES['file'])) {
            $file = $_FILES['file'];
            $srcFileName = $file['name'];
            $pathInDb = '';
            $extension = pathinfo($srcFileName, PATHINFO_EXTENSION);

            if (!empty(trim($_POST['file_name']))) {
                $srcFileName = trim($_POST['file_name']) . '.' . $extension;
            }

            if (isset($_POST['directory'])) {
                if (!empty(trim($_POST['directory']))) {
                    if (is_dir(self::$uploadsDir . trim($_POST['directory']))) {
                        $newFilePath = self::$uploadsDir . trim($_POST['directory']) . '/' . $srcFileName;
                        $pathInDb = trim($_POST['directory']) . '/';
                    } else {
                        echo "Такой папки не существует";
                        return;
                    }
            }

            } else {
                $newFilePath = 'uploads/' . $srcFileName;
            }

            if (file_exists($newFilePath)) {
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
                    "Что-то пошло не так";
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
            var_dump(['files in directory' => $files]);
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

    // SHARE

    public function shareList($id)
    {
        $shareList = $this->shareModel->shareList($id);
        var_dump(['shareList' => $shareList]);
    }

    public function shareFile($fileId, $userId)
    {
        $file = $this->fileModel->findOneFile($fileId, $this->ownerId);
        $user = $this->userModel->findOneUser($userId);
        var_dump(['user' => $user, 'file' => $file]);
        if ($file && $user) {
            $this->shareModel->shareFileInDb($fileId, $userId);
        } else {
            echo "Такого файла или пользователя не существует";
        }
    }

    public function unshareFile($fileId, $userId)
    {
        $file = $this->fileModel->findOneFile($fileId, $this->ownerId);
        $user = $this->userModel->findOneUser($userId);
        var_dump($file);
        var_dump($user);
        if ($file && $user) {
            $this->shareModel->unshareFileInDb($fileId, $userId);
        } else {
            echo "Такого файла или пользователя не существует";
        }
    }
}