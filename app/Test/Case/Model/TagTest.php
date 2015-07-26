<?php
App::uses('Tag', 'Model');

/**
 * Tag Test Case
 *
 */
class TagTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.tag'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Tag = ClassRegistry::init('Tag');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Tag);

		parent::tearDown();
	}

  public function testValidation() {
    $this->validate(true,'tag',1);
    $this->validate(false,'',1);
    $this->validate(false,'toolongtaggggggggggggggggggggggggggggggggggggggggggggggggggggggggg',1);
  }

  private function validate($expected,$tag,$thread_id) {
    $this->Tag->set(
      array(
        'tag'=>$tag,
        'thread_id'=>$thread_id,
      )
    );

    $result = $this->Tag->validates();

    if($expected) {
      $this->assertTrue($result);
    } else {
      $this->assertFalse($result);
    }
  }



}
