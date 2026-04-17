<?php

namespace App\Policies;

use App\Models\Category;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CategoryPolicy
{

    public function view(User $user, Category $category): bool
    {
        return $user->id === $category->user_id;
    }


    public function update(User $user, Category $category): bool
    {
        return $user->id === $category->user_id;
    }


    public function delete(User $user, Category $category): bool
    {
        return $user->id === $category->user_id;
    }

    public function UpdateGlobalCategory(User $user, Category $category): bool
    {
        if (!$category->is_global && $user->role === "Admin") return true;
        return false;
    }

    public function CreateGlobalCategory(User $user): bool
    {
        return $user->role === "Admin";
    }

    public function ShowGlobalCategory(User $user): bool
    {
        if ($user->role == "Admin") return true;
        return false;
    }
}
