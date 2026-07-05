<?php

namespace App\Livewire\Template;

use App\Models\ItemTemplate;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class TemplateShow extends Component
{
    public ItemTemplate $template;

    public function mount(ItemTemplate $template): void
    {
        $this->template = $template->load(['kategori', 'items']);
    }

    public function render()
    {
        return view('livewire.template.template-show')
            ->title('Detail Template: ' . $this->template->nama);
    }
}
