<?php

namespace Users\Validator;
use Zend\InputFilter\InputFilter;

class RegisterFilter extends InputFilter
{
    public function __construct()
    {
        $this->add([
            'name' => 'email',
            'required' => true,
            'validators' => [
                [
                    'name' => 'EmailAddress',
                    'options' => [
                        'domain' => true
                    ]
                ]
            ]
            ]
        );
        $this->add([
                'name' => 'login',
                'required' => true,
                'validators' => [
                    [
                        'name' => 'StringLength',
                        'options' => [
                            'encoding' => 'UTF-8',
                            'min' => 4,
                            'max' => 20,
                        ],
                    ],

                ],
            ]
        );
        $this->add([
                'name' => 'password',
                'required' => true,
                'validators' => [
                    [
                        'name' => 'StringLength',
                        'options' => [
                            'encoding' => 'UTF-8',
                            'min' => 4,
                            'max' => 20,
                        ],
                    ],

                ],
            ]
        );
        
        $this->add([
            'name' => 'name',
            'required' => true,
            'filters' => [
                [
                    'name' => 'StripTags',
                ],
            ],
            'validators' => [
                [
                    'name' => 'StringLength',
                    'options' => [
                        'encoding' => 'UTF-8',
                        'min' => 4,
                        'max' => 20,
                    ],
                ],

            ],
        ]);
        $this->add([
            'name' => 'surname',
            'required' => true,
            'filters' => [
                [
                    'name' => 'StripTags',
                ],
            ],
            'validators' => [
                [
                    'name' => 'StringLength',
                    'options' => [
                        'encoding' => 'UTF-8',
                        'min' => 4,
                        'max' => 20,
                    ],
                ],

            ],
        ]);
    }
}

?>