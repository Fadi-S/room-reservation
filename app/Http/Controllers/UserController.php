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
                    ->search(request("search"))
                    ->with("services")
                    ->orderBy("id")
                    ->paginate(15),
            ),
        ]);
    }

    public function create()
    {
        $this->authorize("admin");

        return inertia("Users/Form", [
            "user" => new User(),
            "services" => Service::query()
                ->orderBy("id")
                ->pluck("name", "id"),
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
            "password" => ["required", "string", "min:8", "max:255"],
            "services" => ["nullable", "array"],
            "services.*" => ["exists:services,id"],
            "is_admin" => ["required", "boolean"],
        ]);

        $user = User::query()->create([
            "name" => $request->get("name"),
            "username" => $request->get("username"),
            "password" => bcrypt($request->get("password")),
            "email" => $request->get("email"),
            "is_admin" => $request->boolean("is_admin"),
        ]);

        $user->services()->sync($request->get("services"));

        $this->flash(__("ui.user_created"));

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
            "user" => array_replace($user->toArray(), [
                "services" => $user->services->pluck("id")->toArray(),
            ]),
            "services" => Service::query()
                ->orderBy("id")
                ->pluck("name", "id"),
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
            "password" => ["nullable", "string", "min:8", "max:255"],
            "services" => ["nullable", "array"],
            "services.*" => ["exists:services,id"],
            "is_admin" => ["required", "boolean"],
        ]);

        $data = [
            "name" => $request->get("name"),
            "username" => $request->get("username"),
            "email" => $request->get("email"),
            "is_admin" => $request->boolean("is_admin"),
        ];

        if ($request->filled("password")) {
            $data["password"] = bcrypt($request->get("password"));
        }

        $user->update($data);

        $user->services()->sync($request->get("services", []));

        $this->flash(__("ui.user_edited"));

        return redirect()->route("users.edit", $user);
    }

    public function destroy(User $user)
    {
        $this->authorize("admin");
    }
}
