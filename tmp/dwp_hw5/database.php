<?php
// 111550008 蔡東霖 第5次作業 12/6
// 111550008 Tony Tsai The Fifth Homework 12/6

class Database {
    private static $instance = null;
    private $connection;

    private function __construct() {
        $generator = $this->getDB();
        $this->connection = $generator->current();

        register_shutdown_function(function () use ($generator) {
            $generator->next();
        });
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function getDB() {
        $servername = "localhost";
        $username = "dwp_hw5";
        $password = "dwp_hw5";
        $dbname = "dwp_hw5";
    
        $conn = new mysqli($servername, $username, $password, $dbname);
    
        if ($conn->connect_error) {
            die("連線失敗: " . $conn->connect_error);
        }
    
        try {
            yield $conn;
        }
        finally {
            $conn->close();
        }
    }

    public function where($table, $conditions = []) {
        if ($table != 'users' && $table != 'games') {
            throw new Exception("Invalid table name");
        }

        $sql = "SELECT * FROM `$table`";
        $values = [];
        if (!empty($conditions)) {
            $whereClauses = [];
            foreach ($conditions as $key => $value) {
                $whereClauses[] = "`$key` = ?";
                $values[] = $value;
            }
            $sql .= " WHERE " . implode(" AND ", $whereClauses);
        }

        $stmt = $this->connection->prepare($sql);
        if ($stmt === false) {
            throw new Exception("SQL prepare failed: " . $this->connection->error);
        }

        if (!empty($values)) {
            $types = '';
            foreach ($conditions as $value) {
                $types .= is_int($value) ? 'i' : (is_float($value) ? 'd' : 's');
            }
            $stmt->bind_param($types, ...$values);
        }

        $stmt->execute();

        $result = $stmt->get_result();
        if ($result === false) {
            throw new Exception("SQL execution failed: " . $stmt->error);
        }

        $rows = [];
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }

        $stmt->close();
        return $rows;
    }

    public function add($table, $data = []) {
        if ($table != 'users' && $table != 'games') {
            throw new Exception("Invalid table name");
        }

        if (empty($data) || !is_array($data)) {
            throw new Exception("Invalid data: must be a non-empty associative array");
        }

        $columns = implode("`, `", array_keys($data));
        $placeholders = implode(", ", array_fill(0, count($data), "?"));
        $sql = "INSERT INTO `$table` (`$columns`) VALUES ($placeholders)";

        $stmt = $this->connection->prepare($sql);
        if ($stmt === false) {
            throw new Exception("SQL prepare failed: " . $this->connection->error);
        }

        $types = '';
        foreach ($data as $value) {
            $types .= is_int($value) ? 'i' : (is_float($value) ? 'd' : 's');
        }
        $values = array_values($data);
        $stmt->bind_param($types, ...$values);

        if (!$stmt->execute()) {
            throw new Exception("SQL execution failed: " . $stmt->error);
        }

        $insertId = $stmt->insert_id;

        $stmt->close();

        return $insertId;
    }

    public function update($table, $conditions = [], $data = []) {
        if (empty($table) || empty($conditions) || empty($data)) {
            throw new InvalidArgumentException("Table, conditions, and data cannot be empty.");
        }

        if ($table != 'users' && $table != 'games') {
            throw new Exception("Invalid table name");
        }
    
        $setClauses = [];
        foreach ($data as $key => $value) {
            $setClauses[] = "$key = ?";
        }
        $setSql = implode(", ", $setClauses);
    
        $conditionClauses = [];
        foreach ($conditions as $key => $value) {
            $conditionClauses[] = "$key = ?";
        }
        $conditionSql = implode(" AND ", $conditionClauses);
    
        $sql = "UPDATE $table SET $setSql WHERE $conditionSql";
    
        $stmt = $this->connection->prepare($sql);

        if ($stmt === false) {
            die('Failed to prepare the SQL query. Error: ' . $this->connection->error);
        }
    
        $types = '';
        $values = [];
        foreach ($data as $key => $value) {
            $types .= is_int($value) ? 'i' : (is_float($value) ? 'd' : 's');
            $values []= $value;
        }
        foreach ($conditions as $key => $value) {
            $types .= is_int($value) ? 'i' : (is_float($value) ? 'd' : 's');
            $values []= $value;
        }
        $stmt->bind_param($types, ...$values);
    
    
        try {
            return $stmt->execute();
        } catch (PDOException $e) {
            throw new RuntimeException("Database update failed: " . $e->getMessage());
        }
    }
}
?>
