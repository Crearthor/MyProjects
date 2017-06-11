<?php
    namespace Users\Model;
class Result
{
    public $idsurvey;
    public function exchangeArray($idsurvey)
    {
        $this->idsurvey     = (isset($idsurvey) ? $idsurvey     : null);
    }
}



