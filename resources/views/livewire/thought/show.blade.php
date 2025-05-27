<div class="static xl:relative flex flex-col xl:flex-row">
    <x-card class="w-full sm:w-2/3 xl:min-w-3/6 h-fit mx-auto mb-1 xl:mr-1 xl:mb-0 xl:sticky xl:top-5" shadow>
        <x-avatar image="https://picsum.photos/200/200" class="!w-24">  
            <x-slot:title class="text-3xl pl-2">
                {{ Str::of($user->name)->limit(20) }}
            </x-slot:title>
         
            <x-slot:subtitle class="text-gray-500 flex flex-col gap-1 mt-2 pl-2">
                <x-icon name="o-identification" label="{{ $user->slug }}" />
                <x-icon name="o-at-symbol" label="{{ $user->email }}" />
            </x-slot:subtitle>
        </x-avatar>
        <div class="mt-4">
            <x-button label="Visit profile" link="{{ route('users.show', $user->slug) }}" class="w-full btn-outline" />
        </div>
        <div class="mt-4 flex flex-col space-y-4">
            @foreach ($user->thoughts as $user_thought)
                <x-card title="{{ $user_thought->topic }}" subtitle="{{ $user_thought->slug }}" class="bg-base-200" shadow separator wire:key="{{ rand() }}">
                    <div class="flex flex-col space-y-2">
                        <div class="line-clamp-3 overflow-x-auto">
                            {{ $user_thought->content }}
                        </div>
                        <div class="overflow-x-auto">
                            <span>Tags:</span>
                            @forelse ($user_thought->tags as $tag)
                                <span wire:key="{{ rand() }}">
                                    <x-button label="{{ $tag }}" link="{{ route('thoughts.index','tags='.$tag) }}" class="btn-primary btn-xs" />
                                    @if (!$loop->last)
                                        ,
                                    @endif
                                </span>
                            @empty
                                <span>There are no tags</span>
                            @endforelse
                        </div>
                        <span class="text-neutral-content">
                            @if ($user_thought->open === 'Open')
                                <x-button label="{{ $user_thought->open }}" link="{{ route('thoughts.index','open='.strtolower($user_thought->open)) }}" class="btn-success btn-xs" />
                            @else
                                <x-button label="{{ $user_thought->open }}" link="{{ route('thoughts.index','open='.strtolower($user_thought->open)) }}" class="btn-error btn-xs" />
                            @endif
                        </span>
                        <div>
                            <x-button label="Read more" link="{{ route('thoughts.show', $user_thought->slug) }}" class="w-full btn-outline" />
                        </div>
                    </div>
                </x-card>
            @endforeach
        </div>
    </x-card>
    <div class="w-full sm:w-2/3 xl:w-full mx-auto mt-1 xl:ml-1 xl:mt-0 flex flex-col space-y-2 overflow-x-clip">
        <x-card title="{{ $thought->topic }}" subtitle="{{ $thought->slug }}" shadow separator wire:key="{{ rand() }}">
            <div class="flex flex-col space-y-2">
                <div>
                    {{ $thought->created_at->longRelativeDiffForHumans() }}
                </div>
                <div class="overflow-x-auto">
                    <span>Tags:</span>
                    @forelse ($thought->tags as $tag)
                        <span wire:key="{{ rand() }}">
                            <x-button label="{{ $tag }}" link="{{ route('thoughts.index','tags='.$tag) }}" class="btn-primary btn-xs" />
                            @if (!$loop->last)
                                ,
                            @endif
                        </span>
                    @empty
                        <span>There are no tags</span>
                    @endforelse
                </div>
                <span class="text-neutral-content">
                    @if ($thought->open === 'Open')
                        <x-button label="{{ $thought->open }}" link="{{ route('thoughts.index','open='.strtolower($thought->open)) }}" class="btn-success btn-xs" />
                    @else
                        <x-button label="{{ $thought->open }}" link="{{ route('thoughts.index','open='.strtolower($thought->open)) }}" class="btn-error btn-xs" />
                    @endif
                </span>
                <div class="">
                    {{ $thought->content }}
                </div>
                <div>
                    <span>Replies:</span>
                    <span>
                        {{ $thought->replies_count }}
                    </span>
                </div>
            </div>
        </x-card>
        @php
            $config = [
                'spellChecker' => true,
                'uploadImage' => false,
                'toolbar' => ['heading', 'bold', 'italic', 'strikethrough', 'code', 'quote', 'unordered-list', 'ordered-list', 'clean-block', 'link', 'table', 'horizontal-rule', '|', 'preview', 'side-by-side', 'fullscreen', '|', 'guide', '|', 'undo', 'redo'],
            ];
        @endphp
        {{-- comment form --}}
        @auth
            @livewire('reply.reply-for-thought', ['thought_id' => $thought->id], key(rand()))
        @endauth
        {{-- sort --}}
        @if ($thought->replies_count > 0)
            @livewire('reply.sort', ['thought' => $thought->id], key(rand()))
        @else
            <x-card title="There are no replies." subtitle="Sorry we can't find any replies for this thought." class="w-full h-fit" shadow></x-card>
        @endif
    </div>
</div>
