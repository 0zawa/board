<?php
App::uses('Thread', 'Model');

/**
 * Thread Test Case
 *
 */
class ThreadTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.thread'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Thread = ClassRegistry::init('Thread');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Thread);

		parent::tearDown();
	}

  public function testValidation() {
    $this->validate(true,1,'title','2015-07-26 00:00:00','2015-07-26 00:00:00');
    $this->validate(false,1,'','2015-07-26 00:00:00','2015-07-26 00:00:00');
    $this->validate(false,1,'title','','2015-07-26 00:00:00');
    $this->validate(false,1,'title','2015-07-26 00:00:00','');
    $this->validate(false,1,'title','invaliddate','2015-07-26 00:00:00');
    $this->validate(false,1,'title','2015-07-26 00:00:00','invalid date');
    $this->validate(false,1,'toolongtitleeeeeeeeeeeeeeeeeeeeeeeeeeeeee','2015-07-26 00:00:00','2015-07-26 00:00:00');
  }

  private function validate($expected,$user_id,$title,$created_at,$updated_at) {
    $this->Thread->set(
      array(
        'user_id'=>$user_id,
        'title'=>$title,
        'created_at'=>$created_at,
        'updated_at'=>$updated_at,
        )
      );

    $result = $this->Thread->validates();

    if($expected) {
      $this->assertTrue($result);
    } else {
      $this->assertFalse($result);
    }
  }

}
