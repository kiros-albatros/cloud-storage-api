<?php

// todo
// check if admin - then has owners right, make him as owner

class File extends Controller
{
    protected $fileModel;
    protected $shareModel;
    protected $userModel;
    private int $userId;
    private bool $isAdmin;
    private static string $uploadsDir = 'uploads/';

    public function __construct()
    {
        $this->fileModel = $this->model('FileModel');
        $this->shareModel = $this->model('ShareModel');
        $this->userModel = $this->model('UserModel');

        // временная заглушка без сессии
        // $this->userId = 4;

        if (isset($_SESSION['user_id'])) {
            $this->userId = $_SESSION['user_id'];
        }

        if (isset($_SESSION['user_role'])) {
            if ($_SESSION['user_role'] === 'admin') {
                $this->isAdmin = true;
            }
        } else {
            $this->isAdmin = false;
        }
    }

    public function list()
    {
        $files = $this->fileModel->findAllFiles($this->userId);
        if ($files) {
            echo json_encode($files);
        } else {
            echo "Нет файлов";
        }
    }

    public function show($id)
    {
        $file = $this->fileModel->findFileById($id);
        $accessForFile = $this->shareModel->checkAccess($id, $this->userId);
        if ($file) {
            if ($this->isAdmin) {
                echo json_encode($file);
            } elseif ($accessForFile) {
                echo json_encode($file);
            } elseif ($file->user_owner_id === $this->userId) {
                echo json_encode($file);
            } else {
                echo 'У вас нет доступа к файлу';
            }
        } else {
            echo 'Такого файла не существует';
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
            $newFilePath = 'uploads/' . $srcFileName;

            if (isset($_POST['directory'])) {
                if (!empty(trim($_POST['directory']))) {
                    $postDirName = trim($_POST['directory']);
                    if (is_dir(self::$uploadsDir . $postDirName)) {
                        if ($this->isAdmin) {
                            $newFilePath = self::$uploadsDir . $postDirName . '/' . $srcFileName;
                            $pathInDb = $postDirName . '/';
                        } else {
                            $dirInfo = $this->fileModel->getDirInfoByName($postDirName);
                            if ($dirInfo) {
                                if ($dirInfo->user_owner_id === $this->userId) {
                                    $newFilePath = self::$uploadsDir . $postDirName . '/' . $srcFileName;
                                    $pathInDb = $postDirName;
                                }
                            }
                        }
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
                $fileData = ['srcFileName' => $srcFileName, 'pathInDb' => $pathInDb, 'ownerId' => $this->userId, 'extension' => $extension];
                $this->fileModel->addFile($fileData);
            }
        }
    }

    public function update($id)
    {
        parse_str(file_get_contents('php://input'), $_PUT);
        //  var_dump(['$_PUT' => $_PUT]);
        $toFileDirectory = 'uploads/';

        if (!empty(trim($_PUT['directory']))) {
            // проверить доступ к папке
            $dirInfo = $this->fileModel->getDirInfoByName(trim($_PUT['directory']));
            if ($dirInfo) {
                if (($dirInfo->user_owner_id === $this->userId) || $this->isAdmin) {
                    $toFileDirectory = 'uploads/' . trim($_PUT['directory']) . '/';
                } else {
                    echo 'У вас нет доступа к файлу';
                    return;
                }
            }
            // проверить доступ к папке
        }

        if ($this->isAdmin) {
            $file = $this->fileModel->findFileById($id);
        } else {
            $file = $this->fileModel->findOneFile($id, $this->userId);
        }
        if ($file && is_dir($toFileDirectory)) {
            //   var_dump(['$file' => $file]);
            if (!empty($file->directory)) {
                $fromFileDirectory = 'uploads/' . $file->directory . '/' . $file->name;
            } else {
                $fromFileDirectory = 'uploads/' . $file->name;
            }
            //   var_dump(['$fromFileDirectory' => $fromFileDirectory]);
            if (file_exists($fromFileDirectory)) {
                //    var_dump(['$fromFileDirectory' => $fromFileDirectory, 'to' => $toFileDirectory . trim($_PUT['file_name'])]);
                if (!rename($fromFileDirectory, $toFileDirectory . trim($_PUT['file_name']) . '.' . $file->extension)) {
                    echo 'Ошибка при перемещении файла';
                } else {
                    // запись в бд
                    $dir = '';
                    if (!empty(trim($_PUT['directory']))) {
                        $dir = trim($_PUT['directory']);
                    }
                    $fileData = ['id' => $id, 'name' => trim($_PUT['file_name']) . '.' . $file->extension, 'directory' => $dir];
                    $this->fileModel->updateFile($fileData);
                }
            } else {
                echo "Нет такого файла";
            }
        } else {
            echo "Неверный путь";
        }
    }

    public function delete($id)
    {
        if ($this->isAdmin) {
            $file = $this->fileModel->findFileById($id);
        } else {
            $file = $this->fileModel->findOneFile($id, $this->userId);
        }
        //   var_dump($file);
        if ($file) {
            $path = self::$uploadsDir . $file->directory . $file->name;
            //   var_dump($path);
            if (file_exists($path)) {
                if (unlink($path)) {
                    $this->fileModel->deleteFile($id);
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
        $dirOwner = false;
        $dirName = false;
        $dirInfo = $this->fileModel->getDirInfo($id);
        if ($dirInfo) {
            $dirOwner = $dirInfo->user_owner_id;
            $dirName = $dirInfo->name;
        }
        if ($dirName && ($this->isAdmin || ($dirOwner === $this->userId))) {
            if (file_exists("uploads/" . $dirName) and is_dir("uploads/" . $dirName)) {
                $files = array_diff(scandir("uploads/" . $dirName), array('.', '..'));
                if (!empty($files)) {
                    $files = array_values($files);
                }
                echo json_encode($files);
            } else {
                echo "Папка не найдена";
            }
        }
    }

    public function addDirectory()
    {
        if (!empty(trim($_POST['directory_name'])) && (!is_dir('uploads/' . trim($_POST['directory_name'])))) {
            mkdir("uploads/" . trim($_POST['directory_name']));
            $directoryData = [
                'srcFileName' => trim($_POST['directory_name']),
                'pathInDb' => '',
                'ownerId' => $this->userId,
                'extension' => ''
            ];
            $this->fileModel->addFile($directoryData);
        }
    }

    public function renameDirectory($id)
    {
        parse_str(file_get_contents('php://input'), $_PUT);
        var_dump($_PUT);
        if (!empty(trim($_PUT['directory_name']))) {
            // проверка доступа
            $dirInfo = $this->fileModel->getDirInfo($id);
            var_dump($dirInfo);
            if ($dirInfo) {
                if (($dirInfo->user_owner_id === $this->userId) || $this->isAdmin) {
                    var_dump('inside');
                    $oldName = $dirInfo->name;
                    if (file_exists(self::$uploadsDir . $dirInfo->name) and is_dir(self::$uploadsDir . $dirInfo->name)) {
                        var_dump('file_exists');
                        if (rename(self::$uploadsDir . $dirInfo->name, self::$uploadsDir . trim($_PUT['directory_name']))) {
                            // меняем в бд
                            $this->fileModel->renameDirectoryInFiles($oldName, trim($_PUT['directory_name']));
                            $this->fileModel->updateDirectory($id, trim($_PUT['directory_name']));
                        } else {
                            echo "Не удалось переименовать";
                        }
                    }
                }
            }
            // проверка доступа
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
        $dirInfo = $this->fileModel->getDirInfo($id);
        if ($dirInfo) {
            $path = 'uploads/' . $dirInfo->name;
            if (file_exists($path) and is_dir($path)) {
                    if (($dirInfo->user_owner_id === $this->userId) || $this->isAdmin) {
                        if ($this->fileModel->deleteDirectory($id, $dirInfo->name)) {
                            $this->RDir($path);
                        } else {
                            echo 'Что-то пошло не так';
                        }
                    }
            }
        }
    }

    // SHARE

    public function shareList($id)
    {
        $shareList = $this->shareModel->shareList($id);
        echo json_encode($shareList);
    }

    public function shareFile($fileId, $userId)
    {
        $file = $this->fileModel->findOneFile($fileId, $this->userId);
        $user = $this->userModel->findOneUser($userId);
      //  var_dump(['user' => $user, 'file' => $file]);
        if ($file && $user) {
            $this->shareModel->shareFileInDb($fileId, $userId);
        } else {
            echo "Такого файла или пользователя не существует";
        }
    }

    public function unshareFile($fileId, $userId)
    {
        $file = $this->fileModel->findOneFile($fileId, $this->userId);
        $user = $this->userModel->findOneUser($userId);
        if ($file && $user) {
            $this->shareModel->unshareFileInDb($fileId, $userId);
        } else {
            echo "Такого файла или пользователя не существует";
        }
    }
}