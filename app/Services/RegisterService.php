<?php declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use App\Repositories\Users\UserRepository;

class RegisterService
{
    private UserRepository $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function execute(RegisterServiceRequest $request): ?User
    {
        $this->repository->add($request);
        return $this->repository->getByEmail($request->getEmail());
    }
}