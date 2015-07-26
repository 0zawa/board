<?php

/*
 * ルーティングテスト.
 */

App::uses('Router', 'Routing');
 
class AppRoutesTest extends CakeTestCase {
      
  public function setUp() {
    parent::setUp();
    require APP . 'Config' . DS . 'routes.php';
  }
                               
  public function tearDown() {
    Router::reload();
    parent::tearDown();
  }

  /*
   * GET /users
   */
  public function testGetUsers() {
    $this->check('users','index','/users');
  }

  /*
   * GET /users/1
   */
  public function testGetSpecifiedUsers() {
    $this->check('users','view','/users/1');
  }

  /*
   * GET /threads
   */
  public function testGetThreads() {
    $this->check('threads','index','/threads');
  }

  /*
   * GET /threads/1
   */
  public function testGetSpecifiedThreads() {
    $this->check('threads','view','/threads/1');
  }

  /*
   * GET /threads/1/posts/1
   */
  public function testGetSpecifiedPosts() {
    $this->check('posts','view','/threads/1/posts/1');
  }

  /// @todo POST DELETEのテスト方法調査.

  private function check($controller,$action,$route) {
    $expects = array(
      'controller' => $controller,
      'action' => $action
    );
    $url = Router::parse($route);
    $this->assertEqual($expects['controller'], $url['controller']);
    $this->assertEqual($expects['action'], $url['action']);
  }

}
