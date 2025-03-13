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
        {{-- <div class="mt-4">
            <x-button label="Visit profile" link="{{ route('users.show', $user->slug) }}" class="w-full btn-outline" />
        </div> --}}
        {{-- <div class="mt-4 flex flex-col space-y-4">
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
        </div> --}}
    </x-card>
    <div class="w-full 2xl:w-3/6 flex flex-col space-y-2 overflow-x-clip">
        <div class="w-full flex flex-col md:flex-row space-y-2 md:space-y-0 md:space-x-2">
            <div class="w-full flex flex-col space-y-2">
                <x-input label="Topic" wire:model.blur="topic" placeholder="The subject. example: population crisis" clearable />
                <x-input label="Content" wire:model.blur="content" placeholder="The user's thought" clearable />
                <x-input label="Tags" wire:model.blur="tags" placeholder="The tags. example: news,sports" clearable />
            </div>
            <div class="w-full flex flex-col space-y-2">
                <div class="overflow-x-auto">
                    <x-radio
                    label="Filter by"
                    :options="$available_open_options"
                    option-value="value"
                    wire:model.live="open"
                    class="" />
                </div>
                <div class="overflow-x-auto">
                    <x-radio
                    label="Order by"
                    :options="$available_columns"
                    option-value="value"
                    wire:model.live="order"
                    class="" />
                </div>
                <div class="overflow-x-auto">
                    <x-radio
                    label="Sort by"
                    :options="$available_direction"
                    option-value="value"
                    wire:model.live="direction"
                    class="" />
                </div>
            </div>
        </div>
        <x-range
            wire:model.live.debounce="items_in_page"
            min="1"
            max="100"
            step="1"
            label="Items per page: {{ $items_in_page }}"
            class="range-primary" />
        @forelse ($thoughts as $thought)
            <x-card title="{{ $thought->topic }}" subtitle="{{ $thought->slug }}" shadow separator wire:key="{{ rand() }}">
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
        @empty
            <x-card title="There are no thoughts." subtitle="Sorry we can't find any thoughts for this user." class="w-full h-fit" shadow></x-card>
        @endforelse
        @if ($thoughts->hasPages())
            <div>
                {{ $thoughts->links(data: ['scrollTo' => false]) }}
            </div>
        @endif
    </div>
</div>
