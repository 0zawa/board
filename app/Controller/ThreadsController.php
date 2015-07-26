<?php
App::uses('AppController', 'Controller');
/**
 * Threads Controller
 *
 * @property Thread $Thread
 */
class ThreadsController extends AppController {

  public $uses = array('Thread','Token','Tag','Post');
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
     $this->send_ng('invalid token');
      return;
    }

    $tags = explode(',',$this->request->query('tags'));
  
    $thread_records = $this->Tag->find('all',array('fields'=>'DISTINCT thread_id','conditions'=>array('tag'=>$tags)));

    $threads = array();
    foreach($thread_records as $record) {
      $threads[] = $record['Tag']['thread_id'];
    }
  
    $response = array('status'=>'ok','threads'=>$threads);
    $this->send_ok($response);
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
      $this->send_ng('thread not found');
      return;
		}
    
    $token = $this->request->header('X-Token');
    $user_id = $this->Token->get_user_id($token);
    if($user_id < 0) {
      $this->log('invalid token:'.$token,'error');
      $this->send_ng('invalid token');
      return;
    }

    $tags = array();
    $tag_records = $this->Tag->find('all',array('fields'=>'tag','conditions'=>array('thread_id'=>$id)));
    foreach($tag_records as $record) {
      $tags[] = $record['Tag']['tag'];
    }

    $posts_count = $this->Post->find('count',array('conditions'=>array('thread_id'=>$id)));

		$options = array('conditions' => array('Thread.' . $this->Thread->primaryKey => $id));
    $result = $this->Thread->find('first', $options);
    $response = array(
      'id' => $result['Thread']['thread_id'],
      'title'=>$result['Thread']['title'],
      'tags'=>implode(',',$tags),
      'total_posts'=>$posts_count,
    );
    $this->send_ok($response);
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
        $this->send_ng('invalid token');
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
        $this->send_ok($response);
			} else {
        $this->log('failed to save post','error');
        $this->send_ng('faield to save post');
		} else {
      $this->log('invalid http method','error');
      $this->send_ng('invalid http method');
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
      $this->log('thread not found:'.$id,'error');
      return $this->send_ng('thread not found');
		}
    
    $options = array('conditions' => array('Thread.' . $this->Thread->primaryKey => $id));
    $thread = $this->Thread->find('first', $options);
         
    $token = $this->request->header('X-Token');
    
    $user_id = $this->Token->get_user_id($token);
    if($user_id < 0) {
      $this->log('invalid token:'.$token,'error');
      $this->send_ng('invalid token');
      return;
    }

    $thread = $this->Thread->find('first',array('conditions'=>array('thread_id'=>$id,'user_id'=>$user_id)));
    if(empty($thread)) {
      $this->log('only owner can delete thread:'.$user_id,'error');
      $this->send_ng('only owner can delete thread');
      return;
    }

		$this->request->allowMethod('post', 'delete');
		if ($this->Thread->delete()) {
      $response = array('id'=>$id,'created_at'=>$thread['Thread']['created_at']);
      $this->send_ok($response);
		} else {
      $this->log('failed to delete thread','error');
     $this->send_ng('failed to delete thread');
		}
	}
}
