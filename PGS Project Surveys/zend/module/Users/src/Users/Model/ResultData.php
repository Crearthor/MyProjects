<?php
namespace Users\Model;

class ResultData
{
    public $idresultdata;
    public $idresult;
    public $text;
    public $idanswer;



    public function exchangeArray($data)
    {
        $this->idresultdata   = (isset($data['idresultdata']))     ? $data['idresultdata']     : null;
        $this->idresult   = (isset($data['idresult']))     ? $data['idresult']     : null;
        $this->text     = (isset($data['text']))     ? $data['text']     : "true";
        $this->idanswer   = (isset($data['idanswer'])) ? $data['idanswer'] : null;


    }

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

 }
