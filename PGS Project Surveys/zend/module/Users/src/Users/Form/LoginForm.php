<?php

namespace Users\Form;


use Zend\Form\Form;

class LoginForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('users\form');
        $this->setAttribute('method', 'post');

        $this->add([
            'name' => 'login',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => [
                'placeholder' => 'login',
                'required' => 'required'
            ],
            'options' => [
                'label' => 'Login'
            ]
        ]);

        $this->add([
            'name' => 'password',
            'type' => 'Zend\Form\Element\Password',
            'attributes' => [
                'placeholder' => '#',
                'required' => 'required'
            ],
            'options' => [
                'label' => 'Password'
            ]
        ]);

        $this->add([
            'name' => 'submit',
            'attributes' => [
                'type' => 'submit',
                'value' => 'Log in'
            ],
            'options' => [
                'label' => 'Ok'
            ]
        ]);
    }

}