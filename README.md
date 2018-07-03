Torrent Scraper
===============

[![Build Status](https://travis-ci.org/xurumelous/torrent-scraper.svg?branch=master)](https://travis-ci.org/xurumelous/torrent-scraper)

## About
This library provides an abstraction to search for torrent files accross some torrent websites.

## Usage
```php
$scraperService = new \Yoramde\TorrentScraper\TorrentScrapperService(['ezTv', 'kickassTorrents']);
$results = $scraperService->search('elementaryos');

foreach ($results as $result) {
    $result->getName();
    $result->getSeeders();
    $result->getLeechers();
    $result->getTorrentUrl();
    $result->getMagnetUrl();
    $result->getTorrentAge();
}
```

## Available adapters
* ezTv
* kickassTorrents
* thePirateBay
* torrentZ2
* more?

> Adapters inspired from: https://github.com/JimmyLaurent/torrent-search-api
