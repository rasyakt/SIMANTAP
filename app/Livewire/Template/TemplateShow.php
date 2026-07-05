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

        activity('template')
            ->causedBy(auth()->user())
            ->performedOn($this->template)
            ->event('viewed')
            ->withProperties([
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ])
            ->log("Melihat detail template {$this->template->nama}.");
    }

    public function render()
    {
        return view('livewire.template.template-show')
            ->title('Detail Template: ' . $this->template->nama);
    }
}
