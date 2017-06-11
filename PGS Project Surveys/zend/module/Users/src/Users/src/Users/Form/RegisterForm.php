<?php

namespace Users\Form;
use Zend\Form\Form;

class RegisterForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('Register');
        $this->setAttribute('method', 'post');
        $this->setAttribute('enctype', 'multipart/formdata');
        $this->setAttribute('class', 'form-horizontal');
        
        $this->add([
            'name' => 'login',
            'attributes' => [
                'type' => 'text',

            ],


            'options' =>
                [
                'label' => 'Login'
                ]
            ]
        );
        
        $this->add([
            'name' => 'email',
            'attributes' => [
                'type' => 'text'
            ],
            'options' => [
                'label' => 'Email'
            ],
            'filters' => [
                'required' => 'required',
            ],
            'validators' => [
                    [
                        'name' => 'EmailAddress',
                        'options' => [
                            'messages' => [
                                \Zend\Validator\EmailAddress::INVALID_FORMAT => 'Podany adres ma nieprawidłowy format'
                            ]
                        ]
                    ]
            ]
            ]
        );
        
        $this->add([
            'name' => 'password',
            'attributes' => [
                'type' => 'password'
            ],
                'options' => [
                    'label' => 'Password'
                ]
            ]
        );
        
        $this->add([
            'name' => 'confirm_password',
            'attributes' => [
                'type' => 'password'
            ],
                'options' => [
                    'label' => 'Confirm Password'
                ]
            ]
        );

        $this->add([
                'name' => 'name',
                'attributes' => [
                    'type' => 'text'
                ],
                'options' => [
                    'label' => 'Name'
                ]
            ]
        );

        $this->add([
                'name' => 'surname',
                'attributes' => [
                    'type' => 'text'
                ],
                'options' => [
                    'label' => 'Surname'
                ]
            ]
        );

        $this->add([
            'name' => 'submit',
            'attributes' => [
                'type' => 'submit',
                'value' => 'Sign Up',
            ],
                'options' => [
                    'label' => 'Ok'
                ]
            ]
        ); 
    }
}

?>