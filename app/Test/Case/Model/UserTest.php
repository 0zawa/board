<?php
App::uses('User', 'Model');

/**
 * User Test Case
 *
 */
class UserTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.user'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->User = ClassRegistry::init('User');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->User);
		parent::tearDown();
	}
  
  public function testValidation() {
    $this->validate(true,'ozawa','qwerty','ozawa@exapmle.com'); 
    $this->validate(false,'','qwerty','exapmle.com'); 
    $this->validate(false,'ozawa','','ozawa@exapmle.com'); 
    $this->validate(false,'ozawa','qwerty',''); 
    $this->validate(false,'overtenwords','qwerty','ozawa@exapmle.com'); 
    $this->validate(false,'ozawa','qwerty','noatmark'); 
  }

  private function validate($expected,$name,$password,$mail) {
    $this->User->set(
      array(
        'name'=>$name,
        'password'=>$password,
        'mail'=>$mail
      )
    );

    $result = $this->User->validates();

    if($expected) {
      $this->assertTrue($result);
    } else {
      $this->assertFalse($result);
    }
  }

}
