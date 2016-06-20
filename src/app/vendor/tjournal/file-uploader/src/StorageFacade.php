<?php
namespace TJ\Uploader;

use TJ\Uploader\Storage\AWS\AWS;
use TJ\Uploader\Storage\Selectel\Selectel;
use TJ\Uploader\UploaderInterface;
use TJ\Uploader\DestroyerInterface;

/**
 * Class StorageFacade
 * @package TJ\Uploader
 */
class StorageFacade implements UploaderInterface, DestroyerInterface, ListInterface
{
    /**
     * @var storageID
     * @var $storage
     */
    private $storageID;
    /**
     * @var TJSelectelStorage
     */
    private $storage;

    /**
     * @param integer $storage 1 - Amazon, 2 — Selectel
     * @param array $storageAuthParameters Данные для авторизации в хранилище
     */
    public function __construct($storage, $storageAuthParameters)
    {
        $this->storageID = $storage;

        switch ($this->storageID) {
            case 1:
                $this->storage = new AWS($storageAuthParameters);
                break;
            case 2:
                $this->storage = new Selectel($storageAuthParameters);
                break;
            default:
                $this->storage = new AWS($storageAuthParameters);
                break;
        }
    }

    /**
     * Replace spaces with inderscores
     *
     * @param $string
     * @return mixed
     */
    protected function sanitize($string)
    {
        return str_replace(' ', '_', $string);
    }

    /**
     * @param $localFileName
     * @param $remoteFileName
     * @return FileData
     */
    protected function upload($localFileName, $remoteFileName)
    {
        $localFileName = $this->sanitize($localFileName);
        $remoteFileName = $this->sanitize($remoteFileName);

        $result = $this->storage->upload($localFileName, $remoteFileName);

        return $result;
    }

    /**
     * @param  array $files
     * @return FileInterface
     */
    public function uploadFromFile($files)
    {
        $result = [];

        for ($i = 0; $i < count($files['name']); $i++) {
            $localFileName = $files['tmp_name'][$i];
            $remoteFileName = $files['name'][$i];

            $result[] = $this->upload($localFileName, $remoteFileName);
        }

        return $result;
    }

    /**
     * @param  string $url Ссылка
     * @return FileInterface
     */
    public function uploadFromUrl($url)
    {
        $fileName = basename($url);
        $uploadDir = sys_get_temp_dir() . '/';
        $localFileName = $uploadDir . $fileName;
        $remoteFileName = $fileName;

        file_put_contents($localFileName, file_get_contents($url));
        $result = $this->upload($localFileName, $remoteFileName);
        unlink($localFileName);

        return $result;
    }

    /**
     * Upload images from $_FILES
     * @param  string $remoteFileName remote file name to delete
     * @return UploaderInterface
     */
    public function delete($remoteFileName)
    {
        $this->storage->delete($remoteFileName);
    }

    /**
     * Get image by name
     * @param $remoteFileName
     * @return array
     */
    public function getFile($remoteFileName)
    {
        return $this->storage->getFile($remoteFileName);
    }

    /**
     * Get images count from storage
     * @return int
     */
    public function getCount()
    {
        return $this->storage->getCount();
    }

    /**
     * Get images list from storage
     * @param $start int start index
     * @param $count int images count to retrieve (maximum 1000)
     * @return array
     */
    public function getList($start = 0, $count = 1000)
    {
        return $this->storage->getList($start, $count);
    }
}