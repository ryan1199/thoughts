<div class="relative flex flex-row">
    <x-card class="w-1/3 h-fit mr-2 sticky top-0" shadow>
        <x-avatar image="https://picsum.photos/200/200" class="!w-24">
            <x-slot:title class="text-3xl pl-2">
                {{ $user->name }}
            </x-slot:title>
         
            <x-slot:subtitle class="text-gray-500 flex flex-col gap-1 mt-2 pl-2">
                <x-icon name="o-identification" label="{{ $user->slug }}" />
                <x-icon name="o-at-symbol" label="{{ $user->email }}" />
            </x-slot:subtitle>
        </x-avatar>
        <div class="mt-4">
            <x-button label="Visit profile" link="{{ route('thoughts.show', $thought->slug) }}" class="w-full btn-outline" />
        </div>
        <div class="mt-4 flex flex-col space-y-4">
            @foreach ($user->thoughts as $user_thought)
                <x-card title="{{ $user_thought->topic }}" subtitle="{{ $user_thought->slug }}" class="bg-base-200" shadow separator wire:key="{{ rand() }}">
                    <div class="flex flex-col space-y-2">
                        <div class="line-clamp-3 overflow-x-scroll">
                            {{ $user_thought->content }}
                        </div>
                        <div class="overflow-x-auto">
                            <span>Tags:</span>
                            @forelse ($user_thought->tags as $tag)
                                <span wire:key="{{ rand() }}">
                                    @if ($loop->last)
                                        <x-badge value="{{ $tag }}" class="badge-primary" />
                                    @else
                                        <x-badge value="{{ $tag }}" class="badge-primary" />,
                                    @endif
                                </span>
                            @empty
                                <span>There are no tags</span>
                            @endforelse
                        </div>
                        @if ($user_thought->open === 'Open')
                            <span><x-badge value="{{ $user_thought->open }}" class="badge-success text-neutral-content" /></span>
                        @else
                            <span><x-badge value="{{ $user_thought->open }}" class="badge-error text-neutral" /></span>
                        @endif
                        <div>
                            <x-button label="Read more" link="{{ route('thoughts.show', $user_thought->slug) }}" class="w-full btn-outline" />
                        </div>
                    </div>
                </x-card>
            @endforeach
        </div>
    </x-card>
    <x-card title="{{ $thought->topic }}" subtitle="{{ $thought->slug }}" class="w-full" shadow separator wire:key="{{ rand() }}">
        <div class="flex flex-col space-y-2">
            <div>
                {{ $thought->created_at->longRelativeDiffForHumans() }}
            </div>
            <div class="overflow-x-auto">
                <span>Tags:</span>
                @forelse ($thought->tags as $tag)
                    <span wire:key="{{ rand() }}">
                        @if ($loop->last)
                            <x-badge value="{{ $tag }}" class="badge-primary" />
                        @else
                            <x-badge value="{{ $tag }}" class="badge-primary" />,
                        @endif
                    </span>
                @empty
                    <span>There are no tags</span>
                @endforelse
            </div>
            @if ($thought->open === 'Open')
                <span><x-badge value="{{ $thought->open }}" class="badge-success text-neutral-content" /></span>
            @else
                <span><x-badge value="{{ $thought->open }}" class="badge-error text-neutral" /></span>
            @endif
            <div class="">
                {{ $thought->content }}
            </div>
            <div>
                <span>Replies:</span>
                <span>
                    {{ $thought->replies_count }}
                </span>
            </div>
            <div>
                <x-button label="Read more" link="{{ route('thoughts.show', $thought->slug) }}" class="w-full btn-outline" />
            </div>
        </div>
    </x-card>
</div>
