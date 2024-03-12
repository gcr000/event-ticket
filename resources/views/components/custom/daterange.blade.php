@props([
    'startDate' => '',
    'endDate' => '',
    'label' => '',
    ])

<div class="mt-2">
    <div class="flex shadow-sm sm:max-w-md">
        <x-custom.input label="Inizio" value="{{ $startDate }}" placeholder="{{$startDate}}" type="date"></x-custom.input>
        <x-custom.input label="Fine" value="{{ $endDate }}" placeholder="{{$endDate}}" type="date" class="ml-2"></x-custom.input>
    </div>
</div>
