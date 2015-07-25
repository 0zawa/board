<?php
App::uses('AppController', 'Controller');
/**
 * Login Controller
 *
 */
class LoginController extends AppController {

/**
 * Components
 *
 * @var array
 */
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
      $password = $this->Auth->blowfish($request->password);
      $options = array('conditions' => array('name'=> $request->name,'password'=>$password));
      $result = $this->User->find('first',$options);

      if(empty($result)) {
        $response = array('status' => 'ng', 'message' => 'user not found');
      } else {
        //$uuid = String::uuid();
        /// @ref http://www.websec-room.com/2013/03/05/443
        $uuid = bin2hex(openssl_random_pseudo_bytes(16));
        $token = array('user_id'=>$result['User']['id'],'token'=>$uuid,'expired_at'=>date("Y-m-d H:i:s",strtotime("+1 day")));
        $this->Token->setToken($token);
        $response = array('status' => 'ok', 'token' => $uuid);
      }
      $this->response->body(json_encode($response));
    }
	}
}
