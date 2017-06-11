<?php

namespace Users\Form;

use Zend\Form\Form;
use Zend\Session\Container;
//tutaj nadajemy nazwy formularza
class SurveyForm extends Form
{
    public function __construct($name = null)
    {
        // We will ignore the name provided to the constructor
        parent::__construct('survey');

        $this->add([
            'name' => 'idsurvey',
            'type' => 'hidden',
        ]);

        $this->add([
            'name' => 'iduser',
            'type' => 'hidden',
        ]);

        $this->add([
            'name' => 'idquestion',
            'type' => 'hidden',
        ]);

        $this->add([
            'name' => 'idanswer',
            'type' => 'hidden',
        ]);

        $this->add([
            'name' => 'title',
            'type' => 'text',
            'options' => [
                'label' => 'Title',
            ],
        ]);

        $this->add([
            'name' => 'description',
            'type' => 'text',
            'options' => [
                'label' => 'Description',
            ],
        ]);
        $this->add([
            'name' => 'status',
            'type' => 'hidden',
        ]);

        $this->add([
            'name' => 'date',
            'type' => 'hidden'
        ]);

        $this->add([
            'name' => 'submit',  //Confirm
            'type' => 'submit',
            'attributes' => [
                'value' => 'Go',
                'id'    => 'submitbutton',
            ],
        ]);

        $this->add([
            'name' => 'submitq',  //Add Question
            'type' => 'submit',
            'attributes' => [
                'value' => 'Go',
                'id'    => 'submitbutton',
            ],
        ]);

        $this->add([
            'name' => 'submita',   //Add Answer
            'type' => 'submit',
            'attributes' => [
                'value' => 'Go',
                'id'    => 'submitbutton',
            ],
        ]);


        $this->add([
            'name' => 'submitnq',  //Next Question
            'type' => 'submit',
            'attributes' => [
                'value' => 'Go',
                'id'    => 'submitbutton',
            ],
        ]);

        $this->add([
            'name' => 'submitpq',  //previous question
            'type' => 'submit',
            'attributes' => [
                'value' => 'Go',
                'id'    => 'submitbutton',
            ],
        ]);

        $this->add([
            'name' => 'submitdq',           //delete question
            'type' => 'submit',
            'attributes' => [
                'value' => 'Go',
                'id'    => 'submitbutton',
            ],
        ]);



        $this->add([
            'name' => 'text',
            'type' => 'text',
            'options' => [
                'label' => 'Question',
            ],
        ]);

        $session = new Container('creation');

        for($i=1;$i<=$session->offsetGet('nanswer');$i++)
        {
            $this->add([
                'name' => "type".$i,
                'type' => 'select',
                'options' => [
                    'label' => 'Type',
                    'value_options' => array(
                        'text' => 'Open',
                        'checkbox' => 'Multiple choice',
                        'radio' => 'Single Choice',
                    ),
                ],
            ]);

            $this->add([
                'name' => "text".$i,
                'type' => 'text',
                'options' => [
                    'label' => 'Answer',
                ],
            ]);
            $this->add([
                'name' => 'submitda'.$i,   //Delete Answer
                'type' => 'submit',
                'attributes' => [
                    'value' => 'Go',
                    'id'    => 'submitbutton',
                ],
            ]);
        }
    }
}