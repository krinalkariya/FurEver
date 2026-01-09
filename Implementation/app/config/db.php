<?php
class DB {
  private static ?PDO $pdo = null;

  public static function conn(): PDO {
    if (!self::$pdo) {
      // note: DB_HOST already has the port (localhost:3307)
      $dsn = 'mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset=utf8mb4';
      self::$pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
      ]);
    }
    return self::$pdo;
  }
}
