<div class="relative flex flex-col 2xl:flex-row space-y-2 2xl:space-y-0">
    <x-card class="w-full 2xl:w-2/6 h-fit mr-2 2xl:sticky 2xl:top-0" shadow>
        <x-avatar image="https://picsum.photos/200/200" class="!w-24">
            <x-slot:title class="text-3xl pl-2">
                {{ $user->name }}
            </x-slot:title>
         
            <x-slot:subtitle class="text-gray-500 flex flex-col gap-1 mt-2 pl-2">
                <x-icon name="o-identification" label="{{ $user->slug }}" />
                <x-icon name="o-at-symbol" label="{{ $user->email }}" />
            </x-slot:subtitle>
        </x-avatar>
        {{-- messages --}}
    </x-card>
    <div class="w-full 2xl:w-3/6 flex flex-col space-y-2 overflow-x-clip">
        @livewire('thought.index', ['user_id' => (int) $user->id], key(rand()))
    </div>
</div>
