@php
    $record = $getRecord();
    $progress = round($record->total_progress);
@endphp

<div class="flex items-center justify-between w-full text-white">

    <!-- LEFT -->
    <div class="flex flex-col">
        <div class="font-bold text-lg">
            {{ $record->brand_name }}
        </div>

        <div class="text-sm text-gray-400">
            {{ $record->client_name }}
        </div>

        <div class="text-xs text-gray-500">
            {{ $record->product_type }} - {{ $record->variant }}
        </div>
    </div>

    <!-- MIDDLE (DIVISION PROGRESS) -->
    <div class="flex gap-6 text-xs text-center">

        <div>F<br>{{ round($record->formula_progress) }}%</div>
        <div>L<br>{{ round($record->legal_progress) }}%</div>
        <div>D<br>{{ round($record->design_progress) }}%</div>
        <div>P<br>{{ round($record->purchasing_progress) }}%</div>
        <div>PR<br>{{ round($record->production_progress) }}%</div>

    </div>

    <!-- RIGHT (TOTAL PROGRESS) -->
    <div class="w-40">
        <div class="text-xs mb-1 text-right">{{ $progress }}%</div>

        <div class="w-full bg-gray-700 rounded h-2">
            <div class="bg-green-500 h-2 rounded" style="width: {{ $progress }}%"></div>
        </div>
    </div>

</div>