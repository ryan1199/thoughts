<div>
    <x-button wire:click="showEditor">
        Edit Comment
    </x-button>
    @if ($show)
        <div class="flex flex-col space-y-2 mt-2">
            <x-markdown wire:model.live="comment" :config="$config" />
            <x-button label="Save" wire:click="update" class="self-end shadow-sm" spinner />
        </div>
    @endif
</div>
