<?php


abstract class ErrorBase extends Error{
    protected const CODE = 0;
    protected const MESSAGE = 'Неизвестная ошибка';
    private string $private_message = '';

    /**
     * ErrorBase constructor.
     * @param string $private_message Приватное сообщение для Dev
     * @param string $public_message Публичное сообщение
     * @param Throwable|null $previous Ветка исключений
     */
    public final function __construct(string $private_message = null, string $public_message = null, Throwable $previous = null){
        $code = static::CODE;
        $public_message ??= static::MESSAGE;
        $this->private_message = $private_message ?? static::MESSAGE;
        parent::__construct($public_message, $code, $previous);
    }

    public final function getPrivateMessage(){
        return $this->private_message;
    }
}