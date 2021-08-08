<?php

namespace DB;

use PDO;
use PDOException;

class DBConnection extends PDO {
    public $username = "root";
    public $password = "";
    public $dsn = 'mysql:host=localhost;dbname=dns_center_bank';

    public function __construct($dsn = "mysql:host=localhost;dbname=dns_center_bank", $username = 'root', $password = '')
    {
        parent::__construct($dsn, $username, $password);
        $this->username = $username;
        $this->password = $password;
        $this->dsn = $dsn;
    }

    /**
     * @return PDO|string
     */
    public function connect()
    {
        try {
            $conn = new PDO($this->dsn, $this->username, $this->password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conn;
        } catch (PDOException $err) {
            return $err->getMessage();
        }
    }
}