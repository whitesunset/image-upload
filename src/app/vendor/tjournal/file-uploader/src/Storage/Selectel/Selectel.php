<?php
namespace TJ\Uploader\Storage\Selectel;

use TJ\Uploader\Storage\Base;
use TJ\Uploader\Storage\Selectel\TJSelectelStorage;

/**
 * Class Selectel
 * TJ Storage wrapper for unofficial Selectrel SDK
 * https://github.com/easmith/selectel-storage-php-class
 *
 * @package TJ\Uploader\Storage
 */
class Selectel extends Base
{
    private $storageID = 2;
    private $storage;
    private $count;
    private $list;
    private $container;
    private $config = [
        'container' => '',
        'credentials' => []
    ];

    /**
     * Selectel constructor.
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = array_merge($this->config, $config);

        $this->storage = new TJSelectelStorage($this->config['credentials']['user'], $this->config['credentials']['pass']);;
        $this->container = $this->storage->getContainer($this->config['container']);
    }

    /**
     * Upload file to storage using container PUT File method
     * @param $localFileName
     * @param $remoteFileName
     * @return array
     */
    public function upload($localFileName, $remoteFileName)
    {
        try {
            $array = $this->container->putFile($localFileName, $remoteFileName);
            $result = new SelectelResult($array);
            return $result;
        } catch (\Exception $e) {
            echo "There was an error uploading the file to Selectel.\n";
        }
    }

    /**
     * Delete file using container DELETE File method
     * @param $remoteFileName
     */
    public function delete($remoteFileName)
    {
        try {
            $this->container->delete($remoteFileName);
        } catch (\Exception $e) {
            echo "There was an error deleting the file from Selectel.\n";
        }
    }

    /**
     * GET file by name
     * @param string $remoteFileName
     * @return array
     */
    public function getFile($remoteFileName)
    {
        try {
            return $this->container->getFile($remoteFileName);
        } catch (\Exception $e) {
            echo "There was an error with retrieving the file from Selectel.\n";
        }
    }

    /**
     * GET container files count
     * @return int
     */
    public function getCount()
    {
        $this->count = (int)$this->container->getInfo()['x-container-object-count'];

        return $this->count;
    }

    /**
     * GET container listing
     * @param $start
     * @param $count
     * @return array
     */
    public function getList($start, $count)
    {
        $this->list = $this->container->listFiles($limit = 1000, $marker = null, $prefix = null, $path = "");
        $result = [];

        if (array_filter($this->list)) {
            for ($i = $start; $i < $start + $count; $i++) {
                if (!isset($this->list[$i])) break;

                $result[] = [
                    'id' => md5($this->list[$i] . $this->storageID),
                    'storage' => $this->storageID,
                    'url' => $this->storage->getUrl() . $this->config['container'] . '/' . $this->list[$i],
                    'name' => $this->list[$i]
                ];
            }
        }

        return $result;
    }
}