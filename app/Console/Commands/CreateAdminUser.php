<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

use function Laravel\Prompts\password;
use function Laravel\Prompts\text;

#[Signature('admin:create {--name= : Display name} {--email= : Login email} {--password= : Password (prompted when omitted)}')]
#[Description('Create an admin user, or reset the password of an existing one')]
class CreateAdminUser extends Command
{
    public function handle(): int
    {
        $name = $this->option('name') ?: text('Name', required: true);
        $email = $this->option('email') ?: text('Email', required: true);
        $password = $this->option('password') ?: password('Password (min. 8 characters)', required: true);

        $validator = Validator::make(
            compact('name', 'email', 'password'),
            ['name' => ['required', 'max:120'], 'email' => ['required', 'email'], 'password' => ['required', Password::min(8)]],
        );

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->components->error($error);
            }

            return self::FAILURE;
        }

        $user = User::query()->updateOrCreate(['email' => $email], ['name' => $name, 'password' => $password]);

        $this->components->info(($user->wasRecentlyCreated ? 'Created' : 'Updated')." admin user {$email}.");
        $this->line('Log in at '.route('admin.login'));

        return self::SUCCESS;
    }
}
