<?php

namespace Tests\Services;

use PHPUnit\Framework\TestCase;
use Gunther\Services\LangsService;
use Illuminate\Config\Repository as Config;

class LangsServiceTest extends TestCase
{
    public function test_create_service()
    {
        $service = new LangsService(new Config());

        $this->assertThat(
			$service,
			$this->isInstanceOf('Gunther\Services\LangsService')
		);
    }
}
