<div class="flex flex-col space-y-2">
    @foreach ($replies as $reply)
        <div class="max-w-full flex flex-col space-y-2 border-primary border-l-2 rounded-md overflow-x-auto">
            <div class="w-full min-w-max">
                <x-card title="" subtitle="{{ $reply->slug }}" shadow separator wire:key="{{ rand() }}">
                    <div class="flex flex-col space-y-2">
                        <div class="overflow-x-auto">
                            {{ $reply->content }}
                        </div>
                        <div>
                            <x-button label="By {{ $reply->user->name }}" link="{{ route('users.show', $reply->user->slug) }}" class="w-fit btn-outline" />
                        </div>
                        {{-- piined/unpinned button --}}
                        <div class="w-full flex justify-end">
                            @if ($reply->pinned == "Pinned")
                                <x-button label="Pinned" link="{{ route('users.show', $reply->user->slug) }}" class="w-fit btn-outline" />
                            @else
                                <x-button label="Unpinned" link="{{ route('users.show', $reply->user->slug) }}" class="w-fit btn-outline" />
                            @endif
                        </div>
                        <x-textarea label="Biography" wire:model="bio" placeholder="Inline" rows="5" inline />
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
