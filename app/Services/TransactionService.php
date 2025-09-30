<?php

namespace App\Services;

use App\Contracts\CartRepositoryInterface;
use App\Contracts\ProductRepositoryInterface;
use App\Contracts\TransactionRepositoryInterface;
use App\DTOs\TransactionData;
use App\Enums\OrderStatusEnum;
use App\Models\DetailTransaksi;
use App\Models\Product;
use App\Models\transaksi;
use App\ValueObjects\TransactionSummary;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransactionService
{
    public function __construct(
        private readonly TransactionRepositoryInterface $transactionRepo,
        private readonly CartRepositoryInterface $cartRepo,
        private readonly ProductRepositoryInterface $productRepo,
    ) {}

    public function getUserTransaction(int $userId): Collection
    {
        return $this->transactionRepo->getUserTransactions($userId);
    }

    public function processPayment(TransactionData $transactionData, array $checkoutItems): string
    {
        return DB::transaction(function () use ($transactionData, $checkoutItems) {

            $this->cartRepo->updateCartStatus(Auth::id(), $checkoutItems);

            $cartItems = $this->cartRepo->getCartItems(Auth::id(), 1);

            if ($cartItems->isEmpty()) {
                throw new \Exception('Tidak ada item untuk diproses');
            }

            $summary = $this->calculateTransactionSummary($cartItems, $transactionData);

            $transactionCode = $this->transactionRepo->generateTransactionCode();

            $transaction = $this->transactionRepo->createTransaction([
                'code_transaksi' => $transactionCode,
                'total_qty' => $summary->totalQuantity,
                'total_harga' => $summary->subtotal,
                'nama_pelanggan' => $transactionData->customerName,
                'alamat' => $transactionData->address,
                'no_telp' => $transactionData->phone,
                'ekspedisi' => $transactionData->courier,
                'metode_pembayaran' => $transactionData->paymentMethod,
                'subtotal' => $summary->subtotal,
                'ongkir' => $summary->shippingCost,
                'biaya_layanan' => $summary->serviceFee,
                'total_pembayaran' => $summary->totalAmount,
                'status_pesanan' => OrderStatusEnum::PENDING->value,
                'tanggal_pemesanan' => now(),
                'id_user' => Auth::id(),
            ]);

            $this->transactionRepo->createTransactionDetails(
                $transaction->id,
                $transactionCode,
                $cartItems,
            );

            foreach ($cartItems as $item) {
                $this->productRepo->updateStock($item->id_barang, $item->stok);
            }

            $this->cartRepo->clearCart(Auth::id(), 1);

            return $transactionCode;
        });
    }

    public function cancelOrder(string $transactionCode): void
    {
        $transaction = $this->transactionRepo->findByCode($transactionCode);

        if (!$transaction) {
            throw new \Exception('pesana tidak ditemukan');
        }

        $status = OrderStatusEnum::from($transaction->status_pesanan);
        if (!$status->canBeCancelled()) {
            throw new \Exception('Pesana tidak dapat dibatalkan. Status saat ini' . $status->getDisplayText());
        }

        DB::transaction(function () use ($transactionCode) {
            // mengembalikan stok produk
            $details = DetailTransaksi::where('id_transaksi_code', $transactionCode)->get();
            foreach ($details as $detail) {
                $product = Product::find($detail->id_barang);
                if ($product) {
                    $product->stok += $detail->stok;
                    $product->stok_out -= $detail->stok;
                    $product->save();
                }
            }

            $this->transactionRepo->updateTransactionStatus($transactionCode, OrderStatusEnum::CANCELED->value);
        });
    }

    public function confirmOrder(string $transactionCode): void
    {
        $transaction = $this->transactionRepo->findByCode($transactionCode);

        if (!$transaction) {
            throw new \Exception('Pesanan tidak ditemukan');
        }

        $status = OrderStatusEnum::from($transaction->status_pesanan);
        if (!$status->getDisplayText())
        {
            throw new \Exception('Pesanan belum dapat dikonfirmasi. Status' . $status->getDisplayText());
        }

        $this->transactionRepo->deleteTransaction($transactionCode);
    }

    public function getTransactionByCode(string $code): ?transaksi
    {
        return $this->transactionRepo->findByCode($code);
    }

    public function canCancelOrder(string $transactionCode): bool 
    {
        $transaction = $this->getTransactionByCode($transactionCode);

        if (!$transaction) {
            return false;
        }

        $status = OrderStatusEnum::from($transaction->status_pesanan);
        return $status->canBeCancelled();
    }

    public function canConfirmOrder(string $transactionCode): bool
    {
        $transaction = $this->getTransactionByCode($transactionCode);

        if (!$transaction) {
            return false;
        }

        $status = OrderStatusEnum::from($transaction->status_pesanan);
        return $status->canBeCompleted();
    }

    public function getTransactionSummaryByCode(string $code): ?array
    {
        $transaction = $this->getTransactionByCode($code);

        if (!$transaction) {
            return null;
        }

        return [
            'code' =>  $transaction->code_transaksi,
            'customer_name' => $transaction->nama_pelanggan,
            'address' => $transaction->alamat,
            'phone' => $transaction->no_telp,
            'courier' => $transaction->ekspedisi,
            'payment_method' => $transaction->metode_pembayaran,
            'subtotal' => $transaction->subtotal,
            'shipping_cost' => $transaction->ongkir,
            'service_fee' => $transaction->biaya_layanan,
            'total_amount' => $transaction->total_pembayaran,
            'status' => $transaction->status_pesanan,
            'order_date' => $transaction->tanggal_pemesanan,
        ];
    }

    private function calculateTransactionSummary($cartItems, TransactionData $transactionData): TransactionSummary
    {
        $subtotal = $cartItems->sum('harga');
        $totalQuantity = $cartItems->sum('stok');

        $shippingCost = match ($transactionData->courier) {
            'jnt' => 15000,
            'jne' => 20000,
            'pos' => 12000,
            'sicepat' => 18000,
            default => 15000,
        };

        $serviceFee = match ($transactionData->paymentMethod) {
            'cod' => 1000,
            'dana' => 1500,
            'gopay' => 1000,
            'transfer' => 0,
            default => 0,
        };

        return new TransactionSummary(
            subtotal: $subtotal,
            shippingCost: $shippingCost,
            serviceFee: $serviceFee,
            totalAmount: $subtotal + $shippingCost + $serviceFee,
            totalQuantity: $totalQuantity,
        );
    }

    private function findTransactionOrFail(string $code)
    {
        $transaction = $this->transactionRepo->findByCode($code);
        if (!$transaction)
        {
            throw new \Exception('Pesanan tidak ditemukan');
        }
        return $transaction;
    }

    private function validateOrderStatus($transaction, string $action): void
    {
        $status = OrderStatusEnum::from($transaction->status_pesanan);

        $canPerformAction = match($action) {
            'confirm' => $status->canBeCompleted(),
            'cancel' => $status->canBeCancelled(),
            default => false,
        };

        if (!$canPerformAction) {
            $actionText = $action === 'confirm' ? 'dikonfirmasi' : 'dibatalkan';
            throw new \Exception("Pesanan tidak dapat {$actionText}. Status: {$status->getDisplayText()}");
        }
    }
}
