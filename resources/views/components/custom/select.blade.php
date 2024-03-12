@props([
    'label' => '',
    'name' => '',
    'id' => '',
    'data_array' => '',
])

<div>
    <label for="" class="block text-sm font-medium leading-6 text-gray-900">{{$label}}</label>
    <div class="relative mt-2 rounded-md shadow-sm">
        <select name="{{$name}}"
               {{ $attributes->merge(['class' => 'bg-gray-50 shadow-lg border border-gray-800 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500']) }}
            id="{{$id}}"
        >
            @foreach($data_array as $data)
                <option value="{{$data->id}}">{{$data->name}}</option>
            @endforeach
        </select>
    </div>
</div>
