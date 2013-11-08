Database PDO Helper
===================

PDO Helper implements some common database operations

Usage example
-------------

First of all you need some database connection:

    $db = new Database(new PDO('mysql:host=localhost;dbname=example', 'root', 'root'));

Retrieve all/one row(s):

    $users = $db->all("SELECT * FROM users");
    $user = $db->one("SELECT * FROM users WHERE user_id = :user_id", array('user_id' => 1));

Modify data:

    $lastInsertId = $db->execute("INSERT INTO users (first_name, last_name, age) VALUES(:first_name, :last_name, :age)", array('first_name' => 'Hello', 'last_name' => 'World', 'age' => 9));
    $rowsAffectedCount = $db->execute("DELETE FROM users WHERE user_id = :user_id", array('user_id' => 2));
