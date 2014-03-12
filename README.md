Database PDO Helper
===================

[![Build Status](https://travis-ci.org/mac2000/database.png?branch=master)](https://travis-ci.org/mac2000/database)

PDO Helper implements some common database operations

Installation
------------

Here is `composer.json` example:

    {
        "require": {
            "mac/database": "x"
        },
        "repositories": [
            {
                "type": "vcs",
                "url": "https://github.com/mac2000/database"
            }
        ]
    }

Usage example
-------------

First of all you need some database connection:

    $db = new Database(new PDO('mysql:host=localhost;dbname=example', 'root', 'root'));
    // or for test purposes
    $db = new Database(new PDO('sqlite::memory:'));

Retrieve all/one row(s):

    $users = $db->all("SELECT * FROM users");
    $user = $db->one("SELECT * FROM users WHERE user_id = :user_id", array('user_id' => 1));

Retrieve computed value:

    $count = $db->cell("SELECT COUNT(*) FROM users");

Modify data:

    $lastInsertId = $db->execute(
        "INSERT INTO users (first_name, last_name, age) VALUES(:first_name, :last_name, :age)",
        array('first_name' => 'Hello', 'last_name' => 'World', 'age' => 9)
    );

    $rowsAffectedCount = $db->execute(
        "DELETE FROM users WHERE user_id = :user_id",
        array('user_id' => 2)
    );

Run tests
---------

Look at `.travis.yml` there is few tests that are runned:

    vendor/bin/phpunit
    vendor/bin/phpcpd src tests
    vendor/bin/phpmd src,tests text cleancode, codesize, controversial, design, naming, unusedcode
    vendor/bin/phpcs --standard=psr2 src tests