<?php
App::uses('UsersController', 'Controller');

/**
 * UsersController Test Case
 *
 */
class UsersControllerTest extends ControllerTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.user'
	);

/**
 * ユーザー取得テスト
 *
 * @return void
 */
	public function testView() {
		$json_response = $this->testAction('/users/view/1');
		$response = json_decode($json_response);
		
		$expected = array(
			'User' => array('id' => 1, 'name' => 'ozawa'),
		);
		$this->assertEquals($expected, $result);
	}

/**
 * ユーザー登録テスト
 *
 * @return void
 */
	public function testAdd() {
		$data = array(
    	'User' => array(
        'name' => 'ozawa2',
        'password' => 'rawpassword2',
        'mail' => 'ozawa2@example.com',
    	)
    );
		$json_response = $this->testAction('/users',array('data' => $data, 'method' => 'post'));
		$response = json_decode($json_response);
		
		$expected = array(
			'User' => array('id' => 2, 'name' => 'ozawa2'),
		);
		$this->assertEquals($expected, $result);
	}
}
