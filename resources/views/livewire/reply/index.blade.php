<div class="flex flex-col space-y-2">
    @foreach ($replies as $reply)
        <div class="w-full h-fit p-1 flex flex-col space-y-2 bg-base-100 {{ 'border-'.$colors_of_border[$position_of_color_for_border].'-500' }} border-2 card shadow-sm" wire:key="{{ rand() }}">
            <div class="card w-full shadow-sm">
                <div class="card-body">
                    <div class="w-full h-1 flex flex-row justify-between items-center">
                        <span class="font-normal text-sm">{{ $reply->slug }}</span>
                        {{-- piined/unpinned button --}}
                        {{-- new component --}}
                        <div class="w-full flex justify-end">
                            @if ($reply->pinned == "Pinned")
                                <x-button label="Pinned" icon="o-paper-clip" link="{{ route('users.show', $reply->user->slug) }}" class="w-fit h-fit px-4 btn-circle btn-outline" />
                            @else
                                <x-button label="Unpinned" icon="o-paper-clip" link="{{ route('users.show', $reply->user->slug) }}" class="w-fit h-fit px-4 btn-circle btn-outline" />
                            @endif
                        </div>
                    </div>
                    <div class="divider"></div>
                    <div class="flex flex-col space-y-2">
                        <div>
                            {!! $reply->content['markdown_content'] !!}
                        </div>
                        @if ($reply->edited_contents !== null)
                            <x-collapse>
                                <x-slot:heading>
                                    See changes
                                </x-slot:heading>
                                <x-slot:content>
                                    <div class="flex flex-col space-y-2">
                                        @foreach ($reply->edited_contents as $edited_content)
                                            <x-icon name="o-calendar" label="{{ $edited_content['updated_at']->longRelativeDiffForHumans() }}" />
                                            <div>{!! $edited_content['markdown_content'] !!}</div>
                                        @endforeach
                                    </div>
                                </x-slot:content>
                            </x-collapse>
                        @endif
                        @auth
                            @livewire('reply.edit', ['reply_id' => $reply->id], key(rand()))
                        @endauth
                        <div>
                            <x-button link="{{ route('users.show', $reply->user->slug) }}" class="w-fit h-fit p-1 btn-circle btn-outline flex flex-row space-x-2 items-center">
                                <div class="avatar">
                                    <div class="w-10 rounded-full">
                                        <img src="https://picsum.photos/200/200" alt="{{ 'profile of '.$reply->user->name }}">
                                    </div>
                                </div>
                                <span class="w-fit max-w-32 overflow-hidden whitespace-nowrap text-ellipsis">{{ $reply->user->name }}</span>
                            </x-button>
                        </div>
                        @auth
                            @livewire('reply.reply-for-reply', ['reply_id' => $reply->id], key(rand()))
                        @endauth
                    </div>
                </div>
            </div>
            @if ($reply->replied === 'Replied')
                @livewire('reply.index', ['thought' => $thought->id, 'replied_reply' => $reply->slug, 'position_of_color_for_border' => $position_of_color_for_border, 'sort_by' => $sort_by, 'sort_order' => $sort_order], key(rand()))
            @endif
        </div>
        @php
            $position_of_color_for_border++;
        @endphp
    @endforeach
</div>
