<?php
namespace TJ\Uploader;

interface UploaderInterface
{
    /**
     * Choose storage
     * @param integer $storage 1 - Amazon, 2 — Selectel
     * @param array $storageAuthParameters Storage auth parameters
     */
    public function __construct($storage, $storageAuthParameters);

    /**
     * Upload images from $_FILES
     * @param  array $file uploaded images from $_FILES
     * @return FileInterface
     */
    public function uploadFromFile($file);

    /**
     * Upload image from URL
     * @param  string $url link
     * @return FileInterface
     */
    public function uploadFromUrl($url);
}
