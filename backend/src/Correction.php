<?php


namespace BlobbyBob\HochwasserAhrtal2021;


class Correction
{
    public $id;
    public $media;
    public $date;
    public $name;
    public $latitude;
    public $longitude;

    public function setTypes()
    {
        if (!is_null($this->id)) $this->id = (int) $this->id;
        if (!is_null($this->date)) $this->date = (string) $this->date;
        if (!is_null($this->media)) $this->media = (int) $this->media;
        if (!is_null($this->name)) $this->name = (string) $this->name;
        if (!is_null($this->latitude)) $this->latitude = round((float) $this->latitude, 5);
        if (!is_null($this->longitude)) $this->longitude = round((float) $this->longitude, 5);
    }

    public function validateUserInput(): bool
    {
        if (!is_null($this->id)) return false;
        if (!is_null($this->date)) return false;
        if (is_null($this->media) || $this->media <= 0) return false;
        if (is_null($this->name) || strlen($this->name) == 0 || strlen($this->name) > 256) return false;
        if (is_null($this->latitude) || $this->latitude < 50.35 || $this->latitude > 50.6) return false;
        if (is_null($this->longitude) || $this->longitude < 6.6 || $this->longitude > 7.25) return false;

        return true;
    }
}