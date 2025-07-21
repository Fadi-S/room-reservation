<?php

namespace App\Helpers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\Facades\URL;

class PasswordlessLogin
{

    public function __construct(private readonly UrlGenerator $urlGenerator)
    {
    }

    public function forUser(User $user, \DateInterval|\DateTimeInterface|int $expiration = null): string
    {
        $expiration ??= now()->addHours(3);

        return URL::temporarySignedRoute(
            "passwordless.login",
            $expiration,
            ['user' => $user]
        );
    }

    public function check(Request $request) : bool
    {
        return $this->urlGenerator->hasCorrectSignature($request) &&
            $this->urlGenerator->signatureHasNotExpired($request);
    }
}
