<?php

namespace App\SP\Importer;

use App\SP\Importer\Base;

class Axis extends Base {

    protected $indexFileName = "Index"; 
    protected $sheetnameRowIndex = [0,1];
    protected $sheetnameColumnIndex = [1,1];

    public function getIndexFileName()
    {
        return $this->indexFileName;
    }

    public  function getSheetnameRowIndex()
    {
        return $this->sheetnameRowIndex;
    }

    public  function getSheetnameColumnIndex()
    {
        return $this->sheetnameColumnIndex;
    }
}