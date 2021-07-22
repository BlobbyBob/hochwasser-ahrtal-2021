<?php


namespace BlobbyBob\HochwasserAhrtal2021;


class Media
{
    public $id;
    public $town;
    public $title;
    public $timestamp;
    public $latitude;
    public $longitude;
    public $type;
    public $data;

    public function setTypes()
    {
        $this->id = (int) $this->id;
        $this->town = (int) $this->town;
        $this->title = (string) $this->title;
        $this->timestamp = (string) $this->timestamp;
        $this->latitude = (float) $this->latitude;
        $this->longitude = (float) $this->longitude;
        $this->data = (string) $this->data;
    }
}