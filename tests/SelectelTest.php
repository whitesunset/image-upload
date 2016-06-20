<?php

use phpunit\framework\TestCase;
use TJ\Uploader\Storage\Selectel\SelectelResult;
use TJ\Uploader\StorageFacade;
use TJ\Uploader\FileData;
use AWS\Result;

/**
 * Class SelectelTest
 */
class SelectelTest extends TestCase
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
     * Urls pool
     * @var array
     */
    private $urls = [
        'http://images.techtimes.com/data/images/full/239267/facebook-ceo-mark-zuckerberg.jpg',
        'http://newsweek.su/uploads/posts/2011-07/1311673900_durov_pavlik.jpg'
    ];

    /**
     * Upload local files to Selectel
     */
    public function testUploadFromFile()
    {
        $config = require 'src/app/config.php';
        $storage = new StorageFacade(2, $config['storages']['2']);

        $result = $storage->uploadFromFile($this->files);

        $this->assertInstanceOf(SelectelResult::class, $result[0], 'Returned array has "url" key');
    }

    /**
     * Upload remote files to Selectel
     */
    public function testUploadFromUrl()
    {
        $config = require 'src/app/config.php';
        $storage = new StorageFacade(2, $config['storages']['2']);

        $result = $storage->uploadFromUrl($this->urls[0]);

        $this->assertInstanceOf(SelectelResult::class, $result, 'Returned array has "url" key');
    }

    /**
     * Get uploaded image info
     */
    public function testGetFile()
    {
        $config = require 'src/app/config.php';
        $storage = new StorageFacade(2, $config['storages']['2']);

        $result = $storage->getFile($this->files['name'][0]);

        $this->assertArrayHasKey('info', $result, 'Returned array has "info" key');
        $this->assertArrayHasKey('url', $result['info'], 'Returned subarray "info" has "url" key');
    }

    /**
     * Get images count. Expect more than 0
     */
    public function testGetCount()
    {
        $config = require 'src/app/config.php';

        $storage = new StorageFacade(2, $config['storages']['2']);
        $result = $storage->getCount();

        $this->assertTrue(is_int($result), 'Returned int');
        $this->assertTrue($result > 0, 'Images count more than 0');
    }

    /**
     * Get images list. Expect it is array
     */
    public function testGetList()
    {
        $config = require 'src/app/config.php';

        $storage = new StorageFacade(2, $config['storages']['2']);
        $result = $storage->getList();

        $this->assertTrue(is_array($result), 'Returned array of images');
    }

    /**
     * Delete file and check it is deleted
     */
    public function testDelete()
    {
        $config = require 'src/app/config.php';

        $storage = new StorageFacade(2, $config['storages']['2']);
        $storage->delete($this->files['name'][1]);
        $file = $storage->getFile($this->files['name'][1]);

        $this->assertEquals(404, $file['header']['HTTP-Code']);
    }
}