<?php

/**
 * Class LMVCL<br>
 * Супер класс модулей паттерна LMVCL<br>
 * L - Library<br>
 * M - Model<br>
 * V - Viewer<br>
 * C - Controller<br>
 * L - Language<br>
 * @package YouInRoll.com
 * @author Ron_Tayler
 * @copyright 2021
 */
class LMVCL{
    private Registry $registry;

    /**
     * LMVCL constructor.
     * @param Registry $registry
     */
    public function __construct(Registry $registry){
        $this->registry = $registry;
    }

    /**
     * @param $name
     * @return LMVCL|IEngine|null
     */
    public function __get($name)
    {
        return $this->$name ?? $this->registry->get($name);
    }

}