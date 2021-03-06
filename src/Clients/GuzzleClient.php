<?php

namespace Krdinesh\Greenhouse\GreenhousePhp\Clients;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Krdinesh\Greenhouse\GreenhousePhp\Clients\ServiceClientRespository;
use Krdinesh\Greenhouse\GreenhousePhp\Clients\Exceptions\GreenhouseClientException;
use Krdinesh\Greenhouse\GreenhousePhp\Clients\Exceptions\GreenhouseResponseException;

/**
 * GuzzleClient class implements the Service client Repository
 */
class GuzzleClient implements ClientInterface
{
    private $client;

    /**
     * Constructor Function function
     *
     * @param Array  $options
     * @return void
     */
    public function __construct($options)
    {
        $this->client = new client($options);
    }
    /**
     * Get the response from the URL and return the JSON response from the Greenhouse server.
     *
     * @param string $url
     * @return string
     * @throws Greenhouse Response Exception
     */
    public function get($url="")
    {
        try {
            $guzzleResponse = $this->client->request('GET', $url);
        } catch (RequestException $e) {
            throw new GreenhouseResponseException($e->getMessage(), 0, $e);
        }
        /**
         * Just return the response cast as a string.  The rest of the universe need
         * not be aware of Guzzle's details.
         */
        return (string)$guzzleResponse->getBody();
    }

    /**
     * Post parameters as formatted by $this->formatPostParameters and send it to the destination URL.
     * @param Array $postParams
     * @param Array $headers
     * @param string $url
     * @throws  GreenhouseServiceResponseException  for non-200 responses
     */
    public function post(array $postParams, array $headers, $url=null)
    {
        try {
            $guzzleResponse=$this->client
          ->post($url, [ 'multipart'=>$postParams,'headers'=>$headers]);
        } catch (RequestException $e) {
            throw new GreenhouseResponseException($e->getMessage(), 0, $e);
        }
        return (string) $guzzleResponse->getBody();
    }

    /**
      * Transform the post parameters that client understands.
      * @params  Array   $postParamters
      * @return  mixed
      */
    public function formatPostParameters(array $postParameters)
    {
        $guzzleParams=[];
        foreach ($postParameters as $key => $value) {
            if ($value instanceof \CURLFile) {
                $guzzleParams[] = [
                'name' => $key,
                'contents' => fopen($value->getFilename(), 'r'),
                'filename' => $value->getPostFilename()
             ];
            } elseif (is_array($value)) {
                foreach ($value as $k => $v) {
                    $guzzleParams[]=['name' => $key .'[]','contents' => $v];
                }
            } else {
                $guzzleParams[] = ['name' => $key, 'contents' => $value];
            }
        }
        return $guzzleParams;
    }

    /**
     * Send Request
     *
     * @param Array $postParameters
     * @param [type] $url
     * @return void
     */
    public function send($method, $url, array $options)
    {
        try {
            $guzzleResponse= $this->client->request($method, $url, $options);
        } catch (RequestException $e) {
            throw new GreenhouseResponseException($e->getMessage(), 0, $e);
        }
        /**
         * Just return the response cast as a string.  The rest of the universe need
         * not be aware of Guzzle's details.
         */
        return (string)$guzzleResponse->getBody();
    }

    /**
     * Return client function
     *
     * @return $client
     */
    public function getClient()
    {
        return $this->client;
    }
}
