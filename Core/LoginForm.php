<?php

namespace Whitewashing\Core;

class LoginForm extends \Zend_Form
{
    public function init()
    {
        $this->setMethod('POST')->setName('loginform');

        $this->addElement('text', 'username', array(
            'label' => 'Username:',
            'required' => true,
        ));
        $this->addElement('password', 'password', array(
            'label' => 'Password',
            'required' => true,
        ));
        $this->addElement('submit', 'btn_submit', array(
            'label' => 'Login'
        ));

        $this->setDecorators(array(array('ViewScript', array(
            'viewScript' => 'forms/login.phtml',
        ))));
    }
}