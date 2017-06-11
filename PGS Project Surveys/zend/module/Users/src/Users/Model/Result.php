<?php
namespace Users\Model;


class Result
{
    public $idresult;
    public $idsurvey;
    public $date;


    public function exchangeArray($data)
    {
        $this->idresult   = (isset($data['idresult']))     ? $data['idresult']     : null;
        $this->date     = (isset($data['date']))     ? $data['date']     : null;
        $this->idsurvey   = (isset($data['idsurvey'])) ? $data['idsurvey'] : null;

    }

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }
}
