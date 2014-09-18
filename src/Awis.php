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
        $this->accessKeyId     = $accessKeyId;
        $this->secretAccessKey = $secretAccessKey;
    }

    public function getUrlInfo($url, $responseGroup = "ContentData")
    {
        $this->dt = Carbon::now();

        $client = new Client();
        $request = $client->createRequest('GET', $this->endPoint);
        $query = $request->getQuery();
        $query->set('Action', 'UrlInfo');
        $query->set('AWSAccessKeyId', $this->accessKeyId);
        $query->set('SignatureMethod', $this->signatureMethod);
        $query->set('SignatureVersion', $this->signatureVersion);
        $query->set('Timestamp', $this->dt->toISO8601String());
        $query->set('Url', $url);
        $query->set('ResponseGroup', $responseGroup);
        $query->set('Signature', $this->generateSignature($query));

        return $client->send($request);
    }

    public function getTrafficHistory($url, $range = 31, $start = "20070801")
    {
        $this->dt = Carbon::now();

        $client = new Client();
        $request = $client->createRequest('GET', $this->endPoint);
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

        return $client->send($request);
    }

    public function getCategoryBrowse($url, $responseGroup = "Categories", $path, $descriptions = 'TRUE')
    {
        $this->dt = Carbon::now();

        $client = new Client();
        $request = $client->createRequest('GET', $this->endPoint);
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

        return $client->send($request);
    }

    public function getCategoryListings()
    {

    }

    public function getSitesLinkingIn()
    {

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