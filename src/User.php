<?php


namespace Bot;


use Bot\API\API;

class User
{
    /** @var integer */
    protected $id;

    /** @var string */
    protected $login;

    /** @var string */
    protected $passwordHash;

    /** @var string */
    protected $key;

    /** @var string */
    protected $clientID;

    /** @var string */
    protected $session;

    /**
     * User constructor.
     *
     * @param string $login
     * @param string $passwordHash
     * @param string $clientID
     * @param string $key
     * @param string|null $session
     */
    protected function __construct(
        string $login,
        string $passwordHash,
        string $clientID,
        string $key,
        ?string $session = null
    )
    {
        $this->login        = $login;
        $this->passwordHash = $passwordHash;
        $this->clientID     = $clientID;
        $this->key          = $key;
        $this->session      = $session;
    }

    /**
     * Создаёт объект класса по логину и паролю пользователя (без авторизационной сессии)
     *
     * @param string $login
     * @param string $password
     *
     * @return User
     */
    public static function createFromLoginPassword(string $login, string $password): User
    {
        $passwordHash = static::hash($password);
        $clientID     = static::guidGenerator();
        $key          = static::hash(API::API_VERSION . $passwordHash . $clientID);

        return new self ($login, $passwordHash, $clientID, $key, null);
    }

    /**
     * Создаёт объект класса по результирующему объекту из бд
     *
     * @param object $row
     *
     * @return User
     */
    public static function createFromDB(object $row): User
    {
        return new self(
            $row->login,
            $row->password_hash,
            $row->client_id,
            $row->secret_key,
            $row->session
        );
    }

    /**
     * Сохраняет пользователя в БД
     *
     * @param string $login
     * @param string $passwordHash
     * @param string $clientID
     * @param string $key
     * @param string|null $session
     *
     * @return User
     */
    public static function saveToDB(
        string $login,
        string $passwordHash,
        string $clientID,
        string $key,
        ?string $session = null
    ): User
    {
        DataBase::run(
            'INSERT INTO `users` (`login`, `password_hash`, `client_id`, `session`, `secret_key`) VALUES (?,?,?,?,?)',
            [$login, $passwordHash, $clientID, $session, $key]
        );

        return new self ($login, $passwordHash, $clientID, $key, $session);
    }

    /**
     * Авторизует пользователя
     *
     * @return bool - true, есил автоизация удалась
     */
    public function auth(): bool
    {
        $result = API::auth($this);

        if ($result === null || $result->getHttpCode() !== 200) {
            $this->session = null;
            return false;
        }

        $json = json_decode($result->getBody(), true);

        if (!$json || !empty($json['error'])) {
            $this->session = null;
            return false;
        }

        $this->session  = $json['data']['session'];
        $this->clientID = $json['data']['clientId'];

        if ($this->id) {
            DataBase::run(
                'UPDATE `users` SET `client_id` = ?, `secret_key` = ?, `session` = ? WHERE `id` = ?',
                [$this->clientID, $this->key, $this->session, $this->id]
            );
        }

        return true;
    }

    /**
     * Сбрасывает данные по авторизационной сессии
     */
    public function resetAuthInfo(): void
    {
        $this->clientID = static::guidGenerator();
        $this->key      = static::hash(API::API_VERSION . $this->passwordHash . $this->clientID);
        $this->session  = null;

        DataBase::run(
            'UPDATE `users` SET `client_id` = ?, `secret_key` = ?, `session` = ? WHERE `id` = ?',
            [$this->clientID, $this->key, $this->session, $this->id]
        );
    }

    /**
     * Возвращает хэш строки
     *
     * @param string $string
     *
     * @return string
     */
    public static function hash(string $string): string
    {
        return base64_encode(sha1($string, true));
    }

    /**
     * Замена Math.random() из JS
     */
    protected static function jsRand(): float
    {
        return (float)mt_rand() / (float)mt_getrandmax();
    }

    /**
     * Генерирует случайный ClientID
     */
    protected static function guidGenerator(): string
    {
        $S4 = static function(): string {
            return substr((string)dechex((1+static::jsRand())*0x10000), 1);
        };
        return ($S4() . $S4() . '-' . $S4() . '-' . $S4() . '-' . $S4() . '-' . $S4() . $S4() . $S4());
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getLogin(): string
    {
        return $this->login;
    }

    /**
     * @return string
     */
    public function getPasswordHash(): string
    {
        return $this->passwordHash;
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @return string
     */
    public function getClientID(): string
    {
        return $this->clientID;
    }

    /**
     * @return string
     */
    public function getSession(): string
    {
        return $this->session;
    }
}