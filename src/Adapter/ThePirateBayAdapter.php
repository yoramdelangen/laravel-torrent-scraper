<?php

namespace Xurumelous\TorrentScraper\Adapter;

use Tuna\CloudflareMiddleware;
use GuzzleHttp\Exception\ClientException;
use Symfony\Component\DomCrawler\Crawler;
use Xurumelous\TorrentScraper\HttpClientAware;
use Xurumelous\TorrentScraper\AdapterInterface;
use Xurumelous\TorrentScraper\Entity\SearchResult;

class ThePirateBayAdapter implements AdapterInterface
{
    use HttpClientAware;

    public function havingCloudflareBypass(): bool
    {
        return true;
    }

    /**
     * @param array $options
     */
    public function __construct(array $options = [])
    {

    }

    /**
     * @param string $query
     * @return SearchResult[]
     */
    public function search($query)
    {
        $this->httpClient->getConfig('handler')->push(CloudflareMiddleware::create());

        try {
            // $response = $this->httpClient->get('https://thepiratebay3.org/search/' . urlencode($query) . '/0/7/0');
            $response = $this->httpClient->get('https://thepiratebay3.org/?q=' . urlencode($query) . '&category=0&page=0&orderby=99');
        } catch (ClientException $e) {
            return [];
        }

        $crawler = new Crawler((string) $response->getBody());
        $items = $crawler->filter('#searchResult tr');
        $results = [];
        $first = true;

        foreach ($items as $item) {
            // Ignore the first row, the header
            if ($first) {
                $first = false;
                continue;
            }


            $result = new SearchResult('ThePirateBay');
            $itemCrawler = new Crawler($item);
            $result->setName(trim($itemCrawler->filter('.detName')->text()));
            $result->setSeeders((int) $itemCrawler->filter('td')->eq(2)->text());
            $result->setLeechers((int) $itemCrawler->filter('td')->eq(3)->text());
            $result->setMagnetUrl($itemCrawler->filterXpath('//tr/td/a')->attr('href'));

            $result->setTorrentAge($this->getTorrentAge($itemCrawler));

            $uploader = null;
            try {
               $uploader = $itemCrawler->filter('.detDesc a')->text();
            } catch (\InvalidArgumentException $e) {
                // Handle the current node list is empty..
            }
            $result->setUploader($uploader);

            $results[] = $result;
        }

        return $results;
    }

    protected function getTorrentAge(Crawler $crawler): string
    {
        $torrentAge = trim($crawler->filter('.detDesc')->text());

        preg_match('/([0-9]{2}-[0-9]{2}.*[0-9]{2}:[0-9]{2})/', $torrentAge, $ageMatch);

        return end($ageMatch);
    }
}
