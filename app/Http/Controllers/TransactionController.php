<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Snap;
use App\Models\Transaction;

class TransactionController extends Controller
{
    public function __construct()
    {
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    // 1. Buat Transaksi Baru (Status Awal: Pending)
    public function bayar(Request $request)
    {
        $request->validate([
            'jumlah' => 'required|numeric|min:1000',
            'keterangan' => 'required|string|max:255',
        ]);

        $orderId = 'KAS-' . time() . '-' . rand(100, 999);
        $amount = (int) $request->jumlah;

        // Simpan transaksi awal di database dengan status pending
        $trx = Transaction::create([
            'order_id' => $orderId,
            'keterangan' => $request->keterangan,
            'jumlah' => $amount,
            'status' => 'pending',
        ]);

        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $amount,
            ],
            'customer_details' => [
                'first_name' => $request->keterangan,
                'email' => 'user@kasportal.com',
            ],
        ];

        try {
            $snapToken = Snap::getSnapToken($params);

            return response()->json([
                'status' => 'success',
                'snapToken' => $snapToken,
                'orderId' => $orderId
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // 2. Webhook Callback dari Midtrans (Anti-Manipulasi)
    public function notificationHandler(Request $request)
    {
        $serverKey = env('MIDTRANS_SERVER_KEY');
        $orderId = $request->order_id;
        $statusCode = $request->status_code;
        $grossAmount = $request->gross_amount;
        $signatureKey = $request->signature_key;

        // Validasi keaslian signature dari Midtrans
        $mySignature = hash("sha512", $orderId . $statusCode . $grossAmount . $serverKey);

        if ($signatureKey !== $mySignature) {
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $transactionStatus = $request->transaction_status;
        $paymentType = $request->payment_type;

        $trx = Transaction::where('order_id', $orderId)->first();
        if (!$trx) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        // Update status sesuai respons Midtrans
        if ($transactionStatus == 'settlement' || $transactionStatus == 'capture') {
            $trx->update([
                'status' => 'settlement',
                'payment_type' => $paymentType,
                'transaction_time' => $request->transaction_time ?? now(),
            ]);
        } else if ($transactionStatus == 'cancel' || $transactionStatus == 'deny' || $transactionStatus == 'expire') {
            $trx->update(['status' => $transactionStatus]);
        }

        return response()->json(['message' => 'Notification processed successfully']);
    }

    // 3. Ambil Riwayat HANYA Transaksi yang Sukses (Settlement)
    public function getRiwayat()
    {
        // Hanya mengambil data transaksi yang benar-benar LUNAS di Midtrans
        $data = Transaction::where('status', 'settlement')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($data);
    }

    // 4. Ringkasan Total Kas Asli dari Midtrans
    public function summary()
    {
        $total = Transaction::where('status', 'settlement')->sum('jumlah');

        return response()->json([
            'status' => 'success',
            'total_kas' => $total
        ]);
    }
}