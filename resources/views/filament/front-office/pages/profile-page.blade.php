<div class="min-h-screen flex flex-col items-center justify-start py-8 px-4">
    <!-- Profile Header -->
    <div class="w-full max-w-4xl bg-white shadow-lg rounded-3xl overflow-hidden">
        <div class="relative bg-teal-500 h-48 flex items-center justify-center">
            <!-- Company Logo -->
            <img src="{{ asset('storage/' . auth()->user()->latestHotel->logo_url) }}" alt="Company Logo" class="h-12" />
            <!-- User Avatar -->
            <div class="absolute inset-x-0 -bottom-12 flex justify-center">
                <img src="https://via.placeholder.com/150" alt="User Avatar"
                    class="w-24 h-24 rounded-full border-4 border-white shadow-md" />
            </div>
        </div>
        <div class="p-6 mt-12">
            <div class="text-center">
                <h1 class="text-2xl font-bold text-gray-900">John Doe</h1>
                <p class="text-sm text-gray-500">johndoe@example.com</p>
                <div class="mt-4">
                    <button class="px-4 py-2 rounded-lg text-sm font-medium primary hover:primary-hover shadow-md">
                        Edit Profile
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Profile Details -->
    <div class="w-full max-w-4xl space-y-6 mt-8">
        <!-- Personal Information -->
        <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow">
            <h2 class="text-lg font-semibold text-gray-700 mb-4">Personal Information</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-sm text-gray-500">Full Name</p>
                    <p class="font-medium text-gray-900">John Doe</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Date of Birth</p>
                    <p class="font-medium text-gray-900">January 15, 1990</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Gender</p>
                    <p class="font-medium text-gray-900">Male</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Phone Number</p>
                    <p class="font-medium text-gray-900">+1 234 567 890</p>
                </div>
            </div>
        </div>

        <!-- Subscription Details -->
        <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow">
            <h2 class="text-lg font-semibold text-gray-700 mb-4">Subscription Details</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-sm text-gray-500">Plan Name</p>
                    <p class="font-medium text-gray-900">Premium Plan</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Next Billing Date</p>
                    <p class="font-medium text-gray-900">January 15, 2025</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Status</p>
                    <span class="px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-700">
                        Active
                    </span>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Payment Method</p>
                    <p class="font-medium text-gray-900">Visa •••• 1234</p>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow">
            <h2 class="text-lg font-semibold text-gray-700 mb-4">Recent Activity</h2>
            <ul class="space-y-4 text-sm text-gray-600">
                <li class="flex items-start space-x-4">
                    <span class="w-2.5 h-2.5 rounded-full bg-teal-500 mt-2"></span>
                    <p>Logged in on December 1, 2024 at 10:45 AM</p>
                </li>
                <li class="flex items-start space-x-4">
                    <span class="w-2.5 h-2.5 rounded-full bg-teal-500 mt-2"></span>
                    <p>Updated profile details on November 28, 2024</p>
                </li>
                <li class="flex items-start space-x-4">
                    <span class="w-2.5 h-2.5 rounded-full bg-teal-500 mt-2"></span>
                    <p>Changed password on November 25, 2024</p>
                </li>
            </ul>
        </div>
    </div>
</div>
