<?php

namespace App;

use App\Exceptions\DbException;
use App\Singleton;
use App\Config;

class Db
{
    use Singleton;

    protected \PDO $dbh;

    protected function __construct()
    {
        $config = Config::instance();
        try {
            $dsn = 'pgsql:host=' . $config->data['db']['host'] . ';dbname=' . $config->data['db']['dbname'];
            $this->dbh = new \PDO($dsn, $config->data['db']['user'], $config->data['db']['password']);
        } catch (\PDOException $exception) {
            throw new DbException('Нет соединения с БД!');
        }
    }

    public function query($sql, $class, $params = []): array
    {
        $sth = $this->dbh->prepare($sql);
        $res = $sth->execute($params);
        if (!$res) {
            throw new DbException('Ошибка при выполнении запроса: ' . $sql);
        }
        return $sth->fetchAll(\PDO::FETCH_CLASS, $class);
    }

    public function execute($sql, $params = []): bool
    {
        $sth = $this->dbh->prepare($sql);
        $res = $sth->execute($params);
        if (!$res) {
            throw new DbException('Ошибка при выполнении запроса: ' . $sql);
        }
        return $res;
    }

    public function lastId()
    {
        $res = $this->dbh->lastInsertId();
        if (!$res) {
            throw new DbException('Ошибка при выполнении запроса');
        }
        return $res;
    }
}