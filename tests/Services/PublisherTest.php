<?php

namespace Tests\Services;

use ElKuKu\Crowdin\Languagefile;
use Gunther\Services\Publisher;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use Illuminate\Config\Repository as Config;
use Orchestra\Testbench\TestCase;

class PublisherTest extends TestCase
{
    private function getConfig(): Config
    {
        return new Config([
            'gunther.project' => 'project',
            'gunther.apikey' => 'apikey',
        ]);
    }

    public function test_create_publisher()
    {
        $publisher = new Publisher($this->getConfig());

        $this->assertThat(
            $publisher,
            $this->isInstanceOf('Gunther\Services\Publisher')
        );
    }

    public function test_language_supported()
    {
        // Create a mock and queue two responses.
        $mock = new MockHandler([
            new Response(200, ['Content-Length' => 0]),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);

        $publisher = new Publisher($this->getConfig(), $client);

        $this->assertEquals(true, $publisher->languageSupported('fr'));
    }

    public function test_language_not_supported()
    {
        // Create a mock and queue two responses.
        $mock = new MockHandler([
            new Response(404, ['Content-Length' => 0]),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);

        $publisher = new Publisher($this->getConfig(), $client);

        $this->assertEquals(false, $publisher->languageSupported('xx'));
    }

    public function test_upload_file()
    {
        $container = [];
        $history = Middleware::history($container);

        // Create a mock and queue two responses.
        $mock = new MockHandler([
            new Response(200, ['Content-Length' => 0], '<success></success>'),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $handlerStack->push($history);

        $client = new Client(['handler' => $handlerStack]);

        $publisher = new Publisher($this->getConfig(), $client);

        $languagefile = new Languagefile(__DIR__.'/../stubs/file.txt', 'path');

        $publisher->upload('fr', [$languagefile]);

        $this->assertCount(1, $container);

        $request = $container[0]['request'];
        $this->assertEquals('project/project/upload-translation', $request->getUri()->getPath());
    }
}
