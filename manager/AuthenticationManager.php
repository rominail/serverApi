<?php

namespace manager;

use Lcobucci\Clock\SystemClock;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;

class AuthenticationManager
{
    /**
     * @var \DatabaseConnexion
     */
    protected $dbConn;

    /**
     * @var bool
     */
    protected $successfulLogin = false;

    /**
     * @var string|null
     */
    protected $jwt = null;

    /**
     * @var \Lcobucci\JWT\Configuration
     */
    protected $jwtConfiguration;

    /**
     * @var int|null
     */
    protected $currentUserId = null;

    public function __construct($key)
    {
        $this->dbConn = new \DatabaseConnexion();
        $this->jwtConfiguration = \Lcobucci\JWT\Configuration::forSymmetricSigner(new Sha256(), InMemory::plainText($key));
        $this->jwtConfiguration->setValidationConstraints(
            new \Lcobucci\JWT\Validation\Constraint\LooseValidAt(SystemClock::fromSystemTimezone()),
            new \Lcobucci\JWT\Validation\Constraint\SignedWith(new Sha256(), InMemory::plainText($key))
        );
    }

    /**
     * Return if the given credentials are corrects, if yes, the user is authenticated and generate a JWT
     * @param $name
     * @param $password
     * @return bool
     */
    public function isValidLogin($name, $password): bool
    {
        $query = $this->dbConn->getConnexion()->prepare('SELECT * FROM user WHERE name = :name');
        $query->execute(['name' => $name]);
        $result = $query->fetchAll(\PDO::FETCH_ASSOC);
        if (count($result) > 0 && password_verify($password, $result[0]['password'])) {
            $this->successfulLogin = true;
            $this->setCurrentUserId($result[0]['id']);
            $this->generateJwt();
            return true;
        }
        return false;
    }

    public function isLogged(): bool
    {
        return $this->successfulLogin;
    }

    /**
     * Given a jwt connect the user if the jwt is valid (signed and not expired)
     * @param $jwt
     */
    public function connect($jwt): void
    {
        try {
            $token = $this->jwtConfiguration->parser()->parse($jwt);
        } catch (\InvalidArgumentException $e) {
            $this->successfulLogin = false;
            return;
        }

        $constraints = $this->jwtConfiguration->validationConstraints();

        if (!$this->jwtConfiguration->validator()->validate($token, ...$constraints)) {
            $this->successfulLogin = false;
        } else {
            $this->jwt = $jwt;
            $this->successfulLogin = true;
            $this->currentUserId = (int) $token->claims()->get('uid');
        }
    }

    public function generateJwt(?int $expire = 300): void
    {
        $expireDatetime = new \DateTimeImmutable('now + ' . $expire . ' seconds');
        $token = $this->jwtConfiguration->builder()
            ->issuedBy('https://seedbox.com')
            ->expiresAt($expireDatetime)
            ->withClaim('uid', $this->currentUserId)
            ->getToken($this->jwtConfiguration->signer(), $this->jwtConfiguration->signingKey());

        $this->jwt = $token->toString();
    }

    public function getJwt(): ?string
    {
        return $this->jwt;
    }

    public function setJwt(string $jwt): self
    {
        $this->jwt = $jwt;
        return $this;
    }

    public function getCurrentUserId(): ?int
    {
        return $this->currentUserId;
    }

    public function setCurrentUserId(?int $currentUserId): self
    {
        $this->currentUserId = $currentUserId;
        return $this;
    }
}