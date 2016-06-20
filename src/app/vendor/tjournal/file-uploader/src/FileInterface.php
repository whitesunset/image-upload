<?php
namespace TJ\Uploader;

interface FileInterface
{
    /**
     * Image data
     * @return array
     */
    public function getData();

    /**
     * Image URL
     * @return string
     */
    public function getUrl();

    /**
     * Image size
     * @return integer|null
     */
    public function getSize();
}