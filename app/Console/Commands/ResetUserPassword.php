<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class ResetUserPassword extends Command
{
    /**
     * The name and signature of the console command.
     *
     * Usage: php artisan user:reset-password {identifier} {--password=}
     */
    protected $signature = 'user:reset-password
                            {identifier : User ID or email}
                            {--password= : New password (min 8). If omitted, prompts securely}';

    /**
     * The console command description.
     */
    protected $description = 'Reset a user\'s password by ID or email';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $identifier = (string) $this->argument('identifier');

        $user = $this->findUser($identifier);
        if (!$user) {
            $this->error("User not found for identifier: {$identifier}");
            return self::FAILURE;
        }

        $password = (string) ($this->option('password') ?? '');
        if ($password === '') {
            $password = (string) $this->secret('Enter new password (min 8 chars)');
            $confirm  = (string) $this->secret('Confirm new password');

            if ($password !== $confirm) {
                $this->error('Password confirmation does not match.');
                return self::FAILURE;
            }
        }

        if (strlen($password) < 8) {
            $this->error('Password must be at least 8 characters.');
            return self::FAILURE;
        }

        $user->password = Hash::make($password);
        $user->setRememberToken(Str()->random(60));
        $user->save();

        $this->info("Password reset successful for user ID {$user->id} ({$user->email}).");
        return self::SUCCESS;
    }

    private function findUser(string $identifier): ?User
    {
        // Try by numeric ID first
        if (ctype_digit($identifier)) {
            $byId = User::find((int) $identifier);
            if ($byId) {
                return $byId;
            }
        }

        // Fallback to email lookup
        return User::where('email', $identifier)->first();
    }
}
