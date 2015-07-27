<?php
App::uses('Post', 'Model');

/**
 * Post Test Case
 *
 */
class PostTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.post'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Post = ClassRegistry::init('Post');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Post);

		parent::tearDown();
	}

	/**
	* バリデーションテスト.
	*
	*/
  public function testValidation() {
    $this->validate(true,1,1,'content','2015-07-26 00:00:00');
    $this->validate(false,1,1,'','2015-07-26 00:00:00');
    $this->validate(false,1,1,'content','');
    $this->validate(false,1,1,'content','invaliddate');
  }

  private function validate($expected,$thread_id,$user_id,$content,$created_at) {
    $this->Post->set(
      array(
        'thread_id'=>$thread_id,
        'user_id'=>$user_id,
        'content'=>$content,
        'created_at'=>$created_at
      )
    );

    $result = $this->Post->validates();

    if($expected) {
      $this->assertTrue($result);
    } else {
      $this->assertFalse($result);
    }
  }
}
