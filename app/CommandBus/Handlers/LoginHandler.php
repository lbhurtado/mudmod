<?php

namespace App\CommandBus\Handlers;

use Setting;
use App\Models\User;
use App\Notifications\Login;
use App\CommandBus\Commands\LoginCommand;
use Grosv\LaravelPasswordlessLogin\LoginUrl;

class LoginHandler
{
    /**
     * @param LoginCommand $command
     */
    public function handle(LoginCommand $command)
    {
        if ($command->pin == Setting::get('PIN')) {
            $command->origin->notify(new Login($this->getMessage()));
        }
    }

    protected function getMessage()
    {
        $user = User::find(config('mudmod.login.user'));
        $redirect = config('mudmod.login.redirect');

        return $this->getGenerator($user, $redirect)
            ->generate();
    }

    protected function getGenerator(User $user, $redirect)
    {
        return tap(new LoginUrl($user))
            ->setRedirectUrl($redirect);
    }
}
