<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateTestUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:test_users 
        {password : Will be set for all users} 
        {--m|mail_prefix=* : Mail prefixes used for user creating with domain @example.com . Default creates user test@example.com} ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create 3 test users with  the given password';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $created = [];

        /** @var string[] $prefixes */
        $prefixes = $this->option('mail_prefix');
        if (empty($prefixes)) {
            $prefixes[] = 'test2';
        }

        foreach ($prefixes as $prefix) {
            $mail = $prefix . '@example.com';
            $user = User::where('email', $mail)->first();
            if ($user !== null) {
                $rejected[] = $mail;
                continue;
            }

            /** @var string $password */
            $password = $this->argument('password');
            User::factory()->create([
                'name' => "Test User: $prefix",
                'email' => $mail,
                'password' => Hash::make($password)
            ])->save();
            $created[] = $mail;
        }

        if (!empty($created)) {
            $this->info('Users ' . implode(',', $created) . ' created successful!');
        }
        if (!empty($rejected)) {
            $this->error('Users ' . implode(',', $rejected) . " weren't created. Already exist in database.");
        }

        return Command::SUCCESS;
    }
}
