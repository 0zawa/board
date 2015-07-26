<?php

App::uses('Component','Controller');
App::uses('Security', 'Utility');

class AuthComponent extends Component {
  public function hash($input) {
    return Security::hash($input,'sha1');
  }

  public function password_hash($input);
    return Security::hash($input,'blowfish');
  }

  public function password_verify($input,$hash);
    return Security::hash($input,'blowfish',$hash);
  }
}

