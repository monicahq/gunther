<?php

namespace Gunther\Services;

use ElKuKu\Crowdin\Crowdin;
use ElKuKu\Crowdin\Languagefile;
use GuzzleHttp\Client;
use Illuminate\Config\Repository as Config;

class Publisher
{
    /**
     * @var Config
     */
    protected $config;

    /**
     * Crowdin object instance.
     *
     * @var Crowdin
     */
    protected $crowdin;

    /**
     * Create a new Publisher.
     *
     * @param \Illuminate\Config\Repository $config
     * @param Client $client
     */
    public function __construct(Config $config, Client $client = null)
    {
        $this->config = $config;
        if (empty($this->config->get('gunther.project')) || empty($this->config->get('gunther.apikey'))) {
            throw new \InvalidArgumentException('Please fill project and apikey in gunther.php');
        }
        $this->crowdin = new Crowdin($this->config->get('gunther.project'), $this->config->get('gunther.apikey'), 'https://api.crowdin.com/api/', $client);
    }

    /**
     * Tell if this language is currently supported in crowdin project.
     *
     * @param string $language
     *
     * @return bool
     */
    public function languageSupported($language): bool
    {
        try {
            $status = $this->crowdin->language->getStatus($language);

            return $status->getStatusCode() == 200;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Upload files to crowdin.
     *
     * @param string              $language
     * @param array<Languagefile> $files
     */
    public function upload($language, $files): void
    {
        foreach ($files as $file) {
            $this->crowdin->translation->upload($file, $language);
        }
    }
}
