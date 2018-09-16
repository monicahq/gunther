<?php

namespace Gunther\Commands;

use Gunther\Facades\Publisher;
use Gunther\Services\LangsService;
use Illuminate\Console\Command;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateTranslations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lang:update {--dryrun}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update translations for Laravel framework';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $langsService = new LangsService(app()->make('config'));
        $languages = $langsService->getLanguages();

        foreach ($languages as $language) {
            if ($language == 'en') {
                // Source language of Laravel, so skip it.
                continue;
            }

            if (Publisher::languageSupported($language)) {
                $this->info('Language "'.$language.'" supported', OutputInterface::VERBOSITY_VERBOSE);
                $translations = $langsService->getTranslations($language);
                $this->handleTranslations($language, $translations);
            } else {
                $this->info('Language "'.$language.'" is not yet supported', OutputInterface::VERBOSITY_VERBOSE);
            }
        }
    }

    private function handleTranslations(string $language, array $translations)
    {
        foreach ($translations as $translation) {
            $this->info('Update locale file: '.$translation->getLocalPath().', crowdin file: '.$translation->getCrowdinPath(), OutputInterface::VERBOSITY_VERBOSE);
        }

        if (!$this->option('dryrun)')) {
            Publisher::upload($language, $translations);
        }
    }
}
