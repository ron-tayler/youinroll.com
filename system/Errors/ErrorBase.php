<?php


class ErrorBase extends Error{
    private string $private_message = '';
    private static array $error_code_list = [
        0=>'Неизвестная ошибка',
        1=>'Ошибка версии api',
        2=>'Известная, но не декларированная ошибка',
        3=>'Ошибка доступа',
        4=>'Контент не найден',
        5=>'Ошибка на стороне сервера',
        6=>'Ошибка в URL параметрах',
        7=>'Ошибка в GET/POST/... параметрах'
    ];

    /**
     * ErrorBase constructor.
     * @param string $private_message Приватное сообщение для Dev
     * @param int $code Публичный код ошибки
     * @param string $message Публичное сообщение
     * @param Throwable|null $previous Ветка исключений
     */
    public function __construct($private_message = '', $code = 0, $message = null, Throwable $previous = null){
        $message ??= self::$error_code_list[$code];
        parent::__construct($message, $code, $previous);
        $this->private_message = $private_message;
    }

    public function getPrivateMessage(){
        return $this->private_message;
    }
}