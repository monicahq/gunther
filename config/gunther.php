<?php

return [
    /**
     * Available languages of the application.
     * List of all languages, separated by comas.
     */
    'languages' => env('LANGUAGES', 'en'),

    /**
     * Crowdin api key.
     * See https://crowdin.com/project/{project_name}/settings#api
     */
    'apikey' => env('CROWDIN_APIKEY'),

    /**
     * Crowdin project.
     * See https://crowdin.com/project/{project_name}/settings#api
     */
    'project' => env('CROWIND_PROJECT'),

    /**
     * Resulting file format.
     * @see https://support.crowdin.com/files-management/#file-export-settings
     */
    'resulting_file' => '/resources/lang/%two_letters_code%/%original_file_name%',

];
