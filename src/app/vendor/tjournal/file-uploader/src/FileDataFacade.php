<?php
namespace TJ\Uploader;

use Aws\Result;
use TJ\Uploader\FileInterface;
use TJ\Uploader\Storage\Selectel\SelectelResult;

/**
 * Class FileData
 * @package TJ\Uploader
 */
class FileDataFacade implements FileInterface
{
    private $file;

    public function __construct($remoteFile)
    {
        $this->file = $remoteFile;
    }

    /**
     * Image Data
     * @return array
     */
    public function getData()
    {
        return $this->file;
    }

    /**
     * Image URL
     * @return string
     */
    public function getUrl()
    {
        if($this->file instanceof Result){
            return $this->file->get('@metadata')['effectiveUri'];
        }
        if($this->file instanceof SelectelResult){
            return $this->file->url;
        }
    }

    /**
     * Image size
     * @return integer|null
     */
    public function getSize()
    {
        $ch = curl_init($this->getUrl());

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, TRUE);
        curl_setopt($ch, CURLOPT_NOBODY, TRUE);

        $data = curl_exec($ch);
        $size = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);

        curl_close($ch);

        return $size;
    }
}