<?php

class Birds
{
    private $name;

    public function eat()
    {
        echo 'eat';

    }

    public  function  getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function fly()
    {
        echo 'fly';
    }
}

class Raven
{
    private $bird;

    function __construct($bird)
    {
      $this->bird = $bird;
    }

    public function eat()
    {
        $this->bird->eat();
    }

    public function getName()
    {
        $this->bird->getName();
    }

    public function setName()
    {
        $this->bird->setName('Raven');
    }

    public function fly()
    {
        $this->bird->fly();
    }

}

class Parrot
{
    private $bird;

    function __construct($bird)
    {
        $this->bird = $bird;
    }

    public function eat()
    {
        $this->bird->eat();
    }

    public function getName()
    {
        $this->bird->getName();
    }

    public function setName()
    {
        $this->bird->setName('Parrot');
    }

    public function fly()
    {
        $this->bird->fly();
    }

}

class Penguin
{
    private $bird;

    function __construct($bird)
    {
        $this->bird = $bird;
    }

    public function eat()
    {
        $this->bird->eat();
    }

    public function getName()
    {
        $this->bird->getName();
    }

    public function setName()
    {
        $this->bird->setName('Penguin');
    }

}


