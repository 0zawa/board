<?php
App::uses('AppController', 'Controller');
/**
 * Threads Controller
 *
 * @property Thread $Thread
 */
class ThreadsController extends AppController {

  public $uses = array('Thread','Token','Tag');
  public $autoRender = false;

/**
 * index method
 *
 * @return void
 */
	public function index() {
    $token = $this->request->header('X-Token');
    $user_id = $this->Token->get_user_id($token);
    if($user_id < 0) {
      $this->log('invalid token:'.$token,'error');
      $response = array('status'=>'ng','message'=>'invalid token');
      $this->response->body(json_encode($response));
      return;
    }

    $tags = explode(',',$this->request->query('tags'));
  
    $thread_records = $this->Tag->find('all',array('fields'=>'DISTINCT thread_id','conditions'=>array('tag'=>$tags)));

    $threads = array();
    foreach($thread_records as $record) {
      $threads[] = $record['Tag']['thread_id'];
    }
  
    $response = array('status'=>'ok','threads'=>$threads);
    $this->response->body(json_encode($response));
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Thread->exists($id)) {
      $this->log('therad not found:'.$id,'error');
      $response = array('status'=>'ng','message'=>'thread not found');
      $this->request->body(json_encode($response));
      return;
		}
    
    $token = $this->request->header('X-Token');
    $user_id = $this->Token->get_user_id($token);
    if($user_id < 0) {
      $this->log('invalid token:'.$token,'error');
      $response = array('status'=>'ng','message'=>'invalid token');
      $this->response->body(json_encode($response));
      return;
    }

		$options = array('conditions' => array('Thread.' . $this->Thread->primaryKey => $id));
    $result = $this->Thread->find('first', $options);
    $response = array('status'=>'ok');
    $this->request->body(json_encode($response));
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
      if($user_id < 0) {
        $this->log('invalid token:'.$token,'error');
        $response = array('status'=>'ng','message'=>'invalid token');
        $this->response->body(json_encode($response));
        return;
      }

      $request = $this->request->input('json_decode');
      $current = date("Y-m-d H:i:s");
      $request->user_id = $user_id;
      $request->created_at = $current;
      $request->updated_at = $current;

			$this->Thread->create();
			if ($this->Thread->save($request)) {
        $thread_id = $this->Thread->getLastInsertID();

        foreach($request->tags as $tag) {
          $this->Tag->create();
          $this->Tag->save(array('tag'=>$tag,'thread_id'=>$thread_id));
        }

        $response = array(
          'status'=>'ok',
          'id'=>$thread_id, 
          'title'=>$request->title,
          'created_at'=>$current,
          'created_by'=>$user_id,
        );
        $this->response->body(json_encode($response));
			}
		}
	}



/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->Thread->id = $id;
		if (!$this->Thread->exists()) {
      $response = array('status'=>'ng','message'=>'thread not found');
      $this->request->body(json_encode($response));
      return;
		}
    
    $options = array('conditions' => array('Thread.' . $this->Thread->primaryKey => $id));
    $thread = $this->Thread->find('first', $options);
         
    $token = $this->request->header('X-Token');
    
    $user_id = $this->Token->get_user_id($token);
    if($user_id < 0) {
      $this->log('invalid token:'.$token,'error');
      $response = array('status'=>'ng','message'=>'invalid token');
      $this->response->body(json_encode($response));
      return;
    }

    $thread = $this->Thread->find('first',array('conditions'=>array('thread_id'=>$id,'user_id'=>$user_id)));
    if(empty($thread)) {
      $this->log('only owner can delete thread:'.$user_id,'error');
      $response = array('status'=>'ng','message'=>'only owner can delete thread');
      $this->response->body(json_encode($response));
      return;
    }

		$this->request->allowMethod('post', 'delete');
		if ($this->Thread->delete()) {
      $response = array('status'=>'ok','id'=>$id,'created_at'=>$thread['Thread']['created_at']);
      $this->response->body(json_encode($response));
		} else {
      $this->log('failed to delete thread','error');
      $response = array(
        'status'=>'ng',
        'message'=>'failed to delete thread', 
        'id'=>$id,
        'created_at'=>$thread['Thread']['created_at']
      );
      $this->response->body(json_encode($response));
		}
	}
}
