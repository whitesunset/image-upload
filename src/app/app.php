<?php
namespace TJ\Dashboard;

use TJ\Uploader\FileDataFacade;
use TJ\Uploader\StorageFacade;

require '../vendor/autoload.php';

/**
 * Class App
 * @package TJ\Dashboard
 */
class App
{
    /**
     * Storages config
     * @var mixed
     */
    private $config;
    /**
     * Current page imsges
     * @var array $images
     */
    private $images;
    /**
     * Images count for each storage
     * @var array $storagesImagesCount
     * [0] - AWS
     * [1] - Selectel
     */
    private $storagesImagesCount;
    /**
     * Pages count
     * @var int $pagesCount
     */
    private $pagesCount;
    /**
     * Pagination array
     * Contain
     * @var $storages array => $storagesImagesCount
     * @var $count => total images count in both storages
     * @var $pages => $pagesCount
     * @var array $pagination
     */
    private $pagination;

    /**
     * App constructor.
     */
    public function __construct()
    {
        $this->config = require 'config.php';
        $this->setImagesCount();
        $this->setPagination();
    }

    /**
     * Get Storage config by ID
     * 1 - AWS
     * 2 - Selectel
     * @param $storageID
     * @return mixed
     */
    protected function getStorageConfig($storageID)
    {
        return $this->config['storages'][$storageID];
    }

    /**
     * Upload file from request
     * @param $request
     * @return \TJ\Uploader\FileInterface
     */
    public function upload($request)
    {
        $storage = $_POST['storage'];
        $auth = $this->getStorageConfig($storage);
        $storage = new StorageFacade($request['storage'], $auth);
        
        if ($request['source_type'] == 'file') {
            $result = $storage->uploadFromFile($_FILES['files']);
            $urls = [];
            foreach ($result as $file){
                $fileData = new FileDataFacade($file);
                $urls[] = $fileData->getUrl();
            }
        }
        if ($request['source_type'] == 'url') {
            $result = $storage->uploadFromUrl($request['url']);
            $urls = [];
            $fileData = new FileDataFacade($result);
            $urls[] = $fileData->getUrl();
        }

        return $urls;
    }

    /**
     * Set $pagination mixed array
     * @param int $pageSize
     */
    protected function setPagination($pageSize = 12)
    {
        $countArray = $this->storagesImagesCount;
        $this->pagesCount = array_sum($this->storagesImagesCount);
        $this->pagination = [
            'storages' => $countArray,
            'count' => $this->pagesCount,
            'pages' => ceil($this->pagesCount / $pageSize),
        ];
    }

    /**
     * Getter for $pagination
     * @return mixed
     */
    public function getPagination()
    {
        return $this->pagination;
    }

    /**
     * Get
     * @param $request
     * @return mixed
     */
    public function getFileSize($request)
    {
        $storage = urldecode($request['storage']);
        $auth = $this->getStorageConfig($storage);

        $storage = new StorageFacade($request['storage'], $auth);
        $filename = urldecode($request['filename']);
        $file = $storage->getFile($filename);

        $file = new FileDataFacade($file);

        return $file->getSize();
    }

    /**
     * Get images list for current page
     * @param $request
     * @return array
     */
    public function getList($request)
    {
        $currentPage = (int)urldecode($request['current_page']);
        $pageSize = (int)urldecode($request['page_size']);

        $this->setPagination($pageSize);

        $storage = new StorageFacade(1, $this->getStorageConfig(1));
        $start = ($currentPage - 1) * $pageSize;
        $images = $storage->getList($start, $pageSize);

        /**
         * If $pageSize * $currentPage > count($images) from AWS:
         * get images from Selectel and merge it into one array
         */
        if (count($images) < $pageSize) {
            $start = ($currentPage - 1) * $pageSize + count($images) - $this->storagesImagesCount[0];
            $rest = $pageSize - count($images);

            $storage = new StorageFacade('2', $this->getStorageConfig(2));
            $images = array_merge($images, $storage->getList($start, $rest));
        }

        $this->images = $images;

        $result = [
            'images' => $this->images,
            'pagination' => $this->pagination
        ];

        return $result;
    }

    /**
     * Delete file by StorageID and File name
     * @param $request
     */
    public function delete($request)
    {
        $storage = $request['storage'];
        $config = $this->getStorageConfig($storage);

        $destroyer = new StorageFacade($request['storage'], $config);
        $destroyer->delete($request['name']);
    }

    /**
     * Set images count for each Storage
     */
    protected function setImagesCount()
    {
        $config = $this->getStorageConfig(1);
        $list = new StorageFacade(1, $config);
        $aws = $list->getCount();

        $config = $this->getStorageConfig(2);
        $list = new StorageFacade('2', $config);
        $selectel = $list->getCount();

        $this->storagesImagesCount = [
            $aws,
            $selectel
        ];
    }

    /**
     * @return mixed
     */
    public function getImagesCount()
    {
        return $this->storagesImagesCount;
    }

    /**
     * Get config instance
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            return self::$instance = new Config();
        }
        return self::$instance;
    }

    /**
     * Check images type before upload
     * @param $files array from $_FILES
     * @return bool
     */
    public function validateFiles($files)
    {
        $result = true;
        foreach ($files['files']['tmp_name'] as $file){
            $allowedTypes = array(IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF);
            $detectedType = exif_imagetype($file);
            if(!in_array($detectedType, $allowedTypes)){
                $result = false;
            }
        }
        return $result;
    }

    /**
     * Check URL before upload
     * @param $url
     * @return mixed
     */
    public function validateUrl($url)
    {
        return filter_var(urldecode($url), FILTER_VALIDATE_URL);
    }

    /**
     * @return string
     */
    public function getContainer()
    {
        return $this->container;
    }

    private function __clone()
    {
        // prevent clonning
    }

    private function __wakeup()
    {
        // prevent unserialize
    }
}