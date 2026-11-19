<?php
/**
 * Database - thin PDO singleton wrapper.
 * Usage:
 *   $db = Database::get();
 *   $rows = $db->all("SELECT * FROM members WHERE role = ?", ['student']);
 */
class Database
{
    private static ?Database $instance = null;
    private PDO $pdo;

    private function __construct()
    {
        $dsn = sprintf(
            'mysql:host=%s;port=%s;dbname=%s;charset=%s',
            DB_HOST, DB_PORT, DB_NAME, DB_CHARSET
        );
        $opts = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci",
        ];
        try {
            $this->pdo = new PDO($dsn, DB_USER, DB_PASS, $opts);
        } catch (PDOException $e) {
            if (APP_DEBUG) {
                die('DB connection failed: ' . htmlspecialchars($e->getMessage()));
            }
            die('Database connection error. Please contact administrator.');
        }
    }

    public static function get(): Database
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function pdo(): PDO { return $this->pdo; }

    public function run(string $sql, array $params = []): PDOStatement
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    public function all(string $sql, array $params = []): array
    {
        return $this->run($sql, $params)->fetchAll();
    }

    public function one(string $sql, array $params = []): ?array
    {
        $row = $this->run($sql, $params)->fetch();
        return $row ?: null;
    }

    public function value(string $sql, array $params = [])
    {
        $stmt = $this->run($sql, $params);
        $v = $stmt->fetchColumn();
        return $v === false ? null : $v;
    }

    public function insert(string $table, array $data): int
    {
        $cols   = array_keys($data);
        $place  = array_map(fn($c) => ':' . $c, $cols);
        $sql    = sprintf(
            'INSERT INTO `%s` (`%s`) VALUES (%s)',
            $table, implode('`,`', $cols), implode(',', $place)
        );
        $this->run($sql, $data);
        return (int) $this->pdo->lastInsertId();
    }

    public function update(string $table, array $data, string $where, array $whereParams = []): int
    {
        $set = [];
        foreach (array_keys($data) as $c) $set[] = "`$c` = :$c";
        $sql = sprintf('UPDATE `%s` SET %s WHERE %s', $table, implode(',', $set), $where);
        return $this->run($sql, array_merge($data, $whereParams))->rowCount();
    }

    public function delete(string $table, string $where, array $params = []): int
    {
        $sql = sprintf('DELETE FROM `%s` WHERE %s', $table, $where);
        return $this->run($sql, $params)->rowCount();
    }
}
