<?php declare(strict_types=1);

namespace App\Repositories\Users;

use App\Services\RegisterServiceRequest;

interface UserRepository
{
    public function add(RegisterServiceRequest $request): void;
}
