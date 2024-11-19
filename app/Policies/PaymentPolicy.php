<?php

namespace App\Policies;

use App\Enums\PaymentStatus;
use App\Enums\ReservationStatus;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PaymentPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_payment');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Payment $payment): bool
    {
        return $user->can('view_payment');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_payment');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Payment $payment): bool
    {
        if ($payment->status == PaymentStatus::COMPLETED->value || $payment->status == PaymentStatus::REFUNDED->value) {
            return false;
        }
        return $user->can('update_payment');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Payment $payment): bool
    {
        return $user->can('delete_payment');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Payment $payment): bool
    {
        return $user->can('restore_payment');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Payment $payment): bool
    {
        return $user->can('force_delete_payment');
    }

    /** Determine whether the user can refund the payment */
    public function refundPayment(User $user, Payment $payment): bool
    {
        if ($payment->status != PaymentStatus::COMPLETED->value && $payment->reservation->status == ReservationStatus::CANCELLED->value || $payment->status == PaymentStatus::REFUNDED->value) {
            return false;
        }
        return $user->can('refund_payment');
    }
    /** Determine whether the user can paid the payment */
    public function paidPayment(User $user, Payment $payment): bool
    {
        if ($payment->status == PaymentStatus::COMPLETED->value && $payment->reservation->status == ReservationStatus::CANCELLED->value || $payment->status == PaymentStatus::REFUNDED->value) {
            return false;
        }
        return $user->can('paid_payment');
    }
}
