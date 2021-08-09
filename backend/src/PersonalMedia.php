<?php


namespace BlobbyBob\HochwasserAhrtal2021;


class PersonalMedia
{
    public $id;
    public $date;
    public $name;
    public $email;
    public $request;

    public function setTypes() {
        if (!is_null($this->id)) $this->id = (int) $this->id;
        if (!is_null($this->date)) $this->date = (string) $this->date;
        if (!is_null($this->name)) $this->name = (string) $this->name;
        if (!is_null($this->email)) $this->email = (string) $this->email;
        if (!is_null($this->request)) $this->request = (string) $this->request;
    }

    public function validateUserInput()
    {
        if (!is_null($this->id)) return false;
        if (!is_null($this->date)) return false;
        if (is_null($this->name) || strlen($this->name) == 0 || strlen($this->name) > 128) return false;
        if (is_null($this->email) || strlen($this->email) < 5 || strlen($this->email) > 128 || strpos($this->email, '@') === false) return false;
        if (is_null($this->request) || strlen($this->request) == 0|| strlen($this->request) > 4000) return false;

        return true;
    }
}