<?php
class SelectBox{
    public $items = array();
    public $defaultText = '';
    public $title = '';

    public function __construct($title, $default){
        $this->defaultText = $default;
        $this->title = $title;
    }

    public function addItem($name, $connection = NULL){
        $this->items[$name] = $connection;
        return $this;
    }

    public function toJSON(){
        return json_encode($this);
    }
}