<div>
    <x-button wire:click="showCommentForm">
        Comment
        <x-badge value="{{ $comment_count }}" class="badge-neutral" />
    </x-button>
    @if ($show)
        <div class="flex flex-col space-y-2 mt-2">
            <x-markdown wire:model.live="comment" :config="$config" />
            <x-button label="Submit" wire:click="submit" class="self-end shadow-sm" spinner />
        </div>
    @endif
</div>
