<?php namespace Cvaize\TranslationManager\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Cvaize\TranslationManager\Events\TranslationsPublished;
use Cvaize\TranslationManager\Manager;

class ExportCommand extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'translations:export';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export translations to PHP files';

    /** @var \Cvaize\TranslationManager\Manager */
    protected $manager;

    public function __construct(Manager $manager)
    {
        $this->manager = $manager;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire() {
        $this->handle();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $group = $this->argument('group');

        if ($group && $group != '*') {
            $this->manager->exportTranslations($group);
        } else {
            $this->manager->exportAllTranslations();
        }

        $errors = $this->manager->errors();
        event(new TranslationsPublished($group, $errors));

        $this->info("Done writing language files for " . (($group == '*') ? 'ALL groups' : $group . " group"));
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return array(
            array('group', InputArgument::REQUIRED, 'The group to export ("*" for all).'),
        );
    }
}
