<?php
use phpunit\framework\TestCase;
use TJ\Uploader\StorageFacade;
use TJ\Uploader\FileDataFacade;

/**
 * Class FileDataTest
 */
class FileDataTest extends TestCase
{
    /**
     * Emulate $_FILES
     * @var array
     */
    private $files = [
        'name' => [
            'logo.png',
            'siliconrus-logo.png'
        ],

        'tmp_name' => [
            __DIR__ . '/tmp/logo.jpg',
            __DIR__ . '/tmp/siliconrus-logo.png'
        ]
    ];

    /**
     * Get File URL from image, uploaded to AWS
     */
    public function testGetUrlAWS()
    {
        $config = require 'src/app/config.php';
        $storage = new StorageFacade(1, $config['storages']['1']);

        $result = $storage->uploadFromFile($this->files);
        $fileData = new FileDataFacade($result[0]);
        $url = $fileData->getUrl();

        $this->assertTrue(strlen($url) > 0, 'Returned Url length more than 0');
    }

    /**
     * Get File URL from image, uploaded to AWS
     */
    public function testGetUrlSelectel()
    {
        $config = require 'src/app/config.php';
        $storage = new StorageFacade(2, $config['storages']['2']);

        $result = $storage->uploadFromFile($this->files);
        $fileData = new FileDataFacade($result[1]);
        $url = $fileData->getUrl();

        $this->assertTrue(strlen($url) > 0, 'Returned Url length more than 0');
    }

    /**
     * Get remote image size (AWS)
     */
    public function testGetSizeAWS()
    {
        $config = require 'src/app/config.php';
        $storage = new StorageFacade(1, $config['storages']['1']);

        $result = $storage->uploadFromFile($this->files);
        $fileData = new FileDataFacade($result[0]);

        $sizeLocal = filesize($this->files['tmp_name'][0]);
        $sizeRemote = $fileData->getSize();

        $this->assertEquals($sizeLocal, $sizeRemote, 'Uploaded images size equals to local image size');
    }

    /**
     * Get remote image size (AWS)
     */
    public function testGetSizeSelectel()
    {
        $config = require 'src/app/config.php';
        $storage = new StorageFacade(2, $config['storages']['2']);

        $result = $storage->uploadFromFile($this->files);
        $fileData = new FileDataFacade($result[1]);

        $sizeLocal = filesize($this->files['tmp_name'][1]);
        $sizeRemote = $fileData->getSize();

        $this->assertEquals($sizeLocal, $sizeRemote, 'Uploaded images size equals to local image size');
    }
}