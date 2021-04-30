<?php
/**
 * Class DB
 * @package		YouInRoll
 * @author		Ron_Tayler
 * @copyright	2021
*/
class DB extends LMVCL {
    /** @var db\mysqli | db\mysql | db\mpdo | db\mssql | db\postgre */
	private $adaptor;

    /**
     * Constructor
     * @param $registry
     * @param string $adaptor
     * @param string $hostname
     * @param string $username
     * @param string $password
     * @param string $database
     * @param int $port
     * @throws Exception
     */
	public function __construct($registry,$adaptor, $hostname, $username, $password, $database, $port = NULL) {
	    parent::__construct($registry);
		$class = 'DB\\' . $adaptor;

		if (class_exists($class)) {
			$this->adaptor = new $class($hostname, $username, $password, $database, $port);
		} else {
			throw new \Exception('Error: Could not load database adaptor ' . $adaptor . '!');
		}
	}

	/**
     * query
     * @param	string	$sql
	 * @return bool|stdClass
     */
	public function query($sql) {
		return $this->adaptor->query($sql);
	}

    /**
     * selectAll
     * @param string $tbl
     * @param string $where
     * @param bool $is_return
     * @return bool|stdClass|string
     */
	public function selectAll($tbl,$where,$is_return=false){
	    return $this->select('*',$tbl,$where,$is_return);
    }

    /**
     * select
     * @param $select
     * @param $tbl
     * @param $where
     * @param bool $is_return
     * @return bool|stdClass|string
     */
    public function select($select,$tbl,$where,$is_return=false){
        $sql = 'SELECT '.$select.' FROM '.DB_PREFIX.$tbl;
        if(isset($where))$sql.=' WHERE '.$where;
        if($is_return)
            return $sql;
        else
            return $this->adaptor->query($sql);
    }

    /**
     * insert_into
     * @param string $tbl
     * @param array $params
     * @return int Last id
     */
    public function insert_into($tbl,$params){
        $str_keys = implode(', ',array_keys($params));
        $str_params = implode(', ',array_values($params));
	    $sql = 'INSERT INTO '.DB_PREFIX.$tbl.'('.$str_keys.') VALUES ('.$str_params.')';
        $this->query($sql);
        return $this->getLastId();
    }

    /**
     * update
     * @param string $tbl
     * @param array $params
     * @param string $where
     */
    public function update($tbl,$params,$where){
        $array_params = array();
        foreach($params as $key => $param){
            $array_params[] = '`'.$key.'`='.$param;
        }
        $str_params = implode(', ',$array_params);
        $sql = 'UPDATE '.DB_PREFIX.$tbl.' SET '.$str_params.' WHERE '.$where;
        $this->query($sql);
    }

    /**
     * delete
     * @param $tbl
     * @param $where
     * @param bool $is_return
     * @return bool|stdClass|string
     */
    public function delete($tbl,$where,$is_return=false){
        $sql = 'DELETE FROM '.DB_PREFIX.$tbl;
        if(isset($where))$sql.=' WHERE '.$where;
        if($is_return)
            return $sql;
        else
            return $this->adaptor->query($sql);
    }

	/**
     * escape
     * @param	string	$value
	 * @return	string
     */
	public function escape($value) {
		return $this->adaptor->escape($value);
	}

	/**
     * countAffected
	 * @return	int
     */
	public function countAffected() {
		return $this->adaptor->countAffected();
	}

	/**
     * getLastId
	 * @return	int
     */
	public function getLastId() {
		return $this->adaptor->getLastId();
	}
	
	/**
     * connected
	 * @return	bool
     */	
	public function connected() {
		return $this->adaptor->connected();
	}
}