<?php

namespace App\Http\Resources;

class TransactionResource
{
    public static function transform($transaction): array
    {
        $data = [
            'transaction_id' => $transaction->transaction_id,
            'order_id' => $transaction->order_id,
            'amount' => (float) $transaction->amount,
            'content' => $transaction->content,
            'code' => $transaction->code,
            'type' => $transaction->type,
            'mode' => $transaction->mode,
            'status' => $transaction->status,
            'created_at' => $transaction->created_at?->toIso8601String(),
            'updated_at' => $transaction->updated_at?->toIso8601String(),
        ];

        // Include order info if loaded
        if ($transaction->relationLoaded('order') && $transaction->order) {
            $data['order'] = [
                'order_id' => $transaction->order->order_id,
                'user_id' => $transaction->order->user_id,
                'grand_total' => (float) $transaction->order->grand_total,
                'status' => $transaction->order->status,
                'orders_at' => $transaction->order->orders_at?->toIso8601String(),
            ];

            // Include user info if loaded
            if ($transaction->order->relationLoaded('user') && $transaction->order->user) {
                $data['order']['user'] = [
                    'user_id' => $transaction->order->user->user_id,
                    'first_name' => $transaction->order->user->first_name,
                    'last_name' => $transaction->order->user->last_name,
                    'email' => $transaction->order->user->email,
                ];
            }
        }

        return $data;
    }

    public static function collection($transactions): array
    {
        return $transactions->map(fn($transaction) => self::transform($transaction))->toArray();
    }
}

