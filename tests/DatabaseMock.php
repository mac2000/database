<?php
namespace Mac\Database\Tests;

use Mac\Database\Database;

class DatabaseMock extends Database
{
    public function getType($value)
    {
        return $this->getParameterDataType($value);
    }
}
