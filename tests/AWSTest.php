<?php
use phpunit\framework\TestCase;
use TJ\Uploader\StorageFacade;
use AWS\Result;

/**
 * Class AWSTest
 */
class AWSTest extends TestCase
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
     * Upload local files to AWS
     */
    public function testUploadFromFile()
    {
        $config = require 'src/app/config.php';

        $storage = new StorageFacade(1, $config['storages']['1']);
        $result = $storage->uploadFromFile($this->files);

        $this->assertInstanceOf(Result::class, $result[0], 'Returned object is instance of \AWS\Reslut class');
    }

    /**
     * Upload remote files to AWS
     */
    public function testUploadFromUrl()
    {
        $config = require 'src/app/config.php';

        $storage = new StorageFacade(1, $config['storages']['1']);
        $result = $storage->uploadFromUrl($this->urls[0]);

        $this->assertInstanceOf(Result::class, $result, 'Returned object is instance of \AWS\Reslut class');
    }

    /**
     * Get uploaded image info
     */
    public function testGetFile()
    {
        $config = require 'src/app/config.php';
        $storage = new StorageFacade(1, $config['storages']['1']);

        $result = $storage->getFile($this->files['name'][0]);

        $this->assertInstanceOf(Result::class, $result, 'Returned object is instance of \Aws\Reslut class');
        $this->assertInstanceOf(\GuzzleHttp\Psr7\Stream::class, $result->get('Body'), 'Returned object Body is instance of \GuzzleHttp\Psr7\Stream');
    }

    /**
     * Get images count. Expect more than 0
     */
    public function testGetCount()
    {
        $config = require 'src/app/config.php';

        $storage = new StorageFacade(1, $config['storages']['1']);
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

        $storage = new StorageFacade(1, $config['storages']['1']);
        $result = $storage->getList();

        $this->assertTrue(is_array($result));
    }

    /**
     * Delete file and check it is deleted
     */
    public function testDelete()
    {
        $config = require 'src/app/config.php';

        $storage = new StorageFacade(1, $config['storages']['1']);
        $storage->delete($this->files['name'][0]);
        $file = $storage->getFile($this->files['name'][0]);

        $this->assertNull($file);
    }
}