<?php
App::uses('AppController', 'Controller');
/**
 * Users Controller
 * ユーザーの登録/取得を行う.
 *
 * @property User $User
 */
class UsersController extends AppController {

	public $components = array('Auth'); /// パスワードハッシュ用.
  public $autoRender = false;

/**
 * view method
 * ユーザーの取得.
 *
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->User->exists($id)) {
      $this->log('user not found:'.$id, 'error');
      return $this->send_ng('user not found');
		}

		$options = array('conditions' => array('User.' . $this->User->primaryKey => $id),'fields'=>array('user_id','name'));
    $record = $this->User->find('first',$options);

    $this->send_ok($record['User']);
	}

/**
 * add method
 * ユーザーの追加.
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
      $request = $this->request->input('json_decode');
      $request->password = $this->Auth->hash($request->password);

      if($this->User->find('count',array('conditions'=>array('name'=>$request->name)))>0) {
        $this->log('user name already exist:'.$request->name,'error');
        return $this->send_ng('user name already exist');
      }

			$this->User->create();
			if ($this->User->save($request)) {
        $response = array(
          'id'=> $this->User->getLastInsertID(),
          'name'=>$request->name,
        );
        return $this->send_ok($response);
			} else {
        $this->log('failed to add user:'.$request->name,'error');
        return $this->send_ng('failed to add user');
      }
		} else {
      $this->log('invalid http method:'.$this->request->method(), 'error');
      return $this->send_ng('invalid htttp method');
    }
	}
}
