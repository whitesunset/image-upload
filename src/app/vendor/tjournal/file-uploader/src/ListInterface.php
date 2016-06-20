<?php
namespace TJ\Uploader;

/**
 * Interface ListInterface
 * @package TJ\Uploader
 */
interface ListInterface
{
    /**
     * Choose storage
     * @param integer $storage 1 - Amazon, 2 — Selectel
     * @param array $storageAuthParameters Storage auth parameters
     */
    public function __construct($storage, $storageAuthParameters);


    /**
     * Get storage count
     * @return mixed
     */
    public function getCount();

    public function getList($start, $count);
}