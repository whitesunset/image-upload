<?php
namespace TJ\Uploader\Storage\AWS;

use Aws\S3\S3Client;
use TJ\Uploader\Storage\Base;

/**
 * Class AWS
 * TJ Storage wrapper for official AWS SDK
 * https://github.com/aws/aws-sdk-php
 *
 * @package TJ\Uploader\Storage
 */
class AWS extends Base
{
    private $storageID = 1;
    private $storage;
    private $container;
    private $count;
    private $list;
    private $config = [
        'region' => 'us-west-2',
        'container' => '',
        'credentials' => []
    ];

    /**
     * AWS constructor.
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $this->config = array_merge($this->config, $config);

        $this->storage = new S3Client($this->config);
    }

    /**
     * Upload file to storage using PUT Object operator
     * http://docs.aws.amazon.com/AmazonS3/latest/API/RESTObjectPUT.html
     *
     * @param $localFileName
     * @param $remoteFileName
     * @return \Aws\Result
     */
    public function upload($localFileName, $remoteFileName)
    {
        try {
            return $this->storage->putObject([
                'Bucket' => $this->config['container'],
                'Key' => $remoteFileName,
                'Body' => fopen($localFileName, 'r'),
                'ACL' => 'public-read',
            ]);
        } catch (Aws\Exception\S3Exception $e) {
            echo "There was an error uploading the file to AWS.\n";
        }
    }

    /**
     * Delete file using Stream Wrapper
     * http://docs.aws.amazon.com/aws-sdk-php/v3/guide/service/s3-stream-wrapper.html?highlight=registerstreamwrapper
     *
     * @param $remoteFileName
     */
    public function delete($remoteFileName)
    {
        try {
            $this->storage->registerStreamWrapper();

            $dir = 's3://' . $this->config['container'] . '/';
            unlink($dir . $remoteFileName);
        } catch (Aws\Exception\S3Exception $e) {
            echo "There was an error deleting the file from AWS.\n";
        }
    }

    /**
     * GET file by name
     * http://docs.aws.amazon.com/aws-sdk-php/v2/api/class-Aws.S3.S3Client.html#_getObject
     *
     * @param string $remoteFileName
     * @return array
     */
    public function getFile($remoteFileName)
    {
        try {
            return $this->storage->getObject(array(
                'Bucket' => $this->config['container'],
                'Key' => $remoteFileName
            ));
        } catch (\Exception $e) {
            echo "There was an error with retrieving the file from AWS.\n";
        }
    }

    /**
     * GET container files count
     * @return int
     */
    public function getCount()
    {
        $this->list = $this->storage->ListObjects(['Bucket' => $this->config['container']])['Contents'];
        $this->count = count($this->list);

        return $this->count;
    }

    /**
     * GET container listing
     * http://docs.aws.amazon.com/AmazonS3/latest/dev/ListingObjectKeysUsingPHP.html
     *
     * @param $start
     * @param $count
     * @return array
     */
    public function getList($start, $count)
    {
        $this->list = $this->storage->ListObjects(['Bucket' => $this->config['container']])['Contents'];
        $result = [];

        if (array_filter($this->list)) {
            for ($i = $start; $i < $start + $count; $i++) {
                if (!isset($this->list[$i])) break;

                $result[] = [
                    'id' => md5($this->list[$i]['Key'] . $this->storageID),
                    'storage' => $this->storageID,
                    'url' => 'http://s3.' . $this->config['region'] . '.amazonaws.com/' . $this->config['container'] . '/' . $this->list[$i]['Key'],
                    'name' => $this->list[$i]['Key']
                ];
            }
        }

        return $result;
    }
}