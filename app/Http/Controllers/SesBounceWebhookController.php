<?php

namespace App\Http\Controllers;

use App\Models\BlockedEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SesBounceWebhookController extends Controller
{
    public function __invoke(Request $request)
    {
        $messageType = $request->header('X-Amz-Sns-Message-Type');
        $payload = json_decode($request->getContent(), true);

        if (!$payload || !$messageType) {
            return response()->json(['error' => 'Invalid payload'], 400);
        }

        if ($messageType === 'SubscriptionConfirmation') {
            Http::get($payload['SubscribeURL']);
            return response()->json(['status' => 'confirmed']);
        }

        if ($messageType === 'Notification') {
            $message = json_decode($payload['Message'] ?? '{}', true);

            if (($message['notificationType'] ?? null) !== 'Bounce') {
                return response()->json(['status' => 'ignored']);
            }

            $bounce = $message['bounce'] ?? [];
            $bounceType = $bounce['bounceType'] ?? null;
            $bounceSubtype = $bounce['bounceSubType'] ?? null;

            foreach ($bounce['bouncedRecipients'] ?? [] as $recipient) {
                $email = strtolower(trim($recipient['emailAddress'] ?? ''));

                if (!$email) {
                    continue;
                }

                BlockedEmail::updateOrCreate(
                    ['email' => $email],
                    [
                        'reason' => $recipient['diagnosticCode'] ?? null,
                        'bounce_type' => $bounceType,
                        'bounce_subtype' => $bounceSubtype,
                        'raw_payload' => $payload,
                    ]
                );

                Log::info("Blocked email due to SES bounce: {$email}", [
                    'bounce_type' => $bounceType,
                    'bounce_subtype' => $bounceSubtype,
                ]);
            }

            return response()->json(['status' => 'processed']);
        }

        return response()->json(['status' => 'ignored']);
    }
}
