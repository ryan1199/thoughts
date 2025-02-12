<div class="flex flex-col space-y-2">
    <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
        <x-input label="Topic" wire:model="topic" placeholder="The subject. example: population crisis" clearable />
        <x-input label="Content" wire:model="content" placeholder="The user's thought" clearable />
        <x-input label="Tags" wire:model="tags" placeholder="The tags. example: news,sports" clearable />
        <x-radio
        :options="$available_open_options"
        option-value="value"
        wire:model="open"
        hint="Choose wisely" />
    </div>
    @forelse ($thoughts as $thought)
        <x-card title="{{ $thought->topic }}" subtitle="{{ $thought->slug }}" shadow separator>
            <div>
                {{ $thought->content }}
            </div>
            <div class="flex flex-row space-x-2">
                <span>Tags:</span>
                @forelse ($thought->tags as $tag)
                    @if ($loop->last)
                        <span>{{ $tag }} </span>
                    @else
                        <span>{{ $tag }}, </span>
                    @endif
                @empty
                    <span>There are no tags</span>
                @endforelse
            </div>
            <div>
                <span>By</span>
                <span> {{ $thought->user->name }}</span>
            </div>
        </x-card>
    @empty
        <x-card title="There are no thoughts." subtitle="Sorry we can't find any thoughts for you." shadow></x-card>
    @endforelse
    @if ($thoughts->hasPages())
        <div>
            {{ $thoughts->links(data: ['scrollTo' => false]) }}
        </div>
    @endif
</div>
