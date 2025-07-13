<?php
class Database {
    private static $host = "localhost";
    private static $user = "root";
    private static $pass = "";
    private static $db = "sorteo";
    public static function connect() {
        return new PDO(
            "mysql:host=".self::$host.";dbname=".self::$db,
            self::$user,
            self::$pass
        );
    }
}
?>
