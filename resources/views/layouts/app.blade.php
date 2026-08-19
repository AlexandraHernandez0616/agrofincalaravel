<x-admin-layout :title="$title ?? null">
    @isset($header)
        <x-slot name="header">
            {{ $header }}
        </x-slot>
    @endisset
    @isset($actions)
        <x-slot name="actions">
            {{ $actions }}
        </x-slot>
    @endisset

    {{ $slot }}
</x-admin-layout>
