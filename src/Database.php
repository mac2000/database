<?php
namespace Mac\Database;

use PDO;
use PDOStatement;

/**
 * Class Database is a PDO helper to perform some common operations on database
 * @package Mac
 */
class Database implements DatabaseInterface
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
     * {@inheritdoc}
     */
    public function all($sql, array $params = array())
    {
        $stmt = $this->invoke($sql, $params);
        return $stmt->fetchAll($this->fetchMode);
    }

    /**
     * {@inheritdoc}
     */
    public function one($sql, array $params = array())
    {
        $stmt = $this->invoke($sql, $params);
        return $stmt->fetch($this->fetchMode);
    }

    /**
     * {@inheritdoc}
     */
    public function cell($sql, array $params = array())
    {
        $row = $this->one($sql, $params);
        $row = array_values((array)$row);
        return array_shift($row);
    }

    /**
     * {@inheritdoc}
     */
    public function execute($sql, array $params = array())
    {
        $stmt = $this->invoke($sql, $params);
        return preg_match('/insert/i', $sql) ? $this->pdo->lastInsertId() : $stmt->rowCount();
    }

    /**
     * @param string $sql Query to execute, i.e. "DELETE FROM category WHERE category_id > :category_id"
     * @param array $params Query parameters, i.e. array('category_id' => 1)
     * @return PDOStatement
     */
    protected function invoke($sql, array $params = array())
    {
        $stmt = $this->pdo->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue(':' . $key, $value, $this->getParameterDataType($value));
        }
        $stmt->execute();
        return $stmt;
    }

    /**
     * Get PDO parameter type
     *
     * @SuppressWarnings(StaticAccess)
     * @param $value
     * @return int parameter data type
     */
    protected function getParameterDataType($value)
    {
        switch (gettype($value)) {
            case 'integer':
                return PDO::PARAM_INT;
            case 'boolean':
                return PDO::PARAM_BOOL;
            case 'NULL':
                return PDO::PARAM_NULL;
            default:
                return PDO::PARAM_STR;
        }
    }
}
