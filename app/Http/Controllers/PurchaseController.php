<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    public function index()
    {
        $purchases = Purchase::with('supplier')
            ->orderByDesc('purchase_date')
            ->orderByDesc('id')
            ->paginate(15);

        return view('purchases.index', compact('purchases'));
    }

    public function create()
    {
        $suppliers = Supplier::orderBy('name')->get();
        $materials = Material::orderBy('name')->get();
        return view('purchases.create', compact('suppliers', 'materials'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'supplier_id' => ['required', 'exists:suppliers,id'],
            'purchase_date' => ['required', 'date'],
            'reference_no' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],

            'items' => ['required', 'array', 'min:1'],
            'items.*.material_id' => ['required', 'exists:materials,id'],
            'items.*.qty' => ['required', 'numeric', 'min:0.001'],
            'items.*.unit_price' => ['nullable', 'numeric', 'min:0'],
        ]);

        DB::transaction(function () use ($data) {
            $purchase = Purchase::create([
                'supplier_id' => $data['supplier_id'],
                'purchase_date' => $data['purchase_date'],
                'reference_no' => $data['reference_no'] ?? null,
                'notes' => $data['notes'] ?? null,
                'created_by' => auth()->id(),
                'total_amount' => 0,
            ]);

            $total = 0;
            foreach ($data['items'] as $item) {
                $qty = (float) $item['qty'];
                $unitPrice = (float) ($item['unit_price'] ?? 0);
                $lineTotal = $qty * $unitPrice;

                PurchaseItem::create([
                    'purchase_id' => $purchase->id,
                    'material_id' => $item['material_id'],
                    'qty' => $qty,
                    'unit_price' => $unitPrice,
                    'line_total' => $lineTotal,
                ]);

                // Optional: auto-increase material stock when a purchase is recorded.
                $material = Material::find($item['material_id']);
                if ($material) {
                    $material->increment('stock', $qty);
                }

                $total += $lineTotal;
            }

            $purchase->update(['total_amount' => $total]);
        });

        return redirect()->route('purchases.index')->with('success', 'Purchase recorded and stock updated.');
    }

    public function show(Purchase $purchase)
    {
        $purchase->load(['supplier', 'items.material']);
        return view('purchases.show', compact('purchase'));
    }

    public function destroy(Purchase $purchase)
    {
        // NOTE: We are NOT rolling back stock here to avoid accidental negative stock.
        // If you need it, add a dedicated "void purchase" flow with checks.
        $purchase->delete();
        return redirect()->route('purchases.index')->with('success', 'Purchase deleted.');
    }
}
