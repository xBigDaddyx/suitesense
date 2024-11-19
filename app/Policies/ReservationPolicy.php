<?php

namespace App\Policies;

use App\Enums\GuestStatus;
use App\Enums\ReservationStatus;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ReservationPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_reservation');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Reservation $reservation): bool
    {
        return $user->can('view_reservation');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_reservation');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Reservation $reservation): bool
    {
        if ($reservation->status == ReservationStatus::COMPLETED->value) {
            return false;
        }
        return $user->can('update_reservation');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Reservation $reservation): bool
    {
        return $user->can('delete_reservation');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Reservation $reservation): bool
    {
        return $user->can('restore_reservation');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Reservation $reservation): bool
    {
        return $user->can('force_delete_reservation');
    }

    /** Determine whether the user can check in the reservation */
    public function checkInReservation(User $user, Reservation $reservation): bool
    {
        if ($reservation->status == ReservationStatus::COMPLETED->value) {
            return false;
        }
        return $user->can('check_in_reservation');
    }

    /** Determine whether the user can check out the reservation */
    public function checkOutReservation(User $user, Reservation $reservation): bool
    {
        if ($reservation->status == ReservationStatus::COMPLETED->value && $reservation->guest_status != GuestStatus::CHECKIN->value) {
            return false;
        }
        return $user->can('check_out_reservation');
    }

    /** Determine whether the user can cancel the reservation */
    public function cancelReservation(User $user, Reservation $reservation): bool
    {
        if ($reservation->status == ReservationStatus::COMPLETED->value && $reservation->guest_status != GuestStatus::CHECKIN->value) {
            return false;
        }
        return $user->can('cancel_reservation');
    }

    /** Determine whether the user can extend the reservation */
    public function extendReservation(User $user, Reservation $reservation): bool
    {
        if ($reservation->status == ReservationStatus::COMPLETED->value && $reservation->guest_status != GuestStatus::CHECKIN->value) {
            return false;
        }
        return $user->can('extend_reservation');
    }
}
