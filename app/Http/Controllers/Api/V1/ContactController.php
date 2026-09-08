<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $input = $request->all();

        if (empty($input['name']) && (!empty($input['first_name']) || !empty($input['last_name']))) {
            $input['name'] = trim(($input['first_name'] ?? '') . ' ' . ($input['last_name'] ?? ''));
        }

        $validator = Validator::make($input, [
            'name' => 'required|string|max:150',
            'email' => 'required|email|max:150',
            'phone' => 'nullable|string|max:50',
            'subject' => 'nullable|string|max:200',
            'message' => 'required|string|max:3000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $message = ContactMessage::create($validator->validated());

        return response()->json([
            'message' => 'Thank you! Your message has been received. Our concierge team will reach out shortly.',
            'data' => $message,
        ], 201);
    }

    public function subscribe(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:150',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Please provide a valid email address.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $subscriber = NewsletterSubscriber::firstOrCreate(
            ['email' => strtolower($request->email)],
            ['is_active' => true]
        );

        return response()->json([
            'message' => 'Thank you for subscribing to The Azura exclusive newsletter!',
            'data' => $subscriber,
        ], 200);
    }
}
