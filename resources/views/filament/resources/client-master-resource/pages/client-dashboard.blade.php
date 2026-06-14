<x-filament::page>

    <div class="grid grid-cols-2 gap-4">

        <div class="p-4 bg-gray-800 text-white rounded">
            <h2 class="text-lg font-bold">Client Info</h2>
            <p>Brand: {{ $record->brand_name }}</p>
            <p>Client: {{ $record->client_name }}</p>
            <p>Product: {{ $record->product_type }}</p>
            <p>Variant: {{ $record->variant }}</p>
        </div>

        <div class="p-4 bg-gray-900 text-white rounded">
            <h2 class="text-lg font-bold">TOTAL PROGRESS</h2>
            <p class="text-3xl">{{ round($record->total_progress) }}%</p>
        </div>

    </div>

    <div class="grid grid-cols-5 gap-4 mt-6">

        <div class="p-4 bg-yellow-500 text-black rounded">
            Formula<br>{{ round($record->formula_progress) }}%
        </div>

        <div class="p-4 bg-red-500 text-white rounded">
            Legal<br>{{ round($record->legal_progress) }}%
        </div>

        <div class="p-4 bg-blue-500 text-white rounded">
            Design<br>{{ round($record->design_progress) }}%
        </div>

        <div class="p-4 bg-purple-500 text-white rounded">
            Purchasing<br>{{ round($record->purchasing_progress) }}%
        </div>

        <div class="p-4 bg-green-500 text-white rounded">
            Production<br>{{ round($record->production_progress) }}%
        </div>

    </div>

</x-filament::page>