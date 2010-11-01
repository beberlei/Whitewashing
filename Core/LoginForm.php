<?php

namespace Whitewashing\Core;

use Symfony\Component\Form\Form;

class LoginForm extends Form
{
    protected function configure()
    {
        $this->add(new TextField('username'));
        $this->add(new PasswordField('password'));
    }
}