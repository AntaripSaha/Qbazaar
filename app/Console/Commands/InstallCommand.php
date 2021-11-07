<?php

namespace App\Console\Commands;

use App\Utilities\Installer;
use Hash;
use Illuminate\Console\Command;

class InstallCommand extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'App Installation';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle() {
        if (env('APP_INSTALLED', false) == true) {
            $this->info('App already installed !');
            return 0;
        }

        $hostname = $this->ask('Database Hostname?', '127.0.0.1');
        $database = $this->ask('Database name?');
        $username = $this->ask('Database Username?');
        $password = $this->secret('Database Password?');

        $this->info('Processing...');

        if (Installer::createDbTables($hostname, $database, $username, $password) == false) {
            $this->error('Invalid Database Settings !');
            return 0;
        }

        $this->newLine();
        $this->info('Create Admin User');

        $name     = $this->ask('Admin Name?', 'Jhon Doe');
        $email    = $this->ask('Admin Email?', 'admin@demo.com');
        $password = $this->secret('Admin Password?');
        if ($password == '' || $password == null) {
            $this->error('Password must required !');
            return 0;
        }
        $password = Hash::make($password);

        $this->info('Processing...');

        Installer::createUser($name, $email, $password);
        Installer::finalTouches(env('APP_NAME'));

        //Import Dummy Data
        \DB::unprepared(file_get_contents('public/uploads/green_store_dummy.sql'));

        $this->info('App Installed Successfully.');
    }
}
