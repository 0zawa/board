<?php
App::uses('AppController', 'Controller');
/**
 * Posts Controller
 *
 * @property Post $Post
 */
class PostsController extends AppController {

  public $autoRender = false;
  public $uses = array('Post','Token');
	public $components = array('Token');

/**
 * view method
 *
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Post->exists($id)) {
      $this->log('post not found:'.$id,'error');
      $response = array('status'=>'ng','message'=>'post not found');
      $this->response->body(json_encode($response));
      return;
		}

    $token = $this->request->header('X-Token');
    $user_id = $this->Token->get_user_id($token);
    if($user_id<0) {
      $this->log('invalid token:'.$token,'error');
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

      $user_id = $this->Token->get_user_id($token);
      if($user_id<0) {
        $this->log('invalid token:'.$token,'error');
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
			} else {
        $this->log('failed to add post:'.$request->content, 'error');
        $response = array('status'=>'ng','message'=>'failed to add post');
      }
       
      $this->response->body(json_encode($response));
		}
	}
}
