<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CouponController extends Controller
{
    public function validateCoupon(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string',
            'amount' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'valid' => false,
                'message' => 'Please provide a coupon code and amount.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $code = strtoupper(trim($request->code));
        $amount = (float) $request->amount;

        $coupon = Coupon::where('code', $code)
            ->where('is_active', true)
            ->first();

        if (!$coupon) {
            return response()->json([
                'valid' => false,
                'message' => 'Invalid coupon code.',
            ], 404);
        }

        if (!$coupon->isValidForAmount($amount)) {
            $msg = 'Coupon is not applicable.';
            if ($coupon->expires_at && $coupon->expires_at->isPast()) {
                $msg = 'This coupon has expired.';
            } elseif ($coupon->min_spend > 0 && $amount < (float) $coupon->min_spend) {
                $msg = "Minimum booking spend of ৳{$coupon->min_spend} required.";
            }

            return response()->json([
                'valid' => false,
                'message' => $msg,
            ], 422);
        }

        $discount = $coupon->calculateDiscount($amount);
        $finalAmount = max(0, $amount - $discount);

        return response()->json([
            'valid' => true,
            'message' => "Coupon applied successfully!",
            'data' => [
                'code' => $coupon->code,
                'discount_type' => $coupon->discount_type,
                'discount_value' => (float) $coupon->discount_value,
                'discount_amount' => $discount,
                'original_amount' => $amount,
                'final_amount' => $finalAmount,
            ],
        ]);
    }
}
