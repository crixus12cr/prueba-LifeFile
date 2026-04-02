<?php

namespace App\Http\Repositories;

use App\Models\User;

class UserRepository
{

    /**
     * Find a user by their email.
     *
     * @param string $email The email of the user.
     * @return User|null Returns the User instance if found, or null otherwise.
     */
    public function findByEmail(string $email): ?User {
        return User::where('email', $email)->first();
    }
}
