<div x-data="{ open: false }" x-show="open" @keydown.escape.window="open = false" style="display: none;">
    <div class="fixed inset-0 bg-black bg-opacity-50"></div>
    <div class="fixed inset-0 flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-xl font-bold mb-4">Reservation Details</h2>
            <p><strong>Title:</strong> {{ $reservation['title'] }}</p>
            <p><strong>Start:</strong> {{ \Carbon\Carbon::parse($reservation['start'])->toDayDateTimeString() }}</p>
            <p><strong>End:</strong> {{ \Carbon\Carbon::parse($reservation['end'])->toDayDateTimeString() }}</p>
            <button @click="open = false" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded">Close</button>
        </div>
    </div>
</div>
