<?php

namespace Yoramdelangen\TorrentScraper\Adapter;

use Tuna\CloudflareMiddleware;
use GuzzleHttp\Exception\ClientException;
use Symfony\Component\DomCrawler\Crawler;
use Yoramdelangen\TorrentScraper\HttpClientAware;
use Yoramdelangen\TorrentScraper\AdapterInterface;
use Yoramdelangen\TorrentScraper\Entity\SearchResult;

class TorrentZ2Adapter implements AdapterInterface
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

        $query = str_replace(' ', '+', $query);

        try {
            $response = $this->httpClient->get('https://torrentz2.eu/?f='.$query);
        } catch (ClientException $e) {
            return [];
        }

        $crawler = new Crawler((string) $response->getBody());
        $items = $crawler->filter('.results dl');
        $results = [];
        $first = true;

        foreach ($items as $item) {
            $result = new SearchResult('TorrentT2');
            $itemCrawler = new Crawler($item);

            if ($itemCrawler->filter('.dmca')->count() > 0) {
                continue;
            }

            $title = trim($itemCrawler->filter('dt a')->text());

            $torrentKey = ltrim($itemCrawler->filter('dt > a')->attr('href'), '/');
            $magnet = 'magnet:?xt=urn:btih:'.$torrentKey.'&dn='.urlencode($title);

            $result->setName($title);
            $result->setSeeders((int) $itemCrawler->filter('dd span')->eq(3)->text());
            $result->setLeechers((int) $itemCrawler->filter('dd span')->eq(4)->text());
            $result->setMagnetUrl($magnet);
            $result->setUploader(null);
            $result->setTorrentAge($itemCrawler->filter('dd span')->eq(1)->text());

            $results[] = $result;
        }

        return $results;
    }
}
