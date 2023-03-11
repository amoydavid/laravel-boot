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
    protected $signature = 'app:create.admin';

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
        do {
            $email = $this->ask('Please input admin email');
        } while (!$email);

        do {
            $phone = $this->ask('Please input mobile phone number');
            if (!preg_match('/^1\d{10}$/', $phone)) {
                $this->error("wrong mobile phone format");
                $phone = "";
            }
        } while (!$phone);

        $name = $this->ask('Please input real name');

        do {
            $password = $this->secret('What is the user ['.$email.'] password?');
        } while (!$password);

        $service = new SystemService();

        try {
            if ($service->createAdmin($email, $password, $phone, $name)) {
                $this->info("{$email} created success");
                return Command::SUCCESS;
            } else {
                $this->error("{$email} create failed");
                return Command::FAILURE;
            }
        }catch (\Throwable $e) {
            $this->error("{$email} create failed:".$e->getMessage());
            return Command::FAILURE;
        }
    }
}
