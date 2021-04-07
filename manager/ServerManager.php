<?php


namespace manager;


class ServerManager
{
    /**
     * @var \DatabaseConnexion
     */
    protected $dbConn;

    public function __construct()
    {
        $this->dbConn = new \DatabaseConnexion();
    }

    public function listServers(): array
    {
        $query = $this->dbConn->getConnexion()->prepare('SELECT id, name, INET_NTOA(ip) ip FROM server');
        $query->execute();
        return $query->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function addServer($name, $ip)
    {
        if (!filter_var($ip, FILTER_VALIDATE_IP)) {
            throw new \Exception('Please provide a correct IP address');
        }
        $name = self::sanitizeName($name);
        if ($name === '') {
            throw new \Exception('Please provide a name, allowed char are alphanumeric - _ . and space');
        }

        $query = $this->dbConn->getConnexion()->prepare('INSERT INTO server(name, ip) VALUES(:name, INET_ATON(:ip))');
        $query->execute([
            'name' => $name,
            'ip' => $ip,
        ]);

        return true;
    }

    public function deleteServer(int $id)
    {
        $query = $this->dbConn->getConnexion()->prepare('DELETE FROM server WHERE id = :id');
        $query->execute([
            'id' => $id,
        ]);

        return true;
    }

    public function renameServer(int $id, $name)
    {
        $name = self::sanitizeName($name);
        if ($name === '') {
            throw new \Exception('Please provide a name, allowed char are alphanumeric - _ . and space');
        }
        $query = $this->dbConn->getConnexion()->prepare('UPDATE server SET name = :name WHERE id = :id');
        $query->execute([
            'id' => $id,
            'name' => $name,
        ]);

        return true;
    }

    /**
     * Return $name after removing everything that is not : alphanumeric - _ . or space
     * @param $name
     * @return string|null
     */
    public static function sanitizeName($name) {
        return preg_replace('/[^\w\-_. ]/', '', $name);
    }
}