<?php

namespace Krdinesh\Greenhouse\GreenhousePhp\Test\Services;

use Krdinesh\Greenhouse\GreenhousePhp\Services\IngestionService;
use Krdinesh\Greenhouse\GreenhousePhp\Clients\Exceptions\GreenhouseResponseException;

class IngestionApiServiceTest extends \PHPUnit_Framework_TestCase {

    public function setUp(){
        $this->ingestionService = new IngestionService('apiKey');
        $this->errorService = new IngestionService('exception_api_key');
        $this->baseUrl =  IngestionService::ingestionBaseUrl();
    }

    public function testGetJobsException(){
        $this->expectException('Krdinesh\Greenhouse\GreenhousePhp\Clients\Exceptions\GreenhouseResponseException');
        $this->errorService->getJobs();
    }
}