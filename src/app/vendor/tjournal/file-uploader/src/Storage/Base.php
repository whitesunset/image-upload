<?php
namespace TJ\Uploader\Storage;

use TJ\Uploader\Storage\StorageInterface;

/**
 * Third-party storages SDK wrapper
 *
 * Class Base
 * @package TJ\Uploader\Storage
 */
abstract class Base implements StorageInterface
{
    /**
     * Storage ID
     * @var
     */
    private $storageID;
    /**
     * Storage object
     * @var object $storage
     */
    private $storage;
    /**
     * Container object
     * @var object $container
     */
    private $container;
    /**
     * Container files count
     * @var int $count
     */
    private $count;
    /**
     * Container files list
     * @var array $list
     */
    private $list;
    /**
     * Storage config - auth credentials etc.
     * @var array $config
     */
    private $config;

    /**
     * Base constructor. Create storage object with $config param
     * @param $config
     */
    public function __construct(array $config = [])
    {
        $this->config = array_merge($this->config, $config);
    }

    /**
     * Get remote file by name
     * @param string $remoteFileName
     */
    public function getFile($remoteFileName)
    {

    }

    /**
     * Get container files count
     */
    public function getCount()
    {

    }

    /**
     * Get container files list
     * @param $start
     * @param $count
     * @return array
     */
    public function getList($start, $count)
    {

    }

    /**
     * Upload file to container by local name
     * @param $localFileName
     * @param $remoteFileName
     */
    public function upload($localFileName, $remoteFileName)
    {

    }

    /**
     * Delete file from container by name
     * @param $remoteFileName
     */
    public function delete($remoteFileName)
    {

    }
}