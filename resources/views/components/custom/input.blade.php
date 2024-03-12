@props([
    'label' => '',
    'type' => 'text',
    'value' => '',
    'placeholder' => '',
    'name' => '',
])



<div>
    <label for="" class="block text-sm font-medium leading-6 text-gray-900">{{$label}}</label>
    <div class="relative mt-2 rounded-md shadow-sm">
        <input type="{{$type}}" name="{{$name}}" value="{{$value}}"
               {{ $attributes->merge(['class' => 'block w-full rounded-md border-0 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6']) }}
               placeholder="{{$placeholder}}"
        >
    </div>
</div>
