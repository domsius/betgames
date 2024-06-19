<?php
namespace App\Services;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\Request;

interface ProfileServiceInterface
{
    public function getUser(Request $request);
    public function updateUser(ProfileUpdateRequest $request);
    public function deleteUser(Request $request);
}