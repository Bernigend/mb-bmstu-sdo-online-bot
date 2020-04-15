<?php


namespace Bot;


class Notification
{
    /** @var string */
    public const ERROR = 'error';

    /** @var string */
    public const SUCCESS = 'success';

    /** @var string */
    public $text;

    /** @var string */
    public $type;

    /**
     * Notification constructor.
     *
     * @param string $text - текст уведомления
     * @param string $type - тип уведомления (success, error)
     */
    private function __construct(string $text, string $type = self::SUCCESS)
    {
        $this->text = $text;
        $this->type = $type;
    }

    /**
     * Добавляет уведомление
     *
     * @param string $text - текст уведомления
     * @param string $type - тип уведомления (success, error)
     */
    public static function add(string $text, string $type = self::SUCCESS): void
    {
        $_SESSION['notifications'][] = new self($text, $type);
    }
}