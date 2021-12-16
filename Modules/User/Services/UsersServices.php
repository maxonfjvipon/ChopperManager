<?php


namespace Modules\User\Services;

use Inertia\Inertia;
use Inertia\Response;
use Modules\User\Contracts\UsersServiceContract;

abstract class UsersServices implements UsersServiceContract
{
    public function indexPath(): string
    {
        return 'User::Index';
    }

    public function editPath(): string
    {
        return 'User::Edit';
    }

    public function createPath(): string
    {
        return 'User::Create';
    }

    /**
     * @param $users
     * @return Response
     */
    public function __index($users): Response
    {
        return Inertia::render($this->indexPath(), [
            'users' => $users,
        ]);
    }
}
