<?php
App::uses('AppModel', 'Model');
/**
 * Tagモデル
 *
 */
class Tag extends AppModel {

/**
 * プライマリーキー.
 *
 * @var string
 */
	public $primaryKey = 'tag';

/**
 * バリデーションルール.
 *
 * @var array
 */
	public $validate = array(
		'tag' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
      'maxLength' => array(
        'rule' => array('maxLength',64),
      ),
		),
		'thread_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);
}
