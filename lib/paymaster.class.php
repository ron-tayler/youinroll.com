<?php

class PayMaster {
    
    private $login = 'youinroll-api';
    private $password = 'Eug8NaUk';

    public function savePremium()
    {
        try {
            $this->validate($_POST);
        } catch (\Throwable $th) {
            echo "<pre>";
            print_r($th);
            die();
        }
        return $this->validate($_POST);
    }

    private function validate($data)
    {
        $result = false;

        $nonce = $this->generateRandomString(8);

        $hash = $this->login.';'.$this->password.';'.$nonce.';'.$data['LMI_SYS_PAYMENT_ID'];

        $link = 'https://paymaster.ru/api/v1/getPayment?login='
            .$this->login
            .'&nonce='
            .$nonce
            .'&hash='.base64_encode(sha1($hash, true))
            .'&paymentId='.$data['LMI_SYS_PAYMENT_ID'];

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $link);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_VERBOSE, true);

        $result = curl_exec($curl);

        $json = json_encode($result, true);

        if($json->ErrorCode === 0 && $json->Payment->State === 'COMPLETE')
        {
            $result = true;
        }

        return $result;
    }

    private function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}

?>