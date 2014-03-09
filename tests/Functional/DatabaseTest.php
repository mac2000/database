<?php
namespace Mac\Database\Tests\Functional;

use Mac\Database\Database;
use PDO;
use PHPUnit_Framework_TestCase;


class DatabaseTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Database
     */
    protected $database;
    protected $pdo;
    protected $stmt;

    protected $sqlWithoutParameters = "SELECT * FROM category";
    protected $sqlWithParameters = "SELECT * FROM category WHERE category_id = :category_id";
    protected $sqlDelete = "DELETE FROM category WHERE category_id = :category_id";
    protected $sqlInsert = "INSERT INTO category (name) VALUES(:name)";

    protected $params = array('category_id' => 1);
    protected $sqlInsertParams = array('name' => 'Hello World!');

    public function setUp()
    {
        $this->pdo = new PDO('sqlite::memory:');
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->database = new Database($this->pdo);

        $this->database->execute("CREATE TABLE category (
            category_id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
            name VARCHAR(50)
        )");

        $this->database->execute("INSERT INTO category VALUES(NULL, :name)", array('name' => 'Hello'));
    }

    public function testAll()
    {
        $categories = $this->database->all($this->sqlWithoutParameters);

        $this->assertCount(1, $categories);
        $this->assertEquals(1, $categories[0]['category_id']);
        $this->assertEquals('Hello', $categories[0]['name']);
    }

    public function testAllWithParams()
    {
        $categories = $this->database->all($this->sqlWithParameters, $this->params);

        $this->assertCount(1, $categories);
        $this->assertEquals(1, $categories[0]['category_id']);
        $this->assertEquals('Hello', $categories[0]['name']);
    }

    public function testOne()
    {
        $category = $this->database->one($this->sqlWithoutParameters);

        $this->assertEquals(1, $category['category_id']);
        $this->assertEquals('Hello', $category['name']);
    }

    public function testCell()
    {
        $result = $this->database->cell('SELECT 1');

        $this->assertEquals(1, $result);
    }

    public function testThatCellMethodReturnsNull()
    {
        $result = $this->database->cell('SELECT NULL');
        $this->assertEquals(null, $result);

        $result = $this->database->cell('SELECT * FROM category WHERE category_id = 0');
        $this->assertEquals(null, $result);
    }

    public function testOneWithParams()
    {
        $category = $this->database->one($this->sqlWithParameters, $this->params);

        $this->assertEquals(1, $category['category_id']);
        $this->assertEquals('Hello', $category['name']);
    }

    public function testExecute()
    {
        $rowCount = $this->database->execute($this->sqlDelete, $this->params);
        $categories = $this->database->all($this->sqlWithoutParameters);

        $this->assertEquals(1, $rowCount);
        $this->assertEmpty($categories);
    }

    public function testInsertExecute()
    {
        $lastInsertId = $this->database->execute($this->sqlInsert, $this->sqlInsertParams);
        $categories = $this->database->all($this->sqlWithoutParameters);

        $this->assertEquals(2, $lastInsertId);
        $this->assertCount(2, $categories);
        $this->assertEquals(2, $categories[1]['category_id']);
    }
}
