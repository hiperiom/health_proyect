<?php

namespace App\Actions\Fortify;

use App\Models\User;
use App\Models\UserProfile;
use App\Helpers\NameHelper;
use App\Http\Requests\Auth\RegistrationRequest;
use App\Services\User\StoreService as UsersService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $inputs): User
    {
        $rules = (new RegistrationRequest())->rules();
        
        $validated = Validator::make($inputs, $rules)->validate();

        return DB::transaction(function () use ($validated) {
            $usersService = new UsersService();
            return $usersService->execute($validated);
        });
    }
}
