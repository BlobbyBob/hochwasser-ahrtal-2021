<?php


namespace BlobbyBob\HochwasserAhrtal2021;


use DateTime;
use DateTimeInterface;

class Media
{
    public $id;
    public $town;
    public $title;
    public $timestamp;
    public $latitude;
    public $longitude;
    public $type;
    public $format;
    public $data;
    public $disabled;

    public function setTypes()
    {
        $this->id = (int) $this->id;
        $this->town = (int) $this->town;
        $this->title = (string) $this->title;
        $this->timestamp = (new DateTime($this->timestamp))->format(DateTimeInterface::ISO8601);
        $this->latitude = (float) $this->latitude;
        $this->longitude = (float) $this->longitude;
        $this->type = (string) $this->type;
        $this->format = (string) $this->format;
        $this->data = (string) $this->data;
        $this->disabled = (bool) $this->disabled;
    }
}