<?php
/**
 * Class Registry
 * Класс для регистрации всех модулей паттерна LMVCL
 * @package		YouInRoll.com
 * @author		Ron_Tayler
 * @copyright	2021
 */
class Registry {
    /** @var LMVCL[]|IEngine[] */
    public array $data = array();

    /**
     *
     * @param string $key
     * @return LMVCL|IEngine
     */
    public function get($key) {
        return $this->data[$key] ?? null;
    }

    /**
     *
     * @param string $key
     * @param LMVCL|IEngine $value
     */
    public function set($key, $value) {
        $this->data[$key] = $value;
    }

    /**
     *
     * @param string $key
     * @return bool
     */
    public function has($key) {
        return isset($this->data[$key]);
    }
}