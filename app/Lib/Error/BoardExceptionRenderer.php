<?php
/*
 * 例外発生時にjson形式のレスポンスを返すための独自定義レンダラー
 *
 */

App::uses('ExceptionRenderer', 'Error');

class BoardExceptionRenderer extends ExceptionRenderer {
  public function notFound($error) {
    $this->controller->redirect(array('controller' => 'errors', 'action' => 'error404'));
  }
  public function missingController($error) {
    $this->controller->redirect(array('controller' => 'errors', 'action' => 'error404'));
  }
  public function missingAction($error) {
    $this->controller->redirect(array('controller' => 'errors', 'action' => 'error404'));
  }
  public function missingWidget($error) {
    $this->controller->redirect(array('controller' => 'errors', 'action' => 'error404'));
  }
}
