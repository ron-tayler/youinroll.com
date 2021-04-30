<?php


class ErrorBase extends Error{
    private string $private_message = '';

    /**
     * ErrorBase constructor.
     * @param string $private_message Приватное сообщение для Dev
     * @param int $code Публичный код ошибки
     * @param string $message Публичное сообщение
     * @param Throwable|null $previous Ветка исключений
     */
    public function __construct($private_message = '', $code = 0, $message = '', Throwable $previous = null){
        parent::__construct($message, $code, $previous);
        $this->private_message = $private_message;
    }

    public function getPrivateMessage(){
        return $this->private_message;
    }
}