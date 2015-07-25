<?php
class DATABASE_CONFIG {

  public $default = array();
  public $test = array();

  public function __construct()
  {
    $this->default = array(
        'datasource' => 'Database/Mysql',
        'persistent' => false,
        'host'      => env("DB_HOST"),
        'login'     => env('DB_USER'),
        'password'  => env('DB_PASS'),
        'database' => 'board',
        'encoding' => 'utf8'
        );	

    $this->test = array(
        'datasource' => 'Database/Mysql',
        'persistent' => false,
        'host'      => env("DB_HOST"),
        'login'     => env('DB_USER'),
        'password'  => env('DB_PASS'),
        'database' => 'test',
        'encoding' => 'utf8'
        );
  }

}
