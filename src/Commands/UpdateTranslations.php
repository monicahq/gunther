<?php

namespace Gunther\Commands;

use Gunther\Facades\Publisher;
use Illuminate\Console\Command;
use Gunther\Services\LangsService;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateTranslations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lang:update {--dryrun : Simulate the execution but not write anything.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update translations for Laravel framework';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $langsService = new LangsService(app()->make('config'));
        $languages = $langsService->getLanguages();

        foreach ($languages as $language) {
            if ($language == config('gunther.source_language')) {
                // Source language of Laravel, so skip it.
                continue;
            }

            if (Publisher::languageSupported($language)) {
                $this->info('Language "'.$language.'" supported', OutputInterface::VERBOSITY_VERBOSE);
                $translations = $langsService->getTranslations($language);
                if (! is_null($translations)) {
                    $this->handleTranslations($language, $translations);
                }
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

        if (! $this->option('dryrun')) {
            Publisher::upload($language, $translations);
        }
    }
}
