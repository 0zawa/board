<?php
App::uses('AppController', 'Controller');
/**
 * Users Controller
 *
 * @property User $User
 * @property PaginatorComponent $Paginator
 */
class UsersController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Auth');
  public $autoRender = false;

/**
 * view method
 *
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->User->exists($id)) {
      $this->log('user not found:'.$id, 'error');
      $response = array('status'=>'ng','message'=>'user not found');
      $this->response->body(json_encode($response));
      return;
		}

		$options = array('conditions' => array('User.' . $this->User->primaryKey => $id));
    $record = $this->User->find('first',$options);
    $this->response->body(json_encode($record['User']));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
      $request = $this->request->input('json_decode');
      $request->password = $this->Auth->blowfish($request->password);
			$this->User->create();
			if ($this->User->save($request)) {
        $response = array(
          'status'=>'ok',
          'id'=> $this->User->getLastInsertID()
          );
			} else {
        $this->log('failed to add user:'$request->name,'error');
        $response = array('status'=>'ok','message'=>'failed to add user');
		} else {
      $this->log('invalid http method:'.$this->request->method(), 'error');
      $response = array('status'=>'ng','message'=>'invalid http method');
    }

    $this->response->body(json_encode($response));
	}
}
