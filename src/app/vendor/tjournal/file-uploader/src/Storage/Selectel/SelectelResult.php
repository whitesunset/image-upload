<?php
/**
 * Created by PhpStorm.
 * User: whitesunset
 * Date: 20/06/16
 * Time: 15:30
 */

namespace TJ\Uploader\Storage\Selectel;


class SelectelResult
{
    private $data;

    public function __construct(array $file)
    {
        $this->data = $file;
    }

    public function getData()
    {
        return $this->data;
    }

    public function __get($key)
    {
        return $this->data[$key];
    }
}