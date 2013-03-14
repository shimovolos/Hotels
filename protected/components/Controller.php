<?php

class Controller extends CController
{
    public $client;
    public $dataDB;

    public function init()
    {
        $this->client = new HotelsProAPI();
        $this->dataDB = new DataReader();
    }
}