<?php
/**
 * TagFixture
 *
 */
class TagFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'tag' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 64, 'key' => 'primary', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'thread_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'unsigned' => true),
		'indexes' => array(
			'PRIMARY' => array('column' => 'tag', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'tag' => 'Lorem ipsum dolor sit amet',
			'thread_id' => 1
		),
	);

}
