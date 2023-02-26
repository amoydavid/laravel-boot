<?php

namespace App\Console\Commands;

use App\Models\WxUser;
use App\Services\SystemService;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class CreateAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create.admin {username?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Admin for Backend';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $username = $this->argument('username');
        if (!$username) {
            do {
                $username = $this->ask('Please input admin username');
            } while (!$username);
        }
        do {
            $password = $this->secret('What is the user ['.$username.'] password?');
        } while (!$password);

        $service = new SystemService();

        try {
            if ($service->createAdmin($username, $password)) {
                $this->info("{$username} created success");
                return Command::SUCCESS;
            } else {
                $this->error("{$username} create failed");
                return Command::FAILURE;
            }
        }catch (\Throwable $e) {
            $this->error("{$username} create failed:".$e->getMessage());
            return Command::FAILURE;
        }
    }
}
