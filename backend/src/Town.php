<?php


namespace BlobbyBob\HochwasserAhrtal2021;


class Town
{
    public $id;
    public $name;
    public $route;
    public $x;
    public $y;
    public $label;

    public function setTypes()
    {
        $this->id = (int) $this->id;
        $this->name = (string) $this->name;
        $this->route = (string) $this->route;
        $this->x = (float) $this->x;
        $this->y = (float) $this->y;
        $this->label = (string) $this->label;
    }
}