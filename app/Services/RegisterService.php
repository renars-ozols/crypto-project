<?php declare(strict_types=1);

namespace App\Services;

use App\Repositories\Users\MySQLUserRepository;
use App\Repositories\Users\UserRepository;

class RegisterService
{
    private UserRepository $userRepository;

    public function __construct()
    {
        $this->userRepository = new MySQLUserRepository();
    }

    public function execute(RegisterServiceRequest $request): void
    {
        $this->userRepository->add($request);
    }
}