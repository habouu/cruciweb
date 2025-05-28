<?php

class DB extends PDO
{
    /*private const db_host = 'localhost';
    private const db_name = 'cruciweb';
    private const db_user = 'habou';
    private const db_pass = 'h@b0u';*/
    private static $pdo;

    // constructeur privée pour ne pas à avoir instancier avec new DB()
    private function __construct()
    {
        $config = require __DIR__ . '/config.php';

        $dsn = 'mysql:host=' . $config['db_host'] . ';dbname=' . $config['db_name'];
        try {
            parent::__construct($dsn, $config['db_user'], $config['db_pass']);
            $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die('DB KO: ' . $e->getMessage());
        }
    }

    // création d'une instance unique de PDO (mode singleton)
    public static function getInstance()
    {
        if (self::$pdo === null) {
            self::$pdo = new self();
        }
        return self::$pdo;
    }
}
