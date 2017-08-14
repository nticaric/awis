<?php

namespace Nticaric\Awis;

use GuzzleHttp\Client;
use Carbon\Carbon;

class Awis {
    
    protected $accessKeyId;
    protected $secretAccessKey;
    protected $endPoint = "https://awis.amazonaws.com/";
    protected $dt;
    protected $signatureMethod = "HmacSHA256";
    protected $signatureVersion = 2;

    public function __construct($accessKeyId, $secretAccessKey)
    {
        $this->client = new Client;
        $this->accessKeyId     = $accessKeyId;
        $this->secretAccessKey = $secretAccessKey;
    }

    public function getUrlInfo($url, $responseGroup = "ContentData")
    {
        $this->dt = Carbon::now();

        $query = [
            'Action'           => 'UrlInfo',
            'AWSAccessKeyId'   => $this->accessKeyId,
            'SignatureMethod'  => $this->signatureMethod,
            'SignatureVersion' => $this->signatureVersion,
            'Timestamp'        => $this->dt->toISO8601String(),
            'Url'              => $url,
            'ResponseGroup'    => $responseGroup,
        ];

        $query['Signature'] = $this->generateSignature($query);

        $response = $this->client->get($this->endPoint, [
            'query' => $query
        ]);

        return $response;
    }

    public function getTrafficHistory($url, $range = 31, $start = "20070801")
    {
        $this->dt = Carbon::now();

        $query = [
            'Start'            => $start,
            'Action'           => 'TrafficHistory',
            'AWSAccessKeyId'   => $this->accessKeyId,
            'SignatureMethod'  => $this->signatureMethod,
            'SignatureVersion' => $this->signatureVersion,
            'Timestamp'        => $this->dt->toISO8601String(),
            'Url'              => $url,
            'ResponseGroup'    => 'History',
            'Range'            => $range,
        ];

        $query['Signature'] = $this->generateSignature($query);

        $response = $this->client->get($this->endPoint, [
            'query' => $query
        ]);

        return $response;
    }

    public function getCategoryBrowse($url, $responseGroup = "Categories", $path, $descriptions = 'TRUE')
    {
        $this->dt = Carbon::now();

        $query = [
            'Action'           => 'CategoryBrowse',
            'AWSAccessKeyId'   => $this->accessKeyId,
            'SignatureMethod'  => $this->signatureMethod,
            'SignatureVersion' => $this->signatureVersion,
            'Timestamp'        => $this->dt->toISO8601String(),
            'Url'              => $url,
            'ResponseGroup'    => $responseGroup,
            'Path'             => $path,
            'Descriptions'     => $descriptions,
        ];

        $query['Signature'] = $this->generateSignature($query);

        $response = $this->client->get($this->endPoint, [
            'query' => $query
        ]);

        return $response;
    }

    public function getCategoryListings($url, $path, $sortBy, $recursive, $start, $count, $descriptions = 'TRUE')
    {
        if( $count > 20) $count = 20;

        $this->dt = Carbon::now();

        $query = [
            'Action'           => 'CategoryListings',
            'AWSAccessKeyId'   => $this->accessKeyId,
            'SignatureMethod'  => $this->signatureMethod,
            'SignatureVersion' => $this->signatureVersion,
            'Timestamp'        => $this->dt->toISO8601String(),
            'Url'              => $url,
            'ResponseGroup'    => 'Listings',
            'Path'             => $path,
            'SortBy'           => $sortBy,
            'Recursive'        => $recursive,
            'Start'            => $start,
            'Count'            => $count,
            'Descriptions'     => $descriptions,
        ];

        $query['Signature'] = $this->generateSignature($query);

        $response = $this->client->get($this->endPoint, [
            'query' => $query
        ]);

        return $response;
    }

    public function getSitesLinkingIn($url, $count = 10, $start = 0)
    {
        $this->dt = Carbon::now();
        if( $count > 20) $count = 20;

        $query = [
            'Action'           => 'SitesLinkingIn',
            'AWSAccessKeyId'   => $this->accessKeyId,
            'SignatureMethod'  => $this->signatureMethod,
            'SignatureVersion' => $this->signatureVersion,
            'Timestamp'        => $this->dt->toISO8601String(),
            'Url'              => $url,
            'ResponseGroup'    => 'SitesLinkingIn',
            'Start'            => $start,
            'Count'            => $count,
        ];

        $query['Signature'] = $this->generateSignature($query);

        $response = $this->client->get($this->endPoint, [
            'query' => $query
        ]);

        return $response;
    }

    protected function generateSignature($query) {
        $sign = "GET\nawis.amazonaws.com\n/\n". $this->buildQueryParams($query);
        $sig = base64_encode(hash_hmac('sha256', $sign, $this->secretAccessKey, true));
        return $sig;
    }

    protected function buildQueryParams($query) {
        ksort($query);
        return http_build_query($query, null, '&', PHP_QUERY_RFC3986);
    }
}
