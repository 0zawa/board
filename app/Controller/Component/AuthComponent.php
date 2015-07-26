<?php

App::uses('Component','Controller');
App::uses('Security', 'Utility');

class AuthComponent extends Component {
  public function hash($input) {
    return Security::hash($input,'sha1');
  }
}

