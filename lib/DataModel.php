<?php
abstract class DataModel
{
    const
        BOOLEAN = 'boolean'
      , INTEGER = 'integer'
      , DOUBLE  = 'double'
      , FLOAT   = 'double'
      , STRING  = 'string'
      , DATETIME = 'dateTime'
      ;

    protected $_data = array();
    protected static $_schema = array();

    function __get($prop) {
        if (isset($this->_data[$prop])) {
            return $this->_data[$prop];
        } elseif (isset(static::$_schema[$prop])) {
            return null;
        } else {
            throw new InvalidArgumentException;
        }
    }

    function __isset($prop) {
        return isset($this->_data[$prop]);
    }

    function __set($prop, $val) {
        if (!isset(static::$_schema[$prop])) {
            throw new InvalidArgumentException($prop.'はセットできません');
        }

        $schema = static::$_schema[$prop];
        $type = gettype($val);

        if ($schema === self::DATETIME) {
            if ($val instanceof DateTime) {
                $this->_data[$prop] = $val;
            } else {
                $this->_data[$prop] = new DateTime($val);
            }
            return;
        }

        if ($type === $schema) {
            $this->_data[$prop] = $val;
            return;
        }

        switch ($schema) {
            case self::BOOLEAN:
                return $this->_data[$prop] = (bool)$val;
            case self::INTEGER:
                return $this->_data[$prop] = (int)$val;
            case self::DOUBLE:
                return $this->_data[$prop] = (double)$val;
            case self::STRING:
            default:
                return $this->_data[$prop] = (string)$val;
        }
    }

    function toArray() {
        return $this->_data;
    }

    function fromArray(array $arr) {
        foreach ($arr as $key => $val) {
            $this->__set($key, $val);
        }
    }

    abstract function isValid();
}
