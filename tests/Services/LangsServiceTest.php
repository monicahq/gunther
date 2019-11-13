<?php

namespace Tests\Services;

use Gunther\Services\LangsService;
use Illuminate\Config\Repository as Config;
use Illuminate\Filesystem\Filesystem;
use Orchestra\Testbench\TestCase;

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

    public function test_get_languages_default()
    {
        $config = (new Filesystem())->getRequire(__DIR__.'/../../config/gunther.php');

        $service = new LangsService(new Config(['gunther' => $config]));

        $languages = $service->getLanguages();

        $this->assertArraySubset(['en'], $languages);
    }

    public function test_get_languages_list()
    {
        $service = new LangsService(new Config(['gunther' => ['languages' => 'en,fr,jp']]));

        $languages = $service->getLanguages();

        $this->assertArraySubset(['en', 'fr', 'jp'], $languages);
    }

    public function test_get_languages_array()
    {
        $service = new LangsService(new Config(['gunther' => ['languages' => ['en', 'fr', 'jp']]]));

        $languages = $service->getLanguages();

        $this->assertArraySubset(['en', 'fr', 'jp'], $languages);
    }

    public function test_get_translations()
    {
        app()->setBasePath(__DIR__.'/../../');
        $service = new LangsService(new Config());

        $translations = $service->getTranslations('fr');

        $this->assertNotNull($translations);
        $this->assertCount(4, $translations);
    }

    public function test_get_translations_unknow()
    {
        app()->setBasePath(__DIR__.'/../../');
        $service = new LangsService(new Config());

        $translations = $service->getTranslations('xx');

        $this->assertNotNull($translations);
        $this->assertCount(0, $translations);
    }

    public function test_get_locale_translations()
    {
        app()->setBasePath(__DIR__.'/../../');
        $service = new LangsService(new Config());

        $translations = $service->getLocaleTranslations('en', ['fr', 'it', 'he']);

        $this->assertNotNull($translations);
        $this->assertCount(3, $translations);
    }

    public function test_get_locale_translations_unknow()
    {
        app()->setBasePath(__DIR__.'/../../');
        $service = new LangsService(new Config());

        $translations = $service->getLocaleTranslations('en', ['xx', 'jp']);

        $this->assertNotNull($translations);
        $this->assertCount(0, $translations);
    }
}
