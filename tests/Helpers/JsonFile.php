<?php

namespace Softpampa\Moip\Tests\Helpers;

class JsonFile {

    /**
     * @var $file
     */
    protected $file;

    /**
     * @var  string  name
     */
    protected $name;

    /**
     * @var  int  $size
     */
    protected $size;

    /**
     * Constructor
     *
     * @param  string  $name
     */
    public function __construct($name)
    {
        $this->name = 'tests/Mocks/' . $name;
    }

    /**
     * Open a file
     *
     * @param  string $mode
     * @return $file
     */
    private function openFile($mode = 'r')
    {
        $this->file = fopen($this->name, $mode);
        $this->size = filesize($this->name);

        return $this->file;
    }

    /**
     * Read file
     *
     * @return string
     */
    public function readFile()
    {
        return fread($this->openFile('r'), $this->size);
    }

    /**
     * Read a Json file
     *
     * @param  string  $filename
     * @return JsonFile
     */
    public static function read($filename)
    {
        $self = new self($filename);

        return $self->readFile('r');
    }

    /**
     * Close file
     *
     * @return void
     */
    public function __destruct()
    {
        fclose($this->file);
    }

}
