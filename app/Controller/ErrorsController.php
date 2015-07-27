<?php
/**
 * 例外発生時にjson形式のレスポンスを返すためのコントローラー
 *
 */

class ErrorsController extends AppController {
  public $name = 'Errors';
  public $autoRender = false;

  public function error404() {
    $this->send_ng('not found');
  }
}
