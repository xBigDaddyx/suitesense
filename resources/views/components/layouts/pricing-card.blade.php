 <!-- Features -->
 <div class="overflow-auto">
     <div class="card-pricing">
         {{-- <!-- Title -->
         <div class="card-pricing-title">
             <h2>
                 {{ $cardTitle }}
             </h2>
         </div>
         <!-- End Title --> --}}

         <div class="card-pricing-body">
             <!-- Grid -->
             <div class="card-pricing-grid">
                 <div>
                     <!-- Card -->
                     <div class="card-pricing-body-child">
                         <h3>{{ $planName }}</h3>
                         <div class="card-pricing-child-subtitle">{{ $planDescription }}</div>

                         <div class="card-pricing-price">
                             <span class="price">{{ $planPrice }}</span>
                             <span class="decimal">.00</span>
                             <span class="period">/ month</span>
                         </div>

                         <div class="card-pricing-feature">
                             <!-- List -->
                             <ul>
                                 {{ $planFeatures }}

                             </ul>
                             <!-- End List -->
                         </div>

                         <div class="mt-5 gap-x-4 py-8 first:pt-0 last:pb-0">
                             <div class="flex items-center justify-center gap-x-4">
                                 {{ $planActions }}
                                 {{-- <button type="button"
                                     class="py-3 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none focus:outline-none focus:bg-gray-50 dark:bg-transparent dark:border-neutral-700 dark:text-neutral-300 dark:hover:bg-neutral-800 dark:focus:bg-neutral-800">Start
                                     free trial</button> --}}
                             </div>
                         </div>
                     </div>
                     <!-- End Card -->
                 </div>
             </div>
             <!-- End Grid -->




         </div>


     </div>
 </div>
 <!-- End Features -->
