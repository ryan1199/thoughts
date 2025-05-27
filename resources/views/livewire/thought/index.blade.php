<div class="flex flex-col space-y-2">
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
                class="whitespace-nowrap" />
            </div>
            <div class="overflow-x-auto">
                <x-radio
                label="Order by"
                :options="$available_columns"
                option-value="value"
                wire:model.live="order"
                class="whitespace-nowrap" />
            </div>
            <div class="overflow-x-auto">
                <x-radio
                label="Sort by"
                :options="$available_direction"
                option-value="value"
                wire:model.live="direction"
                class="whitespace-nowrap" />
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
    <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
        @forelse ($thoughts as $thought)
            <x-card title="{{ $thought->topic }}" subtitle="{{ $thought->slug }}" class="md:h-fit" shadow separator wire:key="{{ rand() }}">
                <div class="flex flex-col space-y-2">
                    <div class="line-clamp-3 overflow-x-auto">
                        {{ $thought->content }}
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
                    @if ($user_id == 0)
                        <div>
                            <span>By</span>
                            <span> {{ $thought->user->name }}</span>
                        </div>
                    @endif
                    <span class="text-xs text-neutral-content">
                        @if ($thought->open === 'Open')
                            <x-button label="{{ $thought->open }}" link="{{ route('thoughts.index','open='.strtolower($thought->open)) }}" class="btn-success btn-xs" />
                        @else
                            <x-button label="{{ $thought->open }}" link="{{ route('thoughts.index','open='.strtolower($thought->open)) }}" class="btn-error btn-xs" />
                        @endif
                    </span>
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
            <x-card title="There are no thoughts." subtitle="Sorry we can't find any thoughts for you." class="col-span-full" shadow></x-card>
        @endforelse
    </div>
    @if ($thoughts->hasPages())
        <div>
            {{ $thoughts->links(data: ['scrollTo' => false]) }}
        </div>
    @endif
</div>
