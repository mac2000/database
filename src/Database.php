<?php
namespace Mac\Database;

use PDO;

/**
 * Class Database is a PDO helper to perform some common operations on database
 * @package Mac
 */
class Database
{
    /**
     * @var PDO
     */
    protected $pdo;

    /**
     * @var int
     */
    protected $fetchMode;

    /**
     * @param PDO $pdo
     * @param int $fetchMode Default fetch mode to use (PDO::FETCH_OBJ|PDO::FETCH_ASSOC|etc)
     */
    public function __construct(PDO $pdo, $fetchMode = PDO::FETCH_ASSOC)
    {
        $this->pdo = $pdo;
        $this->fetchMode = $fetchMode;
    }

    /**
     * @param string $sql Query to execute, i.e. "SELECT * FROM category WHERE category_id = :category_id"
     * @param array $params Query parameters, i.e. array('category_id' => 1)
     * @return array
     */
    public function all($sql, array $params = array())
    {
        $stmt = $this->invoke($sql, $params);
        return $stmt->fetchAll($this->fetchMode);
    }

    /**
     * @param string $sql Query to execute, i.e. "SELECT COUNT(*) FROM category WHERE category_id > :category_id"
     * @param array $params Query parameters, i.e. array('category_id' => 1)
     * @return array
     */
    public function one($sql, array $params = array())
    {
        $stmt = $this->invoke($sql, $params);
        return $stmt->fetch($this->fetchMode);
    }

    /**
     * @param string $sql Query to execute, i.e. "SELECT COUNT(*) FROM category WHERE category_id > :category_id"
     * @param array $params Query parameters, i.e. array('category_id' => 1)
     * @return mixed|null
     */
    public function cell($sql, array $params = array())
    {
        $stmt = $this->invoke($sql, $params);
        $row = $stmt->fetch($this->fetchMode);

        if (!$row) {
            return null;
        }

        $row = (array)$row;
        $row = array_values($row);

        return array_shift($row);
    }

    /**
     * @param string $sql Query to execute, i.e. "DELETE FROM category WHERE category_id > :category_id"
     * @param array $params Query parameters, i.e. array('category_id' => 1)
     * @return int Will return number of affected rows or last insert id in case of adding new row
     */
    public function execute($sql, array $params = array())
    {
        $stmt = $this->invoke($sql, $params);
        return preg_match('/insert/i', $sql) ? $this->pdo->lastInsertId() : $stmt->rowCount();
    }

    public function delete($table, array $params = array())
    {
        if(empty($params)){
            return $this->execute("DELETE FROM $table");
        } else {

        }

        //return $this->execute("DELETE FROM $table WHERE ")
    }

    protected function invoke($sql, array $params = array())
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }
}
