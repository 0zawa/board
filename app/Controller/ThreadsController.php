<?php
App::uses('AppController', 'Controller');

/**
 * Thread管理コントローラ.
 *
 */
class ThreadsController extends AppController {

  public $uses = array('Thread','Token','Tag','Post');
  public $autoRender = false;

/**
 * タグ指定でのThread検索
 * 検索はOR条件で行う.
 *
 */
	public function index() {
    
    /// アクセストークン検証.
    $token = $this->request->header('X-Token');
    $user_id = $this->Token->get_user_id($token);
    if($user_id < 0) {
      $this->log('invalid token:'.$token,'error');
     $this->send_ng('invalid token');
      return;
    }

    /// タグをOR条件で検索.
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
 * id指定でのThread取得.
 *
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Thread->exists($id)) {
      $this->log('therad not found:'.$id,'error');
      return $this->send_ng('thread not found');
		}
    
    /// アクセストークン検証.
    $token = $this->request->header('X-Token');
    $user_id = $this->Token->get_user_id($token);
    if($user_id < 0) {
      $this->log('invalid token:'.$token,'error');
      return $this->send_ng('invalid token');
    }

    /// 指定thread_idのタグ取得.
    $tags = array();
    $tag_records = $this->Tag->find('all',array('fields'=>'tag','conditions'=>array('thread_id'=>$id)));
    foreach($tag_records as $record) {
      $tags[] = $record['Tag']['tag'];
    }

    /// Postの数を取得.
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
 * Thread作成.
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
      $token = $this->request->header('X-Token');
      
      /// アクセストークン検証.
      $user_id = $this->Token->get_user_id($token);
      if($user_id < 0) {
        $this->log('invalid token:'.$token,'error');
        return $this->send_ng('invalid token');
      }

      $request = $this->request->input('json_decode');
      $current = date("Y-m-d H:i:s");
      $request->user_id = $user_id;
      $request->created_at = $current;
      $request->updated_at = $current;

			$this->Thread->create();
			if ($this->Thread->save($request)) {
        $thread_id = $this->Thread->getLastInsertID();

        /// タグ情報をTagテーブルに保存.
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
        return $this->send_ok($response);
			} else {
        $this->log('failed to save post','error');
        return $this->send_ng('faield to save post');
		} else {
      $this->log('invalid http method','error');
      return $this->send_ng('invalid http method');
    }
	}



/**
 * POST削除.
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
         
    /// アクセストークン検証.
    $token = $this->request->header('X-Token');
    $user_id = $this->Token->get_user_id($token);
    if($user_id < 0) {
      $this->log('invalid token:'.$token,'error');
      return $this->send_ng('invalid token');
    }

    $thread = $this->Thread->find('first',array('conditions'=>array('thread_id'=>$id,'user_id'=>$user_id)));
    if(empty($thread)) {
      $this->log('only owner can delete thread:'.$user_id,'error');
      return $this->send_ng('only owner can delete thread');
    }

		$this->request->allowMethod('post', 'delete');
		if ($this->Thread->delete()) {
      $response = array('id'=>$id,'created_at'=>$thread['Thread']['created_at']);
      return $this->send_ok($response);
		} else {
      $this->log('failed to delete thread','error');
      return $this->send_ng('failed to delete thread');
		}
	}
}
