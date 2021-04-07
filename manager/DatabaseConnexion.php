<?php


class DatabaseConnexion
{
    /**
     * @var PDO
     */
    protected $connexion;

    public function __construct()
    {
        try {
            $this->connexion = new PDO('mysql:host=' . SQL_HOST . ';dbname=' . SQL_DATABASE, SQL_USERNAME, SQL_PASSWORD);
        } catch (Exception $e) {
            die('DB is gone'); // Test purpose
        }
//        $this->connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//        $this->connexion->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    }

    /**
     * @return PDO
     */
    public function getConnexion(): PDO
    {
        return $this->connexion;
    }
}