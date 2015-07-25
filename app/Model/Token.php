<?php
App::uses('AppModel', 'Model');
/**
 * Token Model
 *
 */
class Token extends AppModel {

/**
 * Primary key field
 *
 * @var string
 */
	public $primaryKey = 'token';

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'token' => array(
			'uuid' => array(
				'rule' => array('alphaNumeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'user_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'expired_at' => array(
			'datetime' => array(
				'rule' => array('datetime'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);

  public function setToken($token) {
    $query = "INSERT INTO tokens (user_id,token,expired_at) VALUES (:user_id,:token,:expired_at) ON DUPLICATE KEY UPDATE token=:token,expired_at=:expired_at";
    $params = array('user_id'=>$token['user_id'],'token'=>$token['token'],'expired_at'=>$token['expired_at']);
    $this->query($query,$params);
    $this->log($this->getDataSource()->getLog(),'debug');
  }

  public function get_user_id($token)
  {
    $current = date("Y-m-d H:i:s");
    $record = $this->find('first',array('conditions'=>array('token'=>$token,'expired_at >'=>$current)));

    $this->log("SQL:".print_r($this->getDataSource()->getLog(),true),'debug');

    if(empty($record)) {
      return -1;
    }
    
    return $record['Token']['user_id'];
  }
}
