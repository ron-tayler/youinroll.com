<?

include 'functions.tinkoff.php';

/* 
* Оплатизатор самописный, версия 22.8
*/

class YNRPayment {


    /* const tinkoffKey = '1612364108805';
    const tinkoffPass = '81koe78d60rmnidg'; */

    const tinkoffKey = '1612364108805DEMO';
    const tinkoffPass = 'tr0jwagzkswqbvi7';

    const paymasterId = '4cae70bd-2415-4dc8-bb55-e4c3c9439d66';

    private $defaultPrice = 15900;

    private $name;
    private $lastName;
    private $number;
    private $month;
    private $year;
    private $cvv;

    private $courseId;
    private $courseName;

    private $description;
    private $monthes = 1;
    
    public function init(array $postData, int $userId, $type = 'card')
    {

        global $db, $cachedb;

        $this->userId = $userId;

        $this->name = toDb($postData['name']);
		$this->lastName = toDb($postData['lastname']);

		$this->number = toDb( intval($postData['number']) );
		$this->month = toDb( intval($postData['month']) );
		$this->year = toDb( intval($postData['year']) );
        $this->cvv = toDb( intval($postData['cvv']) );

        $this->monthes = ( intval($postData['monthes']) === 0 ) ? 1 :  intval($postData['monthes']);
        $this->description = $this->makeDescription();

        switch ($this->monthes) {
            case 3:
                $this->defaultPrice = 43000;
                break;

            case 6:
                $this->defaultPrice = 81100;
                break;
            
            default:

                break;
        }

        $user = $cachedb->get_row('SELECT id,name,email,phone FROM '.DB_PREFIX.'users WHERE id = '.toDb($this->userId).' LIMIT 0,1');
        
        switch ($type) {
            case 'card':
                $tinkofApi = new TinkoffMerchantAPI(
                    self::tinkoffKey,  //Ваш Terminal_Key
                    self::tinkoffPass   //Ваш Secret_Key
                );
                
                $result = $this->tinkoffPay($tinkofApi, $user);
                break;

            case 'paymaster':
                                
                $result = $this->paymasterPay($user);

                break;
            
            default:
                $tinkofApi = new TinkoffMerchantAPI(
                    self::tinkoffKey,  //Ваш Terminal_Key
                    self::tinkoffPass   //Ваш Secret_Key
                );
                
                $result = $this->tinkoffPay($tinkofApi, $user);
                break;
        }


        return $result;
    }


    public function buyCourse(array $postData, int $userId, $type = 'card')
    {
        global $db, $cachedb;

        $this->courseId = intval($postData['course']);

        $this->userId = $userId;

        $course = $db->get_row('SELECT * FROM '.DB_PREFIX.'playlists WHERE id = '.toDb($this->courseId).' AND owner <> '.toDb($this->userId).' LIMIT 0,1');

        if($course)
        {
            $this->courseName = $course->title;
            $this->description = $this->makeDescription('course');
            $this->defaultPrice = ($course->price * 100);

            $user = $cachedb->get_row('SELECT name,email,phone FROM '.DB_PREFIX.'users WHERE id = '.toDb($this->userId).' LIMIT 0,1');

            switch ($type) {
                case 'card':
                    $tinkofApi = new TinkoffMerchantAPI(
                        self::tinkoffKey,  //Ваш Terminal_Key
                        self::tinkoffPass   //Ваш Secret_Key
                    );
                    
                    $result = $this->tinkoffPayCourse($tinkofApi, $user);
                    break;
    
                case 'paymaster':
                                    
                    $result = $this->paymasterPay($user);
    
                    break;
                
                default:
                    $tinkofApi = new TinkoffMerchantAPI(
                        self::tinkoffKey,  //Ваш Terminal_Key
                        self::tinkoffPass   //Ваш Secret_Key
                    );
                    
                    $result = $this->tinkoffPayCourse($tinkofApi, $user);
                    break;
            }
        }

        return $result;
    }

    public function getCard($user)
    {

        $subApi = new TinkoffMerchantAPI(
            self::tinkoffKey,  //Ваш Terminal_Key
            self::tinkoffPass   //Ваш Secret_Key
        );

        $customerData = [
            'CustomerKey' => $user->email
        ];

        $subApi->getCardList($customerData);


        $cards = $subApi->json;

        return $cards;
    }

    public function charge($sub, $newValid)
    {
        global $cachedb, $db;

        $user = $cachedb->get_row('SELECT email FROM '.DB_PREFIX.'users WHERE id = '.$sub->user_id.' LIMIT 0,1');

        $subApi = new TinkoffMerchantAPI(
            self::tinkoffKey,  //Ваш Terminal_Key
            self::tinkoffPass   //Ваш Secret_Key
        );

        $customerData = [
            'PaymentId' => $sub->bank_id,
            'RebillId' => $sub->rebill_id
        ];

        $subApi->charge($customerData);

        if($subApi->response)
        {
            //$db->query('UPDATE '.DB_PREFIX."user_subscriptions SET renewed = '1', SET valid_to = '".$newValid."' WHERE id = ".toDb($sub->id));
        }

        print_r($subApi);
        echo '\n';

    }

    private function tinkoffPay($api, $user = null)
    {
        global $db;

        $postData = [
            'TerminalKey' => self::tinkoffKey,
            'Amount' => $this->defaultPrice,
            'OrderId' => $this->getOrderId(),
            'IP' => $_SERVER['REMOTE_ADDR'],
            'Description' => $this->description,
            'Password' => self::tinkoffPass,
            'Recurrent' => 'Y',
            'RecurrentDueDate' => date("Y-m-d h:i:s", strtotime("+$this->monthes month"))
        ];

        if($user)
        {
            $subApi = new TinkoffMerchantAPI(
                self::tinkoffKey,  //Ваш Terminal_Key
                self::tinkoffPass   //Ваш Secret_Key
            );

            $customerData = [
                'CustomerKey' => $user->email,
                'IP' => $_SERVER['REMOTE_ADDR'],
                'Phone' => $user->phone,
                'TerminalKey' => self::tinkoffKey
            ];

            //$customerData['Token'] = $this->makeToken($customerData);

            $subApi->addCustomer($customerData);

            $postData['CustomerKey'] = $user->email;

            $postData['Receipt'] = [
                'Email' => $user->email,
                'Phone' => $user->phone,
                'EmailCompany' => 'youinroll@gmail.com',
                'Taxation' => 'osn',
                'Items' => [
                    [
                        'Name' => 'Premium',
                        'Quantity' => 1,
                        'Amount' => $this->defaultPrice,
                        'Price' => $this->defaultPrice,
                        'Tax' => 'vat20'
                    ]
                ]
            ];
        }

        $postData['Token'] = $this->makeToken($postData);

        $api->init($postData);

        if($api->response)
        {
            $result = $api->PaymentUrl;

            $insertSQL = 'INSERT INTO '.DB_PREFIX."user_subscriptions (`user_id`, `type`, `payment_method`, `validity`, `valid_to`, `payment_gross`, `txn_id`, `payment_status`, `bank_id`) 
                VALUES ('".toDb($user->id)."','role','tinkoff','".toDb($this->monthes)."', '".$postData['RecurrentDueDate']."','".toDb( ($this->defaultPrice / 100) )."','".$postData['OrderId']."','waiting', '".toDb($api->PaymentId)."')";
            
            $db->query($insertSQL);
            
        }
        else
        {
           $result = $api->error;
        }

        return $result;
    }

    private function tinkoffPayCourse($api, $user = null)
    {
        global $db;

        $postData = [
            'TerminalKey' => self::tinkoffKey,
            'Amount' => $this->defaultPrice,
            'OrderId' => $this->getOrderId(),
            'IP' => $_SERVER['REMOTE_ADDR'],
            'Description' => $this->description,
            'Password' => self::tinkoffPass,
            'PaymentUrl' => $_SERVER['HTTP_REFERER'],
            'SuccessUrl' => $_SERVER['HTTP_REFERER'],
            'FailUrl' => $_SERVER['HTTP_REFERER'],
            'BackUrl' => $_SERVER['HTTP_REFERER']
        ];

        if($user)
        {
            $postData['Receipt'] = [
                'Email' => $user->email,
                'Phone' => $user->phone,
                'EmailCompany' => 'youinroll@gmail.com',
                'Taxation' => 'osn',
                'Items' => [
                    [
                        'Name' => 'Premium',
                        'Quantity' => 1,
                        'Amount' => $this->defaultPrice,
                        'Price' => $this->defaultPrice,
                        'Tax' => 'vat20'
                    ]
                ]
            ];
        }

        $postData['Token'] = $this->makeToken($postData);

        $api->init($postData);

        if($api->response)
        {
            $result = $api->PaymentUrl;

            $db->query('INSERT INTO '.DB_PREFIX."users_courses (`playlist_id`, `user_id`, `order_id`) VALUES ('".toDb($this->courseId)."','".toDb($this->userId)."','".toDb($postData['OrderId'])."')");
            
        }
        else
        {
           $result = $_SERVER['HTTP_REFERER'];
        }

        return $result;
    }

    private function paymasterPay($user)
    {
        $curl = curl_init();

        $postData = [
            'LMI_MERCHANT_ID' => self::paymasterId,
            'LMI_PAYMENT_AMOUNT' => ($this->defaultPrice / 100),
            'LMI_CURRENCY' => 'rub',
            'LMI_PAYMENT_NO' => $this->getOrderId(),
            'LMI_PAYMENT_DESC' => $this->makeDescription(),
            'LMI_PAYER_PHONE_NUMBER' => $user->phone,
            'LMI_PAYER_EMAIL' => $user->email,
            'LMI_SHOPPINGCART' => [
                'ITEMS' => [
                    [
                        'NAME' => 'Premium Subscribe',
                        'QTY' => 1,
                        'PRICE' => ($this->defaultPrice / 100),
                        'TAX' => 'vat20'
                    ]
                ]
            ]
        ];

        $postData = http_build_query($postData);

        curl_setopt($curl, CURLOPT_URL, 'https://paymaster.ru/payment/init');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_VERBOSE, true);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $postData);

        $result = curl_exec($curl);

        print_r($result);
        die();
    }

    private function getOrderId($length = 30)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    private function makeDescription($type = 'subscribe')
    {
        switch ($type) {
            case 'course':
                $result = 'Покупка курса "'.$this->courseName.'" на YouInRoll';
                break;
            
            default:
                $result = 'Оплата премиум подписки на сайте YouInRoll на '.$this->monthes.' месяц(а)';
                break;
        }
        return $result;
    }

    private function makeToken()
    {
        $token = hash('sha256', implode('',$postData));

        return $token;
    }
}

?>