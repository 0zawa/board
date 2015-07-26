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
	//public $components = array('');

/**
 * view method
 *
 * @param string $id
 * @return void
 */
	public function view($id = null) {
    $post_id = $this->request->params['post_id'];
		if (!$this->Post->exists($post_id)) {
      $this->log('post not found:'.$post_id,'error');
      return $this->send_ng('post not found');
		}

    $token = $this->request->header('X-Token');
    $user_id = $this->Token->get_user_id($token);
    if($user_id<0) {
      $this->log('invalid token:'.$token,'error');
      return $this->send_ng('invalid token');
    }

		$options = array('conditions' => array('Post.' . $this->Post->primaryKey => $post_id));
		$record = $this->Post->find('first', $options);

    $response = array(
      'id'=>$record['Post']['post_id'],
      'thread_id'=>$record['Post']['thread_id'],
      'content'=>$record['Post']['content'],
      'created_at'=>$record['Post']['created_at'],
      'created_by'=>$record['Post']['user_id'],
    );
    return $this->send_ok($response);
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
        /*
        $response = array('status'=>'ng','message'=>'invalid token');
        $this->response->body(json_encode($response));
        */
        return $this->send_ng('invalid token');
      }

      $current = date("Y-m-d H:i:s");
      $thread_id = $this->request->params['thread_id'];

      $request = $this->request->input('json_decode');
      $request->thread_id = $thread_id;
      $request->user_id = $user_id;
      $request->created_at = $current; 

			$this->Post->create();
			if ($this->Post->save($request)) {
        $post_id = $this->Post->getLastInsertID();
        $response = array(
          'id'=>$post_id,
          'thread_id'=>$thread_id,
          'content'=>$request->content,
          'created_at'=>$current,
          'created_by'=>$user_id,
        );
        return $this->send_ok($response);
			} else {
        $this->log('failed to add post:'.$request->content, 'error');
        //$response = array('status'=>'ng','message'=>'failed to add post');
        return $this->send_ng('failed to add post');
      }
       
      //$this->response->body(json_encode($response));
		}
	}
}
