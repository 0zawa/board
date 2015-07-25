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
 * index method
 *
 * @return void
 */
  /*
	public function index() {
		//$this->User->recursive = 0;
    //$this->autoRender = false;
		//$this->set('users', $this->Paginator->paginate());

    $response = array('status'=>'ok');
    $this->response->body(json_encode($response));
	}
  */

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->User->exists($id)) {
			throw new NotFoundException(__('Invalid user'));
		}

		$options = array('conditions' => array('User.' . $this->User->primaryKey => $id));
		//$this->set('user', $this->User->find('first', $options));
    $response = $this->User->find('first',$options);
    $this->response->body(json_encode($response['User']));
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
				//return $this->flash(__('The user has been saved.'), array('action' => 'index'));
        $response = array(
          'status'=>'ok',
          'id'=> $this->User->getLastInsertID()
          );
        $this->response->body(json_encode($response));
			}
		}
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
 /*
	public function edit($id = null) {
		if (!$this->User->exists($id)) {
			throw new NotFoundException(__('Invalid user'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->User->save($this->request->data)) {
				return $this->flash(__('The user has been saved.'), array('action' => 'index'));
			}
		} else {
			$options = array('conditions' => array('User.' . $this->User->primaryKey => $id));
			$this->request->data = $this->User->find('first', $options);
		}
	}
  */

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->User->delete()) {
			return $this->flash(__('The user has been deleted.'), array('action' => 'index'));
		} else {
			return $this->flash(__('The user could not be deleted. Please, try again.'), array('action' => 'index'));
		}
	}
}
