<?php


namespace Bot\API;


class RequestAnswer
{
    /** @var string  */
    protected $body;

    /** @var integer */
    protected $httpCode;

    /** @var string  */
    protected $headers;

    /**
     * RequestAnswer constructor.
     * @param string $body - тело ответа
     * @param integer $httpCode - код ответа http
     * @param string $headers - заголовки ответа
     */
    public function __construct(string $body, int $httpCode = 200, string $headers = '')
    {
        $this->body     = $body;
        $this->httpCode = $httpCode;
        $this->headers  = $headers;
    }

    /**
     * Проверяет, вернулся лт ответ на запрос
     * @return bool
     */
    public function check(): bool
    {
        return $this->body !== false;
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * @return int
     */
    public function getHttpCode(): int
    {
        return $this->httpCode;
    }

    /**
     * @return string
     */
    public function getHeaders(): string
    {
        return $this->headers;
    }


}