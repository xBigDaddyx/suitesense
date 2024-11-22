<x-layouts.app>
    <div class="w-full mx-auto p-6">
        <!-- Header Section -->
        <div class="flex items-center justify-between mb-6">
            <img src="{{ asset('images/logo/suite_sense_logo_white.png') }}" alt="Company Logo" class="h-10">
            <h1 class="text-2xl font-bold">Reservation Daily Report</h1>
            <div>
                <p class="text-2xl font-bold text-primary-500">{{ auth()->user()->latestHotel->name }}</p>
                <span class="text-xs text-wrap text-gray-700">{{ auth()->user()->latestHotel->address }}</span>
            </div>

        </div>

        <!-- Filtering Information -->
        <div class="mb-4">
            <p class="text-xs text-gray-700"><strong>Report Date:</strong>
                {{ $date ? $date : 'N/A' }}</p>

        </div>
        {{--
        <!-- Summary Statistics -->
        <div class="bg-gray-100 p-4 rounded-md mb-6">
            <h2 class="text-lg font-semibold">Summary Statistics</h2>
            <p><strong>Total Reservations:</strong> {{ $totalReservations }}</p>
            <p><strong>Total Revenue:</strong>
                {{ trans('frontOffice.room.pricePrefix') . number_format($totalRevenue, 2) }}</p>
        </div> --}}

        <div class="flex flex-col">
            <div class="-m-1.5 overflow-x-auto">
                <div class="p-1.5 min-w-full inline-block align-middle">
                    <div class="border rounded-lg overflow-hidden dark:border-neutral-700">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700">
                            <thead class="bg-gray-50 dark:bg-neutral-700">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-start text-xs font-semibold text-gray-500 uppercase dark:text-neutral-400">
                                        Number</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-start text-xs font-semibold text-gray-500 uppercase dark:text-neutral-400">
                                        Room Type</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-start text-xs font-semibold text-gray-500 uppercase dark:text-neutral-400">
                                        Guest</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-start text-xs font-semibold text-gray-500 uppercase dark:text-neutral-400">
                                        Check In</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-start text-xs font-semibold text-gray-500 uppercase dark:text-neutral-400">
                                        Check Out</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-start text-xs font-semibold text-gray-500 uppercase dark:text-neutral-400">
                                        Status</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-end text-xs font-semibold text-gray-500 uppercase dark:text-neutral-400">
                                        Revenue</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-neutral-700">
                                @foreach ($datas as $data)
                                    <tr>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-xs font-medium text-gray-800 dark:text-neutral-200">
                                            <span
                                                class="font-mono text-xs text-blue-600 dark:text-blue-500">{{ $data->number }}</span>
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-xs font-medium text-gray-800 dark:text-neutral-200">
                                            {{ $data->room->roomType->name }}</td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-xs font-medium text-gray-800 dark:text-neutral-200">
                                            {{ $data->guest->name }}</td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-xs font-medium text-gray-800 dark:text-neutral-200">
                                            {{ \Carbon\Carbon::parse($data->check_in)->format('D, d M Y') }}</td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-xs text-gray-800 dark:text-neutral-200">
                                            {{ \Carbon\Carbon::parse($data->check_out)->format('D, d M Y') }}</td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-xs text-gray-800 dark:text-neutral-200">
                                            @php
                                                $color = \App\Enums\ReservationStatus::from($data->status)->color();
                                                $icon = \App\Enums\ReservationStatus::from($data->status)->icon();
                                            @endphp
                                            <span @class([
                                                'py-1 px-2 inline-flex items-center gap-x-1 text-xs font-bold bg-red-100 text-red-800 rounded-full dark:bg-red-500/10 dark:text-red-500' =>
                                                    $data->status == 'cancelled',
                                                'py-1 px-2 inline-flex items-center gap-x-1 text-xs font-bold bg-teal-100 text-teal-800 rounded-full dark:bg-teal-500/10 dark:text-teal-500' =>
                                                    $data->status == 'completed',
                                                'py-1 px-2 inline-flex items-center gap-x-1 text-xs font-bold bg-blue-100 text-blue-800 rounded-full dark:bg-blue-500/10 dark:text-blue-500' =>
                                                    $data->status == 'confirmed',
                                            ])>
                                                @svg($icon, 'shrink-0 size-3')
                                                {{ ucfirst($data->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-end text-xs font-medium">
                                            @if ($data->status == 'cancelled')
                                                0
                                            @else
                                                {{ trans('frontOffice.room.pricePrefix') . $data->total_price }}
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- Flex -->
            <div class="mt-8 flex sm:justify-end">
                <div class="w-full max-w-2xl sm:text-end space-y-2">
                    <!-- Grid -->
                    <div class="grid grid-cols-2 sm:grid-cols-1 gap-3 sm:gap-2">
                        <dl class="grid sm:grid-cols-5 gap-x-3">
                            <dt class="col-span-3 font-semibold text-gray-800 dark:text-neutral-200">Total Revenue:</dt>
                            <dd class="col-span-2 text-primary-500 dark:text-neutral-500">
                                {{ trans('frontOffice.room.pricePrefix') . number_format($totalRevenue, 2) }}</dd>
                        </dl>

                        <dl class="grid sm:grid-cols-5 gap-x-3">
                            <dt class="col-span-3 font-semibold text-gray-800 dark:text-neutral-200">Completed
                                Reservations:
                            </dt>
                            <dd class="col-span-2 text-teal-500 dark:text-neutral-500">{{ $completedReservations }}
                            </dd>
                        </dl>
                        <dl class="grid sm:grid-cols-5 gap-x-3">
                            <dt class="col-span-3 font-semibold text-gray-800 dark:text-neutral-200">Cancel
                                Reservations:
                            </dt>
                            <dd class="col-span-2 text-red-500 dark:text-neutral-500">{{ $cancelledReservations }}
                            </dd>
                        </dl>

                        <dl class="grid sm:grid-cols-5 gap-x-3">
                            <dt class="col-span-3 font-semibold text-gray-800 dark:text-neutral-200">Total
                                Reservations:
                            </dt>
                            <dd class="col-span-2 text-gray-500 dark:text-neutral-500">{{ $totalReservations }}
                            </dd>
                        </dl>
                    </div>
                    <!-- End Grid -->
                </div>
            </div>
            <!-- End Flex -->

        </div>
    </div>
</x-layouts.app>
