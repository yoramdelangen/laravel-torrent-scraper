<?php

namespace Xurumelous\TorrentScraper\Entity;

class SearchResult
{
    protected $adapter = '';
    /**
     * @var string
     */
    protected $name;

    /**
     * @var int
     */
    protected $seeders;

    /**
     * @var int
     */
    protected $leechers;

    /**
     * @var string
     */
    protected $torrentUrl;

    /**
     * @var string
     */
    protected $magnetUrl;

    /**
     * @var string
     */
    protected $age;

    /**
     * @var string
     */
    protected $uploader;

    public function __construct($adapter) {
        $this->adapter = $adapter;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param int $seeders
     */
    public function setSeeders($seeders)
    {
        $this->seeders = $seeders;
    }

    /**
     * @return int
     */
    public function getSeeders()
    {
        return $this->seeders;
    }

    /**
     * @param int $leechers
     */
    public function setLeechers($leechers)
    {
        $this->leechers = $leechers;
    }

    /**
     * @return int
     */
    public function getLeechers()
    {
        return $this->leechers;
    }

    /**
     * @param string $torrentUrl
     */
    public function setTorrentUrl($torrentUrl)
    {
        $this->torrentUrl = $torrentUrl;
    }

    /**
     * @return string
     */
    public function getTorrentUrl()
    {
        return $this->torrentUrl;
    }

    /**
     * @param string $magnetUrl
     */
    public function setMagnetUrl($magnetUrl)
    {
        $this->magnetUrl = $magnetUrl;
    }

    /**
     * @return string
     */
    public function getMagnetUrl()
    {
        return $this->magnetUrl;
    }

    /**
     * @param string $uploader
     */
    public function setUploader($uploader)
    {
        $this->uploader = $uploader;
    }

    /**
     * @return string
     */
    public function getUploader()
    {
        return $this->uploader;
    }

    /**
     * @return string
     */
    public function getAdapter()
    {
        return $this->adapter;
    }

    public function setTorrentAge($age)
    {
        $this->age = $age;
    }

    public function getTorrentAge() {
        return $this->age;
    }
}
