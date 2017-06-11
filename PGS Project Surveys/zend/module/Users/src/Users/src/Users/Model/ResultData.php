<?php
namespace Users\Model;
class ResultData
{
    public $idresult;
    public $idanswer;
    public $idquestion;
    public $text;
    public function exchangeArray($data)
    {
        $this->idresult    = (isset($data['idresult']))     ? $data['idresult']     : null;
        $this->text  = (isset($data['text']))  ? $data['text']  : "true";
        $this->idquestion   = (isset($data['idquestion'])) ? $data['idquestion'] : null;
        $this->idanswer = (isset($data['idanswer'])) ? $data['idanswer'] : null;
    }
}