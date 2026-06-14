@php
    use App\Models\ClientMaster;

    $done = ClientMaster::where('status', 'DONE')->count();
    $running = ClientMaster::where('status', 'RUNNING')->count();
    $holding = ClientMaster::where('status', 'HOLDING')->count();
    $cancel = ClientMaster::where('status', 'CANCEL')->count();
@endphp

<x-filament::page>


    <div class="mt-8 max-w-7xl mx-auto px-4">

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-8 max-w-7xl mx-auto px-10">

            <!-- DONE -->
            <a href="{{ url('/admin/client-masters?tableFilters[status][value]=DONE') }}">
                <div class="card-stat bg-green">
                    <div class="card-title">DONE</div>
                    <div class="card-value">{{ $done }}</div>
                </div>
            </a>

            <!-- RUNNING -->
            <a href="{{ url('/admin/client-masters?tableFilters[status][value]=RUNNING') }}">
                <div class="card-stat bg-blue">
                    <div class="card-title">RUNNING</div>
                    <div class="card-value">{{ $running }}</div>
                </div>
            </a>

            <!-- HOLDING -->
            <a href="{{ url('/admin/client-masters?tableFilters[status][value]=HOLDING') }}">
                <div class="card-stat bg-orange">
                    <div class="card-title">HOLDING</div>
                    <div class="card-value">{{ $holding }}</div>
                </div>
            </a>

            <!-- CANCEL -->
            <a href="{{ url('/admin/client-masters?tableFilters[status][value]=CANCEL') }}">
                <div class="card-stat bg-red">
                    <div class="card-title">CANCEL</div>
                    <div class="card-value">{{ $cancel }}</div>
                </div>
            </a>

        </div>

    </div>
</x-filament::page>