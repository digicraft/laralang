<?php

namespace Digicraft\Laralang\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Digicraft\Laralang\Manager;
use Illuminate\Support\Str;

class FindCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laralang:find {keyword} {--package : Vendor Package name to search within.}';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $description = 'Find key with values matching the keyword.';

    /**
     * The Languages manager instance.
     *
     * @var \Digicraft\LangMan\Manager
     */
    private $manager;

    /**
     * Array of files grouped by filename.
     *
     * @var array
     */
    protected $files;

    /**
     * ListCommand constructor.
     *
     * @param \Digicraft\LangMan\Manager $manager
     * @return void
     */
    public function __construct(Manager $manager)
    {
        parent::__construct();

        $this->manager = $manager;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if ($package = $this->option('package')) {
            $this->manager->setPathToVendorPackage($package);
        }

        $this->files = $this->manager->files();

        if (empty($this->files)) {
            $this->warn('No language files were found!');
        }

        $languages = $this->manager->languages();

        $this->table(
            array_merge(['key'], $languages),
            $this->tableRows()
        );
    }

    /**
     * The output of the table rows.
     *
     * @return array
     */
    private function tableRows()
    {
        $allLanguages = $this->manager->languages();

        $filesContent = [];

        $output = [];

        foreach ($this->files as $fileName => $fileLanguages) {
            foreach ($fileLanguages as $languageKey => $filePath) {
                $lines = $filesContent[$fileName][$languageKey] = Arr::dot($this->manager->getFileContent($filePath));

                foreach ($lines as $key => $line) {
                    if (! is_array($line) && stripos($line, $this->argument('keyword')) !== false) {
                        $output[$fileName.'.'.$key][$languageKey] = "<bg=yellow;fg=black>{$line}</>";
                    }
                }
            }
        }

        // Now that we collected all values that matches the keyword argument
        // in a close match, we collect the values for the rest of the
        // languages for the found keys to complete the table view.
        foreach ($output as $fullKey => $values) {
            list($fileName, $key) = explode('.', $fullKey, 2);

            $original = [];

            foreach ($allLanguages as $languageKey) {
                if(isset($values[$languageKey])){
                    $original[$languageKey] = $values[$languageKey];
                }else{
                    $original[$languageKey] =
                        (isset($filesContent[$fileName][$languageKey][$key])) ? $filesContent[$fileName][$languageKey][$key] : ''
                }
            }

            // Sort the language values based on language name
            ksort($original);

            $output[$fullKey] = array_merge(['key' => "<fg=yellow>$fullKey</>"], $original);
        }

        return array_values($output);
    }
}
