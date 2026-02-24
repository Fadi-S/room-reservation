<?php

use App\Models\BlockedEmail;

uses()->group("blocked-emails");

test("isBlocked returns false for unknown email", function () {
    expect(BlockedEmail::isBlocked("unknown@example.com"))->toBeFalse();
});

test("isBlocked returns true for a blocked email", function () {
    BlockedEmail::create([
        "email" => "bounced@example.com",
        "bounce_type" => "Permanent",
        "bounce_subtype" => "General",
    ]);

    expect(BlockedEmail::isBlocked("bounced@example.com"))->toBeTrue();
});

test("isBlocked is case-insensitive", function () {
    BlockedEmail::create([
        "email" => "bounced@example.com",
    ]);

    expect(BlockedEmail::isBlocked("Bounced@Example.COM"))->toBeTrue();
});

test("SES bounce webhook confirms SNS subscription", function () {
    Http::fake();

    $payload = json_encode([
        "Type" => "SubscriptionConfirmation",
        "SubscribeURL" => "https://sns.amazonaws.com/confirm?token=abc123",
    ]);

    $this->postJson("/webhooks/ses/bounce", json_decode($payload, true), [
        "X-Amz-Sns-Message-Type" => "SubscriptionConfirmation",
    ])->assertOk()->assertJson(["status" => "confirmed"]);

    Http::assertSent(fn($request) => $request->url() === "https://sns.amazonaws.com/confirm?token=abc123");
});

test("SES bounce webhook stores bounced email", function () {
    $bouncedEmail = "hard-bounce@example.com";

    $message = json_encode([
        "notificationType" => "Bounce",
        "bounce" => [
            "bounceType" => "Permanent",
            "bounceSubType" => "General",
            "bouncedRecipients" => [
                [
                    "emailAddress" => $bouncedEmail,
                    "diagnosticCode" => "smtp; 550 5.1.1 user unknown",
                ],
            ],
        ],
    ]);

    $payload = [
        "Type" => "Notification",
        "Message" => $message,
    ];

    $this->postJson("/webhooks/ses/bounce", $payload, [
        "X-Amz-Sns-Message-Type" => "Notification",
    ])->assertOk()->assertJson(["status" => "processed"]);

    $this->assertDatabaseHas("blocked_emails", [
        "email" => $bouncedEmail,
        "bounce_type" => "Permanent",
        "bounce_subtype" => "General",
    ]);
});

test("SES bounce webhook updates existing blocked email record", function () {
    BlockedEmail::create(["email" => "repeat@example.com", "bounce_type" => "Transient"]);

    $message = json_encode([
        "notificationType" => "Bounce",
        "bounce" => [
            "bounceType" => "Permanent",
            "bounceSubType" => "General",
            "bouncedRecipients" => [
                ["emailAddress" => "repeat@example.com"],
            ],
        ],
    ]);

    $this->postJson("/webhooks/ses/bounce", [
        "Type" => "Notification",
        "Message" => $message,
    ], [
        "X-Amz-Sns-Message-Type" => "Notification",
    ])->assertOk();

    expect(BlockedEmail::count())->toBe(1);
    expect(BlockedEmail::first()->bounce_type)->toBe("Permanent");
});

test("SES bounce webhook ignores non-bounce notifications", function () {
    $message = json_encode([
        "notificationType" => "Complaint",
    ]);

    $this->postJson("/webhooks/ses/bounce", [
        "Type" => "Notification",
        "Message" => $message,
    ], [
        "X-Amz-Sns-Message-Type" => "Notification",
    ])->assertOk()->assertJson(["status" => "ignored"]);

    expect(BlockedEmail::count())->toBe(0);
});
