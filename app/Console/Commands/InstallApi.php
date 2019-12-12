<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class InstallApi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'api:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Initialize the API so its ready to use.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $commandsToRun = 3;
        $bar = $this->output->createProgressBar($commandsToRun);
        $bar->setFormat('%current%/%max% [%bar%] %percent:3s%%');
        if (!file_exists( ".env" ) ) {
            $this->output->write("⚠️ First, you need to create your .env file with your enviroment configuration.⚠️\n\n");
            $bar->finish();
        } else {
            $this->output->write("[1. Composer dependencies 💽]\n");
            $bar->start();
            $this->output->write("\n");
            $cmd_composer = 'composer install';
            shell_exec($cmd_composer);
            $bar->advance();
            $this->output->write("\n\n");

            $this->output->write("[2. Run database migrations 📝]\n");
            $this->call('migrate');
            $bar->advance();
            $this->output->write("\n\n");

            $this->output->write("[3. Laravel Passport install 🔐]\n");
            $this->call('passport:install');
            $bar->advance();
            $this->output->write("\n\n");

            $this->output->write("Everything is done ✅. You can start using the API.\n");

            $bar->finish();
        }

    }
}
