<?php

namespace Controller;

use Registry;

/**
 * Class Video
 * @package YouInRoll.com
 * @author Ron_Tayler
 * @copyright 2021
 */
class Video extends \LMVCL implements \IController{

    /**
     * Method index
     * @map /video/:chanel/:id
     * @param array $param
     * @return string[]
     */
    public function index(array $param){
        $message = 'Это работает! Я получил параметры: '.$param['chanel'].', '.$param['id'];
        return ['message'=>$message];
    }

}
