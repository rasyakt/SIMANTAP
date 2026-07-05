<?php

namespace App\Livewire\Dashboard;

use App\Models\Item;
use App\Models\Stock;
use App\Models\RepairHistory;
use App\Models\Location;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

#[Layout('layouts.app')]
#[Title('Dashboard')]
class Index extends Component
{
    #[Url(as: 'lokasi', history: true)]
    public string $filterLokasi = '';

    public function mount(): void
    {
        $this->authorize('dashboard.view');
    }

    public function render()
    {
        $user = auth()->user();
        $isSuperAdmin = $user->hasRole('Super Admin');
        $userLocationIds = [];

        $itemsQuery = Item::query();
        $stocksQuery = Stock::query();

        if (!$isSuperAdmin) {
            $userLocationIds = $user->locations()->pluck('locations.id')->toArray();
            if (!empty($userLocationIds)) {
                $itemsQuery->whereIn('lokasi_id', $userLocationIds);
                $stocksQuery->whereIn('lokasi_id', $userLocationIds);
            }
        } elseif ($this->filterLokasi) {
            $itemsQuery->where('lokasi_id', $this->filterLokasi);
            $stocksQuery->where('lokasi_id', $this->filterLokasi);
        }

        $totalItems = (clone $itemsQuery)->count();
        $totalStocks = (clone $stocksQuery)->count();

        $itemsByKondisi = (clone $itemsQuery)
            ->select('kondisi', DB::raw('count(*) as total'))
            ->whereNotNull('kondisi')
            ->groupBy('kondisi')
            ->pluck('total', 'kondisi');

        $lokasiIdsWithItems = (clone $itemsQuery)
            ->select('lokasi_id')
            ->whereNotNull('lokasi_id')
            ->groupBy('lokasi_id')
            ->pluck('lokasi_id');

        $itemsByLokasiCollection = collect();
        if ($lokasiIdsWithItems->isNotEmpty()) {
            $lokasiNama = Location::whereIn('id', $lokasiIdsWithItems)
                ->where('is_active', true)
                ->pluck('nama', 'id');

            $itemsByLokasiCollection = $lokasiIdsWithItems
                ->mapWithKeys(fn($id) => [$id => $id])
                ->map(function ($id) use ($itemsQuery, $lokasiNama) {
                    $count = (clone $itemsQuery)->where('lokasi_id', $id)->count();
                    return [
                        'nama' => $lokasiNama[$id] ?? 'Tanpa Lokasi',
                        'total' => $count,
                    ];
                })
                ->sortByDesc('total')
                ->values();
        }

        $itemsPerluPerhatian = (clone $itemsQuery)
            ->whereIn('kondisi', ['Rusak Ringan', 'Rusak Berat'])
            ->whereDoesntHave('repairHistories', fn($q) => $q->whereNotNull('tanggal_selesai'))
            ->with('lokasi', 'kategori')
            ->limit(10)
            ->get();

        $lowStockItems = (clone $stocksQuery)
            ->whereColumn('jumlah_stok', '<=', 'ambang_batas_minimum')
            ->with('lokasi', 'kategori')
            ->limit(10)
            ->get();

        $recentItemsQuery = Item::with('creator', 'lokasi');
        $recentRepairsQuery = RepairHistory::with('item', 'item.lokasi', 'item.kategori');

        if (!$isSuperAdmin && !empty($userLocationIds)) {
            $recentItemsQuery->whereIn('lokasi_id', $userLocationIds);
            $recentRepairsQuery->whereHas('item', fn($q) => $q->whereIn('lokasi_id', $userLocationIds));
        } elseif ($this->filterLokasi) {
            $recentItemsQuery->where('lokasi_id', $this->filterLokasi);
            $recentRepairsQuery->whereHas('item', fn($q) => $q->where('lokasi_id', $this->filterLokasi));
        }

        $recentItems = $recentItemsQuery->latest()->limit(5)->get();
        $recentRepairs = $recentRepairsQuery->latest()->limit(5)->get();

        $locations = Location::where('is_active', true)->orderBy('nama')->get();

        return view('livewire.dashboard.index', compact(
            'totalItems',
            'totalStocks',
            'itemsByKondisi',
            'itemsByLokasiCollection',
            'itemsPerluPerhatian',
            'lowStockItems',
            'recentItems',
            'recentRepairs',
            'locations',
            'isSuperAdmin',
        ));
    }
}
