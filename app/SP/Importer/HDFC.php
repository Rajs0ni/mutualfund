<?php

namespace App\SP\Importer;

use App\SP\Importer\Base;

class HDFC extends Base{

    protected $indexFileName = "Hyperlinks"; 
    protected $sheetnameRowIndex = 0;
    protected $sheetnameColumnIndex = 0;

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