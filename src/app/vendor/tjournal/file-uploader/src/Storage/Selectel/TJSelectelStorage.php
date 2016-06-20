<?php
namespace TJ\Uploader\Storage\Selectel;

use SelectelStorage;

/**
 * Extend easmith\SelectelStorage
 *
 * Class TJSelectelStorage
 * @package TJ\Uploader
 */
class TJSelectelStorage extends SelectelStorage
{
    /**
     * Getter for private $url param
     * @return array|string
     */
    public function getUrl()
    {
        return $this->url;
    }
}