<?php
App::uses('AppController', 'Controller');
/**
 * Posts Controller
 *
 * @property Post $Post
 * @property PaginatorComponent $Paginator
 */
class PostsController extends AppController {

/**
 * Components
 *
 * @var array
 */
  public $autoRender = false;
  public $uses = array('Post','Token');
	public $components = array('Token');

/**
 * index method
 *
 * @return void
 */
 /*
	public function index() {
		//$this->Post->recursive = 0;
		//$this->set('posts', $this->Paginator->paginate());

    $this->log("THREAD-ID:".$this->request->params['thread_id'],'debug');
    $this->log("POST-ID:".$this->request->params['post_id'],'debug');

    $response = array('status'=>'okk');
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
		if (!$this->Post->exists($id)) {
			//throw new NotFoundException(__('Invalid post'));
      $response = array('status'=>'ng','message'=>'post not found');
      $this->response->body(json_encode($response));
      return;
		}

    $token = $this->request->header('X-Token');
    $user_id = $this->Token->get_user_id($token);
    if($user_id<0) {
      $response = array('status'=>'ng','message'=>'invalid token');
      $this->response->body(json_encode($response));
      return;
    }

		$options = array('conditions' => array('Post.' . $this->Post->primaryKey => $id));
		$record = $this->Post->find('first', $options));

    $response = array(
      'id'=>$record['post_id'],
      'thread_id'=>$record['thread_id'],
      'content'=>$record['content'],
      'created_at'=>$record['created_at'],
      'created_by'=>$record['user_id'],
    );
    $this->response->body(json_encode($response));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
      $token = $this->request->header('X-Token');

      /*
      $token_record = $this->Token->find('first',array('conditions'=>array('token'=>$token)));
      if(empty($token_record)) {
        $response = array('status'=>'ng','message'=>'invalid token');
        $this->response->body(json_encode($response));
        return;
      }
      $user_id = $token_record['Token']['user_id'];
      */
      $user_id = $this->Token->get_user_id($token);
      if($user_id<0) {
        $response = array('status'=>'ng','message'=>'invalid token');
        $this->response->body(json_encode($response));
        return;
      }

      $current = date("Y-m-d H:i:s");
      $thread_id = $this->request->params['thread_id'];

      $request = $this->request->input('json_decode');
      $request->thread_id = $thread_id;
      $request->user_id = $user_id;
      $request->created_at = $current; 

			$this->Post->create();
			if ($this->Post->save($request)) {
        $response = array(
          'status'=>'ok',
          'thread_id'=>$thread_id,
          'content'=>$request->content,
          'created_at'=>$current,
          'created_by'=>$user_id,
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
		if (!$this->Post->exists($id)) {
			throw new NotFoundException(__('Invalid post'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Post->save($this->request->data)) {
				return $this->flash(__('The post has been saved.'), array('action' => 'index'));
			}
		} else {
			$options = array('conditions' => array('Post.' . $this->Post->primaryKey => $id));
			$this->request->data = $this->Post->find('first', $options);
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
 /*
	public function delete($id = null) {
		$this->Post->id = $id;
		if (!$this->Post->exists()) {
			throw new NotFoundException(__('Invalid post'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Post->delete()) {
			return $this->flash(__('The post has been deleted.'), array('action' => 'index'));
		} else {
			return $this->flash(__('The post could not be deleted. Please, try again.'), array('action' => 'index'));
		}
	}
  */
}
