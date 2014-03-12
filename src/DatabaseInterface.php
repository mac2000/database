<?php
namespace Mac\Database;

interface DatabaseInterface
{
    /**
     * @param string $sql Query to execute, i.e. "SELECT * FROM category WHERE category_id = :category_id"
     * @param array $params Query parameters, i.e. array('category_id' => 1)
     * @return array
     */
    public function all($sql, array $params = array());

    /**
     * @param string $sql Query to execute, i.e. "SELECT COUNT(*) FROM category WHERE category_id > :category_id"
     * @param array $params Query parameters, i.e. array('category_id' => 1)
     * @return array
     */
    public function one($sql, array $params = array());

    /**
     * @param string $sql Query to execute, i.e. "SELECT COUNT(*) FROM category WHERE category_id > :category_id"
     * @param array $params Query parameters, i.e. array('category_id' => 1)
     * @return mixed|null
     */
    public function cell($sql, array $params = array());

    /**
     * @param string $sql Query to execute, i.e. "DELETE FROM category WHERE category_id > :category_id"
     * @param array $params Query parameters, i.e. array('category_id' => 1)
     * @return int Will return number of affected rows or last insert id in case of adding new row
     */
    public function execute($sql, array $params = array());
}
