<?php namespace Nticaric\Awis;

use GuzzleHttp\Client;
use GuzzleHttp\Query;
use Carbon\Carbon;

class Awis {
    
    protected $accessKeyId;
    protected $secretAccessKey;
    protected $endPoint = "http://awis.amazonaws.com/";
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

        $request = $this->client->createRequest('GET', $this->endPoint);
        $query = $request->getQuery();
        $query->set('Action', 'UrlInfo');
        $query->set('AWSAccessKeyId', $this->accessKeyId);
        $query->set('SignatureMethod', $this->signatureMethod);
        $query->set('SignatureVersion', $this->signatureVersion);
        $query->set('Timestamp', $this->dt->toISO8601String());
        $query->set('Url', $url);
        $query->set('ResponseGroup', $responseGroup);
        $query->set('Signature', $this->generateSignature($query));

        return $this->client->send($request);
    }

    public function getTrafficHistory($url, $range = 31, $start = "20070801")
    {
        $this->dt = Carbon::now();

        $request = $this->client->createRequest('GET', $this->endPoint);
        $query = $request->getQuery();
        $query->set('Start', $start);
        $query->set('Action', 'TrafficHistory');
        $query->set('AWSAccessKeyId', $this->accessKeyId);
        $query->set('SignatureMethod', $this->signatureMethod);
        $query->set('SignatureVersion', $this->signatureVersion);
        $query->set('Timestamp', $this->dt->toISO8601String());
        $query->set('Url', $url);
        $query->set('ResponseGroup', 'History');
        $query->set('Range', $range);
        $query->set('Signature', $this->generateSignature($query));

        return $this->client->send($request);
    }

    public function getCategoryBrowse($url, $responseGroup = "Categories", $path, $descriptions = 'TRUE')
    {
        $this->dt = Carbon::now();

        $request = $this->client->createRequest('GET', $this->endPoint);
        $query = $request->getQuery();
        $query->set('Action', 'CategoryBrowse');
        $query->set('AWSAccessKeyId', $this->accessKeyId);
        $query->set('SignatureMethod', $this->signatureMethod);
        $query->set('SignatureVersion', $this->signatureVersion);
        $query->set('Timestamp', $this->dt->toISO8601String());
        $query->set('Url', $url);
        $query->set('ResponseGroup', $responseGroup);
        $query->set('Path', $path);
        $query->set('Descriptions', $descriptions);
        $query->set('Signature', $this->generateSignature($query));

        return $this->client->send($request);
    }

    public function getCategoryListings($url, $path, $sortBy, $recursive, $start, $count, $descriptions = 'TRUE')
    {
        if( $count > 20) $count = 20;

        $this->dt = Carbon::now();

        $request = $this->client->createRequest('GET', $this->endPoint);
        $query = $request->getQuery();
        $query->set('Action', 'CategoryListings');
        $query->set('AWSAccessKeyId', $this->accessKeyId);
        $query->set('SignatureMethod', $this->signatureMethod);
        $query->set('SignatureVersion', $this->signatureVersion);
        $query->set('Timestamp', $this->dt->toISO8601String());
        $query->set('Url', $url);
        $query->set('ResponseGroup', 'Listings');
        $query->set('Path', $path);
        $query->set('SortBy', $sortBy);
        $query->set('Recursive', $recursive);
        $query->set('Start', $start);
        $query->set('Count', $count);
        $query->set('Descriptions', $descriptions);
        $query->set('Signature', $this->generateSignature($query));

        return $this->client->send($request);
    }

    public function getSitesLinkingIn($url, $count = 10, $start = 0)
    {
        $this->dt = Carbon::now();
        if( $count > 20) $count = 20;

        $request = $this->client->createRequest('GET', $this->endPoint);
        $query = $request->getQuery();
        $query->set('Action', 'SitesLinkingIn');
        $query->set('AWSAccessKeyId', $this->accessKeyId);
        $query->set('SignatureMethod', $this->signatureMethod);
        $query->set('SignatureVersion', $this->signatureVersion);
        $query->set('Timestamp', $this->dt->toISO8601String());
        $query->set('Url', $url);
        $query->set('ResponseGroup', 'SitesLinkingIn');
        $query->set('Start', $start);
        $query->set('Count', $count);
        $query->set('Signature', $this->generateSignature($query));

        return $this->client->send($request);
    }

    protected function generateSignature($query) {
        $sign = "GET\nawis.amazonaws.com\n/\n". $this->buildQueryParams($query);
        $sig = base64_encode(hash_hmac('sha256', $sign, $this->secretAccessKey, true));
        return $sig;
    }

    protected function buildQueryParams($query) {
        $query->remove('Signature');
        $params = $query->toArray();
        ksort($params);
        return new Query($params);
    }
}