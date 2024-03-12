@props([
    'label' => '',
    'type' => '',
    'value' => '',
    'placeholder' => '',
    'name' => '',
    'text' => ''
])



<div>
    <label for="" class="block text-sm font-medium leading-6 text-gray-900">{{$label}}</label>
    <div class="relative mt-2 rounded-md shadow-sm" style="margin-top: 32px">
        <button type="{{$type}}" class="border hover:bg-blue-700 hover:text-gray-400 py-2 px-4 rounded" style="height: 40px; border: 1px solid #D1D5DA ">{{$text}}</button>
    </div>
</div>
