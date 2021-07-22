<?php


namespace BlobbyBob\HochwasserAhrtal2021;


class Town
{
    public $id;
    public $name;
    public $route;
    public $x;
    public $y;
    public $latitude;
    public $longitude;
    public $zoom;
    public $label;

    public function setTypes()
    {
        $this->id = (int) $this->id;
        $this->name = (string) $this->name;
        $this->route = (string) $this->route;
        $this->x = (float) $this->x;
        $this->y = (float) $this->y;
        $this->latitude = (float) $this->latitude;
        $this->longitude = (float) $this->longitude;
        $this->zoom = (int) $this->zoom;
        $this->label = (string) $this->label;
    }
}