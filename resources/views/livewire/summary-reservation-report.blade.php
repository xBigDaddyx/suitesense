<!-- Invoice -->
<div class="max-w-[85rem] px-4 sm:px-6 lg:px-8 mx-auto my-4 sm:my-10">
    <div class="mx-auto">
        <!-- Buttons -->
        <div class="mb-6 flex justify-end gap-x-3">
            <button wire:click="generatePdf"
                class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none"
                href="#">
                <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round">
                    <polyline points="6 9 6 2 18 2 18 9" />
                    <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2" />
                    <rect width="12" height="8" x="6" y="14" />
                </svg>
                Save
            </button>
            <a class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none"
                href="#">
                <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round">
                    <polyline points="6 9 6 2 18 2 18 9" />
                    <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2" />
                    <rect width="12" height="8" x="6" y="14" />
                </svg>
                Print
            </a>
        </div>
        <!-- End Buttons -->
        <!-- Card -->
        <div class="flex flex-col p-4 sm:p-10 bg-white shadow-md rounded-xl dark:bg-neutral-800 border">
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
                {{ $table }}
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
