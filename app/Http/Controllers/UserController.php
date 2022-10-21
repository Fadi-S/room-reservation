<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $this->authorize("admin");

        return inertia("Users/Index", [
            "users" => UserResource::collection(
                User::query()
                    ->with("services")
                    ->paginate(15),
            ),
        ]);
    }

    public function create()
    {
        $this->authorize("admin");

        return inertia("Users/Form", [
            "user" => new User(),
            "services" => Service::query()->pluck("name", "id"),
            "create" => true,
        ]);
    }

    public function store(Request $request)
    {
        $this->authorize("admin");

        $request->validate([
            "name" => ["required", "string", "max:255"],
            "username" => ["required", "string", "max:255", "unique:users"],
            "email" => [
                "required",
                "string",
                "email",
                "max:255",
                "unique:users",
            ],
            "password" => ["required", "string", "min:8", "confirmed"],
            "services" => ["required", "array"],
            "services.*" => ["exists:services,id"],
            "is_admin" => ["required", "boolean"],
        ]);

        $user = User::query()->create([
            "name" => $request->get("name"),
            "username" => $request->get("username"),
            "email" => $request->get("email"),
            "password" => bcrypt($request->get("password")),
            "is_admin" => $request->boolean("is_admin"),
        ]);

        $user->services()->sync($request->get("services"));

        session()->flash("message", "تم إنشاء المستخدم بنجاح");

        return redirect()->route("users.index");
    }

    public function show(User $user)
    {
        $this->authorize("admin");
    }

    public function edit(User $user)
    {
        $this->authorize("admin");

        $user->load("services:id");

        return inertia("Users/Form", [
            "user" => $user,
            "services" => Service::query()->pluck("name", "id"),
            "create" => false,
        ]);
    }

    public function update(Request $request, User $user)
    {
        $this->authorize("admin");

        $request->validate([
            "name" => ["required", "string", "max:255"],
            "username" => [
                "required",
                "string",
                "max:255",
                Rule::unique("users")->ignore($user->id),
            ],
            "email" => [
                "required",
                "string",
                "email",
                "max:255",
                Rule::unique("users")->ignore($user->id),
            ],
            "password" => ["nullable", "string", "min:8", "confirmed"],
            "services" => ["required", "array"],
            "services.*" => ["exists:services,id"],
            "is_admin" => ["required", "boolean"],
        ]);

        $user->update([
            "name" => $request->get("name"),
            "username" => $request->get("username"),
            "email" => $request->get("email"),
            "password" => $request->has("password")
                ? bcrypt($request->get("password"))
                : $user->password,
            "is_admin" => $request->boolean("is_admin"),
        ]);

        session()->flash("message", "تم تعديل المستخدم بنجاح");

        return redirect()->route("users.edit", $user);
    }

    public function destroy(User $user)
    {
        $this->authorize("admin");
    }
}
