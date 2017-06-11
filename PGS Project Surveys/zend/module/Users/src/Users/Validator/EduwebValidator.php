<?php

namespace Users\Validator;
use Zend\Validator\AbstractValidator;


class EduwebValidator extends AbstractValidator
{
    const LEX = 'eduweb';

    protected $messageTemplates = [
        self::LEX => "'%value%' nie jest poprawną wartością"
    ];


    public function isValid($value)
    {
        $this->setValue($value);

        if($value != self::LEX)
        {
            $this->error(self::LEX);
            return false;
        }
        return true;
    }
}