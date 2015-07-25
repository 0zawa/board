<?php
App::uses('AppController', 'Controller');
/**
 * Login Controller
 *
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
      $blowfish = $this->Auth->blowfish($request->password);
      $options = array('conditions' => array('name'=> $request->name,'password'=>$blowfish));
      $record = $this->User->find('first',$options);

      if(empty($record)) {
        $this->log('user not found:'.$request->name, 'error');
        $response = array('status' => 'ng', 'message' => 'user not found');
      } else {
        /// @ref http://www.websec-room.com/2013/03/05/443
        $uuid = bin2hex(openssl_random_pseudo_bytes(16));
        $token = array(
          'user_id'=>$result['User']['id'],
          'token'=>$uuid,
          'expired_at'=>date("Y-m-d H:i:s",strtotime("+1 day"))
        );
        $this->Token->setToken($token);
        $response = array('status' => 'ok', 'token' => $uuid);
      }
      $this->response->body(json_encode($response));
    }
	}
}
