<div class="w-full max-w-sm mx-auto bg-white shadow-lg rounded-lg border border-gray-200 overflow-hidden">
    <div class="p-6 bg-primary-500 text-white">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-semibold text-center">{{ $planName }}</h2>
            <div
                class="text-sm bg-white text-emerald-500 font-semibold py-1 px-3 rounded-lg hover:bg-gray-100 transition">
                {{ $planActions }}
            </div>

        </div>


        <p class="text-sm text-center mt-2">{{ $planDescription }}</p>

    </div>
    <div class="mt-4 text-center">
        <span class="text-2xl font-bold text-primary-500">{{ $planPrice }}</span>
        <span class="text-sm font-semibold text-gray-500">/month</span>
    </div>
    <div class="p-6">
        <ul class="space-y-3 text-gray-700">
            {{ $planFeatures }}

        </ul>
        <div class="flex items-center justify-center w-full mt-6 py-3 rounded-lg transition">
            {{ $subscribeAction }}
        </div>
    </div>
</div>
