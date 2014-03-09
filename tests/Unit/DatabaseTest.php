<?php
namespace Mac\Database\Tests\Unit;

use Mac\Database\Database;
use PHPUnit_Framework_MockObject_MockObject;
use PHPUnit_Framework_TestCase;

class DatabaseTest extends PHPUnit_Framework_TestCase
{
    protected $pdo;
    protected $stmt;
    /**
     * @var Database
     */
    protected $database;

    protected $sqlWithoutParameters = "SELECT * FROM category";
    protected $sqlWithParameters = "SELECT * FROM category WHERE category_id > :category_id";
    protected $sqlDelete = "DELETE FROM category WHERE category_id = :category_id";
    protected $sqlInsert = "INSERT INTO category (name) VALUES(:name)";

    protected $params = array('category_id' => 1);
    protected $sqlInsertParams = array('name' => 'Hello World!');

    public function setUp()
    {
        $this->pdo = $this->getMockBuilder('Mac\Database\Tests\PDOMock')->getMock();
        $this->stmt = $this->getMockBuilder('PDOStatement')->getMock();
        $this->database = new Database($this->pdo);
    }

    public function testAll()
    {
        $this->setMockExpectations($this->stmt, 'execute');
        $this->setMockExpectations($this->stmt, 'fetchAll');
        $this->setMockExpectations($this->pdo, 'prepare', $this->sqlWithoutParameters, $this->stmt);

        $this->database->all($this->sqlWithoutParameters);
    }

    public function testAllWithParams()
    {
        $this->setMockExpectations($this->stmt, 'execute', $this->params);
        $this->setMockExpectations($this->stmt, 'fetchAll');
        $this->setMockExpectations($this->pdo, 'prepare', $this->sqlWithParameters, $this->stmt);

        $this->database->all($this->sqlWithParameters, $this->params);
    }

    public function testOne()
    {
        $this->setMockExpectations($this->stmt, 'execute');
        $this->setMockExpectations($this->stmt, 'fetch');
        $this->setMockExpectations($this->pdo, 'prepare', $this->sqlWithoutParameters, $this->stmt);

        $this->database->one($this->sqlWithoutParameters);
    }

    public function testCell()
    {
        $this->setMockExpectations($this->stmt, 'execute');
        $this->setMockExpectations($this->stmt, 'fetch');
        $this->setMockExpectations($this->pdo, 'prepare', $this->sqlWithoutParameters, $this->stmt);

        $this->database->cell($this->sqlWithoutParameters);
    }

    public function testOneWithParams()
    {
        $this->setMockExpectations($this->stmt, 'execute', $this->params);
        $this->setMockExpectations($this->stmt, 'fetch');
        $this->setMockExpectations($this->pdo, 'prepare', $this->sqlWithParameters, $this->stmt);

        $this->database->one($this->sqlWithParameters, $this->params);
    }

    public function testExecute()
    {
        $this->setMockExpectations($this->stmt, 'execute', $this->params);
        $this->setMockExpectations($this->stmt, 'rowCount');
        $this->setMockExpectations($this->pdo, 'prepare', $this->sqlDelete, $this->stmt);

        $this->database->execute($this->sqlDelete, $this->params);
    }

    public function testInsertExecute()
    {
        $this->setMockExpectations($this->stmt, 'execute', $this->sqlInsertParams);
        $this->setMockExpectations($this->pdo, 'prepare', $this->sqlInsert, $this->stmt);
        $this->setMockExpectations($this->pdo, 'lastInsertId');

        $this->database->execute($this->sqlInsert, $this->sqlInsertParams);
    }

    protected function setMockExpectations(
        PHPUnit_Framework_MockObject_MockObject $mock,
        $method,
        $params = null,
        $returnValue = null
    ) {

        $inv = $mock->expects($this->once())->method($method);

        if ($params) {
            $inv = $inv->with($params);
        }

        if ($returnValue) {
            $inv->will($this->returnValue($returnValue));
        }
    }
}
