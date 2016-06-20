<?php
namespace TJ\Uploader;

interface DestroyerInterface
{
    /**
     * Choose storage
     * @param integer $storage 1 - Amazon, 2 — Selectel
     * @param array $storageAuthParameters Storage auth parameters
     */
    public function __construct($storage, $storageAuthParameters);

    /**
     * Delete remote file by name
     * @param  string $remoteFileName remote file name to delete
     * @return UploaderInterface
     */
    public function delete($remoteFileName);
}
