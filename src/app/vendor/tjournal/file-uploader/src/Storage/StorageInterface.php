<?php
namespace TJ\Uploader\Storage;

/**
 * Interface
 * PUT File
 * GET Files
 * GET Files count
 * DELETE File
 *
 * Interface StorageInterface
 * @package TJ\Uploader\Storage
 */
interface StorageInterface
{
    public function __construct(array $storage);

    public function getCount();

    public function getFile($remoteFileName);

    public function getList($start, $count);

    public function upload($localFileName, $remoteFileName);

    public function delete($remoteFileName);
}