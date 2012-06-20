<?php
abstract class DataMapper
{
    protected $_pdo;

    function __construct(PDO $pdo)
    {
        $this->_pdo = $pdo;
    }

    protected function _decorate(PDOStatement $stmt)
    {
        $stmt->setFetchMode(PDO::FETCH_CLASS, static::MODEL_CLASS);
        return $stmt;
    }
}
