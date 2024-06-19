<?php
namespace App\Repositories;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\Request;

interface ProfileRepositoryInterface
{
    public function getUser(Request $request);
    public function updateUser(ProfileUpdateRequest $request);
    public function deleteUser(Request $request);
}