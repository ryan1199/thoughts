<div class="flex flex-col space-y-2">
    @foreach ($replies as $reply)
        <div class="max-w-full flex flex-col space-y-2 border-primary border-l-2 rounded-md overflow-x-auto">
            <div class="w-full min-w-max">
                <x-card title="{{ $reply->topic }}" subtitle="{{ $reply->slug }}" shadow separator wire:key="{{ rand() }}">
                    <div class="flex flex-col space-y-2">
                        <div class="overflow-x-auto">
                            {{ $reply->content }}
                        </div>
                        <div>
                            <span>By</span>
                            <span> {{ $reply->user->name }}</span>
                            {{-- button to visit profile --}}
                        </div>
                        {{-- piined/unpinned button --}}
                    </div>
                </x-card>
            </div>
            @if ($reply->replied === 'Replied')
                <div class="ml-2">
                    @livewire('reply.index', ['thought' => $thought->id, 'replied_reply' => $reply->slug], key(rand()))
                </div>
            @endif
        </div>
    @endforeach
</div>
