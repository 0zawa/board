<?php
App::uses('AppController', 'Controller');
/**
 * Login Controller
 * ユーザーのログイン処理を行う.
 */
class LoginController extends AppController {

	public $components = array('Auth');
  public $autoRender = false;
  public $uses = array('User','Token');

/**
 * index method
 *
 * @return void
 */
	public function index() {
    if($this->request->is('post')) {
      $request = $this->request->input('json_decode');
      $hash = $this->Auth->hash($request->password);

      $options = array('conditions' => array('name'=> $request->name,'password'=>$hash));
      $record = $this->User->find('first',$options);

      if(empty($record)) {
        $this->log('user not found:'.$request->name, 'error');
        return $this->send_ng('user not found');
      } else {
        /// @ref http://www.websec-room.com/2013/03/05/443
        $uuid = bin2hex(openssl_random_pseudo_bytes(16));
        $token = array(
          'user_id'=>$record['User']['user_id'],
          'token'=>$uuid,
          'expired_at'=>date("Y-m-d H:i:s",strtotime("+1 day"))
        );
        $this->Token->setToken($token);
        $response = array('token' => $uuid);
      }
      return $this->send_ok($response);
    }
	}
}
