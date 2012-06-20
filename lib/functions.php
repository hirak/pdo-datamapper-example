<?php
/**
 * PDOインスタンスを管理する関数
 *
 */
function getPDO($env=null) {
    static $pdo = array();

    if (! isset($pdo[$env])) {
        if ($env) {
            $conf = require "db.$env.conf.php";
        } else {
            $conf = require 'db.conf.php';
        }
        $pdo[$env] = new PDO(
            $conf['dsn'],
            $conf['user'],
            $conf['pass'],
            array(
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_CLASS,
            )
        );
        //SQLiteで外部キー制約を有効にする
        $pdo[$env]->query('PRAGMA foreign_keys = ON');
    }

    return $pdo[$env];
}
