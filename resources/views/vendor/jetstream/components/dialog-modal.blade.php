@props(['id' => null, 'maxWidth' => null])

<x-jet-modal :id="$id" :maxWidth="$maxWidth" {{ $attributes }}>
    <div class="flex flex-row px-6 py-3 bg-gray-300 text-right font-bold text-gray-600">
        {{ $title }}
    </div>

    <div class="py-4 px-4">
        {{ $content }}
    </div>

    <div class="flex flex-row justify-end px-6 py-3 bg-gray-200 text-right">
        {{ $footer }}
    </div>
</x-jet-modal>
