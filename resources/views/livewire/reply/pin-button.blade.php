<div>
    @if ($is_pinned == "Pinned")
        <x-button wire:click="togglePin" label="Pinned" icon="o-paper-clip" class="w-fit h-fit px-4 btn-circle btn-outline btn-sm" />
    @else
        <x-button wire:click="togglePin" label="Unpinned" icon="o-paper-clip" class="w-fit h-fit px-4 btn-circle btn-outline btn-sm" />
    @endif
</div>
