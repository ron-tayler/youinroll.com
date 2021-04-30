<?php
/**
* @package		SITE
* @author		Ron tayler
* @copyright	2020
*/

/**
* Request class
*/
class Request implements IEngine {
	public array $get = [];
	public array $post = [];
	public array $request = [];
	public array $cookie = [];
	public array $files = [];
	public array $server = [];
	
	/**
	 * Request constructor
 	 */
	public function __construct() {
		$this->get = $this->clean($_GET);
		$this->post = $this->clean($_POST);
		$this->request = $this->clean($_REQUEST);
		$this->cookie = $this->clean($_COOKIE);
		$this->files = $this->clean($_FILES);
		$this->server = $this->clean($_SERVER);
	}
	
	/**
     * Method clean - очистка спец. символов html
	 * @param array $data
     * @return array
     */
	private function clean($data) {
		if (is_array($data)) {
			foreach ($data as $key => $value) {
				unset($data[$key]);

				$data[$this->clean($key)] = $this->clean($value);
			}
		} else {
			$data = htmlspecialchars($data, ENT_COMPAT, 'UTF-8');
		}

		return $data;
	}
}