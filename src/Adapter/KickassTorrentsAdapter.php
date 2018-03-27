<?php

namespace Xurumelous\TorrentScraper\Adapter;

use Xurumelous\TorrentScraper\AdapterInterface;
use Xurumelous\TorrentScraper\HttpClientAware;
use Xurumelous\TorrentScraper\Entity\SearchResult;
use Symfony\Component\DomCrawler\Crawler;

class KickassTorrentsAdapter implements AdapterInterface
{
    use HttpClientAware;

    public function havingCloudflareBypass(): bool
    {
        return false;
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
        try {
            $response = $this->httpClient->get('http://kickasstorrents.to/usearch/' . urlencode($query) . '/');
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            return [];
        }

        $crawler = new Crawler((string) $response->getBody());
        $items = $crawler->filter('#mainSearchTable tr');
        $results = [];

        $i = 0;

        foreach ($items as $item) {
            // Ignores advertisement and header
            if ($i < 2) {
                $i ++;

                continue;
            }

            $itemCrawler = new Crawler($item);

            $name = $itemCrawler->filter('.cellMainLink')->text();

            if (!stristr($name, $query)) {
                continue;
            }

            $data = json_decode(str_replace("'", '"', $itemCrawler->filter('div[data-sc-params]')->attr('data-sc-params')));

            $result = new SearchResult('KickassTorrents');
            $result->setName($name);
            $result->setSeeders((int) $itemCrawler->filter('td:nth-child(5)')->text());
            $result->setLeechers((int) $itemCrawler->filter('td:nth-child(6)')->text());
            $result->setMagnetUrl($data->magnet);
            $result->setTorrentAge($itemCrawler->filter('td:nth-child(4)')->text());

            $results[] = $result;
        }

        return $results;
    }
}
