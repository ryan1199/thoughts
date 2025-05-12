<div class="flex flex-col space-y-2">
    <x-card  shadow>
        <x-select label="Sort By" wire:model.live="sort_by" :options="$sort_by_options" icon="o-user" />
        <div class="my-1"></div>
        <x-select label="Sort Order" wire:model.live="sort_order" :options="$sort_order_options" icon="o-user" />
    </x-card>
    @livewire('reply.index', ['thought' => $thought->id, 'replied_reply' => null, 'position_of_color_for_border' => 0, 'sort_by' => $sort_by, 'sort_order' => $sort_order], key(rand()))
</div>
