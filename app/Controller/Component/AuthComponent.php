<?php

App::uses('Component','Controller');
App::uses('Security', 'Utility');

class AuthComponent extends Component {
  public function blowfish($input) {
    return Security::hash($input,'blowfish');
  }
}

