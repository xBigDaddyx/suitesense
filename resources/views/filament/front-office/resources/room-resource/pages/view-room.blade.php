<x-filament::page>
    @php
        $room = $this->getRecord();
        $tenant = \Filament\Facades\Filament::getTenant();
    @endphp
    <div class="rounded-xl border shadow-md overflow-clip">
        <!-- Hero Section -->
        <div class="relative h-[500px] overflow-hidden bg-gray-900">
            <!-- Background Image -->
            <div class="absolute inset-0 bg-cover bg-center transform transition-transform duration-500 hover:scale-110"
                style="background-image: url('{{ $room->getFirstMediaUrl('room-photos', 'large') }}');">
            </div>
            <div class="absolute inset-0 bg-gradient-to-b from-gray-900/80 to-gray-900/30"></div>

            <!-- Logo -->
            <div class="absolute top-6 left-6 z-10">
                <img src="{{ asset('storage/' . auth()->user()->latestHotel->logo_url) }}" alt="Suite Sense Logo"
                    class="h-10">
            </div>

            <!-- Hero Content -->
            <div class="absolute inset-0 flex flex-col items-center justify-center text-center text-white px-6">
                <h1 class="text-5xl lg:text-6xl font-extrabold tracking-wide drop-shadow-lg">{{ $room->name }}</h1>
                <p class="mt-4 text-lg lg:text-2xl text-gray-300">{{ $room->roomType->name }}</p>
                {{-- <a href="#details"
                    class="mt-8 bg-teal-500 hover:bg-teal-600 text-white text-lg px-6 py-3 rounded-lg shadow-lg hover:shadow-xl transition">
                    Explore Details
                </a> --}}
            </div>
        </div>

        <!-- Content Section -->
        <div class="max-w-7xl mx-auto py-12 px-6 lg:px-12">
            <!-- Room Details & Booking -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16">
                <div>
                    <h2 class="text-4xl font-bold text-gray-800">Room Details</h2>
                    <p class="mt-6 text-gray-600 text-lg leading-relaxed">{{ $room->roomType->description }}</p>
                    <ul class="mt-10 space-y-8 text-gray-700 text-lg">
                        <li class="flex items-center">
                            <span class="text-teal-500 mr-4">
                                <x-tabler-bed class="w-10 h-10" />
                            </span>
                            <span>Room Type: <span class="font-semibold">{{ $room->roomType->name }}</span></span>
                        </li>
                        <li class="flex items-center">
                            <span class="text-teal-500 mr-4">
                                <x-tabler-cash class="w-10 h-10" />
                            </span>
                            <span>Price per night: <span
                                    class="font-semibold">${{ number_format($room->price, 2) }}</span></span>
                        </li>
                        <li class="flex items-center">
                            <span class="text-teal-500 mr-4">
                                <x-tabler-check class="w-10 h-10" />
                            </span>
                            <span>Availability:
                                <span
                                    class="{{ $room->state == 'available' ? 'bg-success-300 rounded-xl mx-2 text-white p-2 font-semibold text-sm' : 'bg-danger-300 rounded-xl mx-2 text-white p-2 font-semibold text-sm' }}">
                                    {{ $room->state == 'available' ? 'Available' : 'Not Available' }}
                                </span>
                            </span>
                        </li>
                    </ul>
                </div>

                <!-- Gallery Section -->
                <div>
                    <h2 class="text-3xl font-bold text-gray-800 mb-6">Gallery</h2>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                        @foreach ($room->getMedia('room-photos') as $photo)
                            <div class="relative group cursor-pointer" onclick="openModal('{{ $photo->getUrl() }}')">
                                <img src="{{ $photo->getUrl('preview') }}" alt="Room Image"
                                    class="rounded-lg shadow-lg transition-transform duration-300 group-hover:scale-105">
                                <div
                                    class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 flex items-center justify-center transition-opacity duration-300">
                                    <x-tabler-eye class="w-8 h-8 text-white" />
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>



    <!-- Modal for Gallery -->
    <div id="galleryModal" class="fixed inset-0 bg-black/80 flex items-center justify-center hidden z-50">
        <div class="relative">
            <img id="modalImage" src="" alt="Gallery Image" class="rounded-lg shadow-lg max-h-[80vh]">
            <button class="absolute top-4 right-4 bg-white text-black rounded-full p-2 hover:bg-gray-200"
                onclick="closeModal()">
                <x-tabler-x class="w-6 h-6" />
            </button>
        </div>


        <script>
            function openModal(imageUrl) {
                document.getElementById('modalImage').src = imageUrl;
                document.getElementById('galleryModal').classList.remove('hidden');
            }

            function closeModal() {
                document.getElementById('galleryModal').classList.add('hidden');
            }
        </script>
</x-filament::page>
