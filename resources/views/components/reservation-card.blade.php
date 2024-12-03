 <!-- Individual Reservation Card -->
 <article class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden" role="listitem">
     <!-- Status Banner -->
     <div class="bg-green-600 text-white px-4 py-2 flex items-center justify-between" role="status">
         <span class="font-semibold">Confirmed</span>
         <span class="text-sm">ID: #12345</span>
     </div>

     <!-- Guest Information Section -->
     <section class="p-6 border-b dark:border-gray-700" aria-label="Guest Information">
         <div class="space-y-4">
             <h2 class="text-2xl font-bold text-gray-800 dark:text-white">John Doe</h2>
             <div class="space-y-2 text-gray-600 dark:text-gray-300">
                 <p class="flex items-center gap-2">
                     <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                             d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                     </svg>
                     <span>john.doe@example.com</span>
                 </p>
                 <p class="flex items-center gap-2">
                     <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                             d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                     </svg>
                     <span>+1 (555) 123-4567</span>
                 </p>
             </div>
         </div>
     </section>

     <!-- Reservation Details Section -->
     <section class="p-6 space-y-4" aria-label="Reservation Details">
         <div class="grid grid-cols-1 gap-4">
             <div class="space-y-2">
                 <div class="flex items-center gap-2 text-gray-600 dark:text-gray-300">
                     <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                             d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                     </svg>
                     <span>Check-in: Dec 15, 2024</span>
                 </div>
                 <div class="flex items-center gap-2 text-gray-600 dark:text-gray-300">
                     <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                             d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                     </svg>
                     <span>Check-out: Dec 20, 2024</span>
                 </div>
             </div>
             <div class="space-y-2 text-gray-600 dark:text-gray-300">
                 <p>Room: Deluxe Suite</p>
                 <p>Guests: 2 Adults, 1 Child</p>
             </div>
         </div>
     </section>

     <!-- Pricing Section -->
     <section class="bg-gray-50 dark:bg-gray-700 p-6 space-y-4" aria-label="Pricing Details">
         <div class="flex justify-between items-center">
             <span class="text-gray-600 dark:text-gray-300">Total Amount</span>
             <span class="text-xl font-bold text-gray-800 dark:text-white">$1,200.00</span>
         </div>
         <div class="space-y-2 text-sm text-gray-600 dark:text-gray-300">
             <p class="flex justify-between">
                 <span>Room Rate (5 nights)</span>
                 <span>$1,000.00</span>
             </p>
             <p class="flex justify-between">
                 <span>Taxes & Fees</span>
                 <span>$200.00</span>
             </p>
         </div>
         <div class="pt-4 border-t border-gray-200 dark:border-gray-600">
             <p class="flex items-center gap-2 text-green-600 dark:text-green-400">
                 <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                     <path fill-rule="evenodd"
                         d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                         clip-rule="evenodd" />
                 </svg>
                 <span>Paid in Full</span>
             </p>
         </div>
     </section>

     <!-- Action Buttons -->
     <section class="p-6 space-y-3" aria-label="Reservation Actions">
         <button
             class="w-full bg-blue-600 hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 text-white font-semibold py-2 px-4 rounded-lg transition-colors"
             aria-label="Modify Reservation">
             Modify Reservation
         </button>
         <button
             class="w-full bg-gray-200 dark:bg-gray-600 hover:bg-gray-300 dark:hover:bg-gray-500 focus:ring-2 focus:ring-gray-400 focus:ring-offset-2 text-gray-800 dark:text-white font-semibold py-2 px-4 rounded-lg transition-colors"
             aria-label="Download Details">
             Download Details
         </button>
     </section>
 </article>
