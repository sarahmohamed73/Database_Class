<?php
echo "<pre>";
  class Database {
    // 1- Properties
    private $dbName;
    private $tableName;
    private $connection;

    // 2- Constructor 
    public Function __construct($dbName, $tableName)
    {
      $this -> tableName = $tableName;
      $this -> dbName = $dbName;
      $this -> connect();
    }

    // 3- Connect
    public function connect()
    {
      $this -> connection = new mysqli("localhost", "root", "", $this -> dbName);
    }

    // 4- Read All
    public function readAll()
    {
      $select = "SELECT * FROM {$this -> tableName}";
      $query = $this -> connection -> query($select);
      $data = [];
      foreach($query as $item) {
        $data[] = $item;
      }
      return $data;
    }

    // 5- Read with Condition
    public function read($columnName, $value)
    {
      $select = "SELECT * FROM {$this -> tableName} WHERE $columnName IN ($value)";
      $query = $this -> connection -> query($select);
      $data = [];
      foreach($query as $item) {
        if($query -> num_rows > 1) {
          $data[] = $item;
        } else {
          $data = $item;
        }
      }
      return $data;
    }

    // 6- Delete 
    public function delete($columnName, $value)
    {
      $delete = "DELETE FROM {$this -> tableName} WHERE $columnName IN ($value)";
      $query = $this -> connection -> query($delete);
      if($query) {
        echo "<p style='width: 100%; text-align: center; font-size: 20px; color: tomato;'>The Row Has Been Deleted Successfully</p>";
        echo "";
        return $this -> readAll();
      } else {
        return $this -> connection -> error;
      }
    }

    // 7- Insert 
    public function insert($row) {
      $columns = implode(",", array_keys($row));
      $values = implode("','", array_values($row));
      $insert = "INSERT INTO {$this -> tableName} ({$columns}) VALUES ('{$values}')";
      $query = $this -> connection -> query($insert);
      if($query) {
        return $this -> readAll();
      } else {
        return  $this -> connection -> error;
      }
    }

    // 8- Update 
    public function update($row, $columnName, $value) {
      $updateRow = [];
      foreach($row as $key => $rowValue) {
        $updateRow[$key] = "{$key} = '{$rowValue}'";
      }
      $values = implode(", ", $updateRow);
      $update = "UPDATE {$this -> tableName} SET {$values} WHERE {$columnName} = {$value}";
      $query = $this -> connection -> query($update);
      if($query) {
        return $this -> readAll();
      } else {
        return  $this -> connection -> error;
      }
    }
  } // End Class

  $admins = new Database('focus','admins');

  echo "<p style='width: 100%; text-align: center; font-size: 30px; color: dodgerblue;'>Read All</p>";
  print_r($admins -> readAll()); // reutrn array
  echo "<hr>";

  echo "<p style='width: 100%; text-align: center; font-size: 30px; color: dodgerblue;'>Read With Condition</p>";
  print_r($admins -> read('id' , 1)); 
  echo "<hr>";

  echo "<p style='width: 100%; text-align: center; font-size: 30px; color: dodgerblue;'>Insert</p>";
  print_r($admins -> insert([
    
    'username' => 'Yara',
    'password' => '098',
    'email' => 'yara@gmail.com',
    'phone' => '0109650932',
    'address' => 'Mansoura',
    'gender' => 0,
    'privliges' => 1
    
  ]));
  echo "<hr>";

  echo "<p style='width: 100%; text-align: center; font-size: 30px; color: dodgerblue;'>Update</p>";
  print_r($admins -> update([
    'username' => 'Mohamed',
    'password' => '456',
    'email' => 'mohamed@gmail.com',
    'phone' => '0104550932',
    'address' => 'Mansoura',
    'gender' => 0,
    'privliges' => 0
  ], "id" , 7));
  echo "<hr>";

  echo "<p style='width: 100%; text-align: center; font-size: 30px; color: dodgerblue;'>Delete</p>";
  print_r($admins -> delete('id' , "6,7"));
  ?>