<?php

namespace Gunther\Services;

use ElKuKu\Crowdin\Languagefile;
use Symfony\Component\Finder\Finder;
use Illuminate\Config\Repository as Config;

class LangsService
{
    /**
     * @var Config
     */
    protected $config;

    /**
     * Create a new LangsService.
     *
     * @param \Illuminate\Config\Repository $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * Get languages to translate.
     *
     * @return array[string]
     */
    public function getLanguages(): array
    {
        $languages = $this->config->get('gunther.languages');

        if (! is_array($languages)) {
            $languages = explode(',', $languages);
        }

        return $languages;
    }

    /**
     * Get translations for the language.
     *
     * @param string $language
     *
     * @return array[Languagefile]
     */
    public function getTranslations($language): array
    {
        $sourcePath = base_path('vendor/caouecs/laravel-lang/src/'.$language);

        $iterator = Finder::create()
                ->files()
                ->in($sourcePath)
                ->depth(0);

        $langs = [];

        foreach ($iterator as $file) {
            $languageFile = $this->formatFile($language, $file->getRelativePathname(), $file->getPathname());
            array_push($langs, $languageFile);
        }

        return $langs;
    }

    /**
     * Get translations for the name of supported locales for the language.
     *
     * @param string        $language
     * @param array[string] $locale
     *
     * @return array[string]
     */
    public function getLocaleTranslations($language, $locales): array
    {
        $sourcePath = base_path('vendor/umpirsky/locale-list/data/'.$language);

        $sourceLocales = app('files')->getRequire($sourcePath.'/locales.php');

        $langs = [];
        foreach ($locales as $locale) {
            $langs['locale_'.$locale] = $sourceLocales[$locale];
        }

        return $langs;
    }

    /**
     * Format filename and path for specific language.
     *
     * @param string $language Language of the file
     * @param string $fileName File name
     * @param string $filePath File path
     *
     * @return Languagefile
     */
    public function formatFile($language, $fileName, $filePath) : Languagefile
    {
        $resulting = $this->config->get('gunther.resulting_file');
        $resulting = str_replace('%two_letters_code%', $language, $resulting);
        $resulting = str_replace('%original_file_name%', $fileName, $resulting);

        return new Languagefile($filePath, $resulting);
    }
}
