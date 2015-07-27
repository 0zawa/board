<?php
App::uses('AppController', 'Controller');


/**
 * Post(メッセージ投稿)管理コントローラ
 * 
 */
class PostsController extends AppController {

  public $autoRender = false;
  public $uses = array('Post','Token','Thread');
	//public $components = array('');

/**
 * id指定のPost取得
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

    /// アクセストークンチェック.
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
 * Post追加
 * 追加時には親Threadの更新日時も現在日時に更新する.
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
      /// アクセストークンチェック.
      $token = $this->request->header('X-Token');
      $user_id = $this->Token->get_user_id($token);
      if($user_id<0) {
        $this->log('invalid token:'.$token,'error');
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

        /// Threadの最終更新日時更新.
        $this->Thread->id = $thread_id;
        $this->Thread->save(array('updated_at'=>$current));

        return $this->send_ok($response);
			} else {
        $this->log('failed to add post:'.$request->content, 'error');
        return $this->send_ng('failed to add post');
      }
		} else {
      /// POSTメソッド以外での呼び出し
      $this->log('invalid http method:', 'error');
      return $this->send_ng('invalid http method');
    }
	}
}
