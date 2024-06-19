<?php
namespace App\Services;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\Request;
use App\Repositories\ProfileRepositoryInterface;

class ProfileService implements ProfileServiceInterface
{
    protected $profileRepository;

    public function __construct(ProfileRepositoryInterface $profileRepository)
    {
        $this->profileRepository = $profileRepository;
    }

    public function getUser(Request $request)
    {
        return $this->profileRepository->getUser($request);
    }

    public function updateUser(ProfileUpdateRequest $request)
    {
        return $this->profileRepository->updateUser($request);
    }

    public function deleteUser(Request $request)
    {
        return $this->profileRepository->deleteUser($request);
    }
}