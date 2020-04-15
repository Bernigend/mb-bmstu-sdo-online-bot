<?php


namespace Bot;


use PDO;

class DataBase
{
    /** @var PDO */
    protected static $instance;

    // закрыли конструктор
    private function __construct() { }

    /**
     * Возврашает единственный экземпляр класса
     *
     * @return PDO
     */
    public static function instance(): PDO
    {
        if (self::$instance === null) {
            $opt = array(
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
                PDO::ATTR_EMULATE_PREPARES   => TRUE,
            );
            $dsn = 'mysql:host=' . getenv('MYSQL_HOST') . ';dbname=' . getenv('MYSQL_DATABASE') . ';charset=' . getenv('MYSQL_CHARSET');
            self::$instance = new PDO($dsn, getenv('MYSQL_USER'), getenv('MYSQL_USER_PASSWORD'), $opt);
        }
        return self::$instance;
    }

    /**
     * Выполняет SQL запрос
     *
     * @param $sql
     * @param array $args
     *
     * @return bool|false|\PDOStatement
     */
    public static function run($sql, $args = [])
    {
        if (!$args)
        {
            return self::instance()->query($sql);
        }
        $stmt = self::instance()->prepare($sql);
        $stmt->execute($args);
        return $stmt;
    }
}