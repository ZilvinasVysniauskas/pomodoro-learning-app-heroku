<?php
namespace Ninja;


class DatabaseTable
{
    private $pdo;
    private $table;
    private $primaryKey;
    public function __construct(\PDO $pdo, $table, $primaryKey)
    {
        $this->pdo = $pdo;
        $this->table = $table;
        $this->primaryKey = $primaryKey;
    }
    private function query($sql, $parameters = null){
        echo $sql . '<br>';
        $query = $this->pdo->prepare($sql);
        $query->execute($parameters);
        return $query;
    }
    public function findById($value = null){
        if ($value !== null){
            $sql = 'SELECT * FROM ' . $this->table . ' WHERE ' . $this->primaryKey . ' = :value';
            $parameters = [
                'value' => $value
            ];
        }else{
            $sql = "SELECT * FROM "  . $this->table;
            $parameters = null;
        }
        $query = $this->query($sql, $parameters);
        return $query->fetchAll();
    }
    public function selectDataFromDb($condition = null){
        $sql = 'SELECT * FROM ' . $this->table;
        if ($condition !== null){
            $sql .= ' WHERE ';
            foreach ($condition as $key => $value){
                $sql .= $key . "=" . "'" . $value . "' AND ";
            }
            $sql = rtrim($sql, 'AND ');
        }
        $query = $this->query($sql);
        return $query->fetchAll();
    }
    public function updateValuesInDb($data, $actionAddOrChange){
        $sql = 'UPDATE ' . $this->table . ' SET ';
        if ($actionAddOrChange == 'add'){
            foreach ($data['set'] as $key => $value){
                $sql .= $key .' = ' . $key . ' + ' . "'" . $value . "'" . ', ';
            }
        }else{
            foreach ($data['set'] as $key => $value){
                if ($value === 'null'){
                    $sql .= $key .' = ' .  $value  . ', ';
                }
                else {
                    $sql .= $key .' = ' .  "'" . $value . "'" . ', ';
                }
            }
        }
        $sql = rtrim($sql, ', ');
        if (isset($data['conditions'])){
            $sql .= ' WHERE ';
            foreach ($data['conditions'] as $key => $value){
                $sql .= $key . " = '" . $value . "' " . 'AND ';
            }
            $sql = rtrim($sql, ' AND');
        }
        $this->query($sql);
    }
    public function insertIntoDb($data){
        $sql = 'INSERT INTO '   . $this->table . ' SET ';
        foreach ($data as $key => $value){
            $sql .=   $key  .  ' = ' .  "'" .$value . "'" . ', ';
        }
        $sql = rtrim($sql, ', ');
        $this->query($sql);

    }
    public function deleteFromDb($condition){
        $sql = 'DELETE FROM ' . $this->table . ' WHERE ';
        foreach ($condition as $key => $value){
            $sql .= $key . ' = ' . "'" . $value . "'" . ' AND ';
        }
        $sql = rtrim($sql, ' AND ');
        $this->query($sql);
    }



}