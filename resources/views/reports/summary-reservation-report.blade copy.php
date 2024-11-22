<x-layouts.app>
    <!-- Invoice -->
    <div class="max-w-[85rem] px-4 sm:px-6 lg:px-8 mx-auto my-4 sm:my-10">
        <div class="mx-auto">
            <!-- Card -->
            <div class="flex flex-col p-4 sm:p-10">
                <!-- Grid -->
                <div class="flex justify-between mb-16">
                    <div>
                        <img src="{{ asset('storage/images/logo/suite_sense_logo_white.png') }}" alt="logo"
                            class="h-12">
                    </div>
                    <!-- Col -->

                    <div class="text-end">
                        <h2 class="text-2xl md:text-3xl font-semibold text-gray-800 dark:text-neutral-200">Summary
                            Reservations
                            Report
                        </h2>
                        <span
                            class="text-xl mt-1 block text-gray-500 dark:text-neutral-500">{{ auth()->user()->latestHotel->name }}</span>

                    </div>
                    <!-- Col -->
                </div>
                <!-- End Grid -->

                <!-- Grid -->
                <div class="mt-8 grid sm:grid-cols-2 gap-3">
                    <div>

                    </div>
                    <!-- Col -->

                    <div class="sm:text-end space-y-2 mb-8">
                        <!-- Grid -->
                        <div class="grid grid-cols-2 sm:grid-cols-1 gap-3 sm:gap-2">

                            <dl class="grid sm:grid-cols-5 gap-x-3">
                                <dt class="col-span-3 font-semibold text-gray-800 dark:text-neutral-200">From date:</dt>
                                <dd class="col-span-2 text-gray-500 dark:text-neutral-500">{{ $startDate }}</dd>
                            </dl>
                            <dl class="grid sm:grid-cols-5 gap-x-3">
                                <dt class="col-span-3 font-semibold text-gray-800 dark:text-neutral-200">To Date:</dt>
                                <dd class="col-span-2 text-gray-500 dark:text-neutral-500">{{ $endDate }}</dd>
                            </dl>
                        </div>
                        <!-- End Grid -->
                    </div>
                    <!-- Col -->
                </div>
                <!-- End Grid -->
                <div class="mb-16">
                    {{-- {{ $this->table }} --}}
                </div>


                <!-- Flex -->
                {{-- <div class="mt-8 flex sm:justify-end">
                <div class="w-full max-w-2xl sm:text-end space-y-2">
                    <!-- Grid -->
                    <div class="grid grid-cols-2 sm:grid-cols-1 gap-3 sm:gap-2">
                        <dl class="grid sm:grid-cols-5 gap-x-3">
                            <dt class="col-span-3 font-semibold text-gray-800 dark:text-neutral-200">Total Revenue:</dt>
                            <dd class="col-span-2 text-gray-500 dark:text-neutral-500">{{ dd($getRecord()) }}</dd>
                        </dl>

                        <dl class="grid sm:grid-cols-5 gap-x-3">
                            <dt class="col-span-3 font-semibold text-gray-800 dark:text-neutral-200">Total:</dt>
                            <dd class="col-span-2 text-gray-500 dark:text-neutral-500">$2750.00</dd>
                        </dl>

                        <dl class="grid sm:grid-cols-5 gap-x-3">
                            <dt class="col-span-3 font-semibold text-gray-800 dark:text-neutral-200">Tax:</dt>
                            <dd class="col-span-2 text-gray-500 dark:text-neutral-500">$39.00</dd>
                        </dl>

                        <dl class="grid sm:grid-cols-5 gap-x-3">
                            <dt class="col-span-3 font-semibold text-gray-800 dark:text-neutral-200">Amount paid:
                            </dt>
                            <dd class="col-span-2 text-gray-500 dark:text-neutral-500">$2789.00</dd>
                        </dl>

                        <dl class="grid sm:grid-cols-5 gap-x-3">
                            <dt class="col-span-3 font-semibold text-gray-800 dark:text-neutral-200">Due balance:
                            </dt>
                            <dd class="col-span-2 text-gray-500 dark:text-neutral-500">$0.00</dd>
                        </dl>
                    </div>
                    <!-- End Grid -->
                </div>
            </div> --}}
                <!-- End Flex -->



                <p class="mt-5 text-sm text-gray-500 dark:text-neutral-500">© 2024 suitesense.</p>
            </div>
            <!-- End Card -->


        </div>
    </div>
    <!-- End Invoice -->
</x-layouts.app>
