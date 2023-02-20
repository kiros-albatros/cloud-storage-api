<?php

class Db
{
    private PDO $pdo;
    private string $error;
    private $stmt;

    public function __construct()
    {
        $dbData = (require __DIR__ . '/../../settings.php')['db'];

        try {
            $this->pdo = new \PDO(
                'mysql:host=' . $dbData['host'] . ';
            dbname=' . $dbData['dbname'],
                $dbData['user'],
                $dbData['password'],
                $dbData['options']
            );
            $this->pdo->exec('SET NAMES UTF8');
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            echo $this->error;
        }
    }

    // Prepare statement with query
    public function query($sql){
        $this->stmt = $this->pdo->prepare($sql);
    }

    // Bind values
    public function bind($param, $value, $type = null){
        if(is_null($type)){
            switch(true){
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
        }

        $this->stmt->bindValue($param, $value, $type);
    }

    // Execute the prepared statement
    public function execute(){
        return $this->stmt->execute();
    }

    // Get result set as array of objects
    public function resultSet(){
        $this->execute();
        return $this->stmt->fetchAll(PDO::FETCH_OBJ);
    }

    // Get single record as object
    public function single(){
        $this->execute();
        return $this->stmt->fetch(PDO::FETCH_OBJ);
    }

    public function getById($id, $tableName)
    {
        $this->query("SELECT * FROM `{$tableName}` WHERE id = :id");
        $this->bind(':id', $id);
        $item = $this->single();
        if ($item) {
            return $item;
        } else {
            return false;
        }
    }
}

