<?php
App::uses('Token', 'Model');

/**
 * Token Test Case
 *
 */
class TokenTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.token'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Token = ClassRegistry::init('Token');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Token);

		parent::tearDown();
	}

  public function testValidation() {
    $this->validate(true,1,'token','2015-07-26 00:00:00');
    $this->validate(false,1,'','2015-07-26 00:00:00');
    $this->validate(false,1,'token','');
    $this->validate(false,1,'token','invaliddate');
    $this->validate(false,1,'toolongtokennnnnnnnnnnnnnnnnnnnnnnnnnnnnnnn','2015-07-26 00:00:00');
  }

  private function validate($expected,$user_id,$token,$expired_at) {
    $this->Token->set(
      array(
        'user_id'=>$user_id,
        'token'=>$token,
        'expired_at'=>$expired_at
      )
    );

    $result = $this->Token->validates();

    if($expected) {
      $this->assertTrue($result);
    } else {
      $this->assertFalse($result);
    }
  }


}
