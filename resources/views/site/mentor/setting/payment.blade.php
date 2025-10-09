<div x-show="activeSection === 'payment'" x-transition class="bg-white p-6">
    <h3 class="text-xl font-semibold mb-4 border-b pb-2">Payment History</h3>

    <div class="overflow-x-auto">
        <table class="min-w-full text-sm text-left border rounded-lg overflow-hidden">
        <thead class="bg-gray-100 text-gray-700">
            <tr>
            <th class="px-4 py-2 border">Sr. No.</th>
            <th class="px-4 py-2 border">Paid to</th>
            <th class="px-4 py-2 border">Date</th>
            <th class="px-4 py-2 border">Amount</th>
            <th class="px-4 py-2 border">Payment status</th>
            <th class="px-4 py-2 border">Action</th>
            </tr>
        </thead>
        <tbody class="text-gray-700">
            <tr class="border-b">
            <td class="px-4 py-2">1.</td>
            <td class="px-4 py-2">Session1</td>
            <td class="px-4 py-2">24/04/2025</td>
            <td class="px-4 py-2">200</td>
            <td class="px-4 py-2">Paid</td>
            <td class="px-4 py-2">
                <button class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">
                View invoice
                </button>
            </td>
            </tr>
            <tr class="border-b">
            <td class="px-4 py-2">2.</td>
            <td class="px-4 py-2">Session2</td>
            <td class="px-4 py-2">26/04/2025</td>
            <td class="px-4 py-2">100</td>
            <td class="px-4 py-2">Paid</td>
            <td class="px-4 py-2">
                <button class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">
                View invoice
                </button>
            </td>
            </tr>
            <!-- Add more rows dynamically if needed -->
        </tbody>
        </table>
    </div>
    </div>