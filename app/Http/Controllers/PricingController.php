<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\PricingCalculatorService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PricingController extends Controller
{
    protected $pricingCalculator;
    
    public function __construct(PricingCalculatorService $pricingCalculator)
    {
        $this->pricingCalculator = $pricingCalculator;
    }
    
    public function index(Request $request)
    {
        $query = Product::with(['category', 'activePricing'])
            ->active();
            
        // Filters
        if ($request->has('mode')) {
            $query->where('mode_harga', $request->mode);
        }
        
        if ($request->has('category')) {
            $query->where('kategori', $request->category);
        }
        
        if ($request->has('search')) {
            $query->where('nama_produk', 'like', "%{$request->search}%");
        }
        
        $products = $query->paginate(20)->withQueryString();
        
        // Calculate current prices
        $products->getCollection()->transform(function($product) {
            $product->current_prices = [
                'umum' => $product->getCurrentPrice('umum'),
                'anggota' => $product->getCurrentPrice('anggota'),
                'karyawan' => $product->getCurrentPrice('karyawan'),
            ];
            return $product;
        });
        
        return Inertia::render('Pricing/Index', [
            'products' => $products,
            'filters' => $request->only(['mode', 'category', 'search'])
        ]);
    }
    
    public function show(Product $product)
    {
        $product->load(['category', 'pricings' => function($query) {
            $query->orderBy('effective_date', 'desc');
        }]);
        
        $priceCalculations = [
            'umum' => $this->pricingCalculator->calculateProductPrice($product, 'umum'),
            'anggota' => $this->pricingCalculator->calculateProductPrice($product, 'anggota'),
            'karyawan' => $this->pricingCalculator->calculateProductPrice($product, 'karyawan'),
        ];
        
        return Inertia::render('Pricing/Show', [
            'product' => $product,
            'calculations' => $priceCalculations
        ]);
    }
    
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'mode_harga' => 'required|in:auto,manual',
            'pricing_mode' => 'required|in:global,individual',
            'markup_override' => 'nullable|numeric|min:0|max:1000',
            'minimum_price' => 'nullable|numeric|min:0',
            'maximum_price' => 'nullable|numeric|min:0',
            
            // Manual prices
            'harga_umum' => 'nullable|numeric|min:0',
            'harga_anggota' => 'nullable|numeric|min:0',
            'harga_karyawan' => 'nullable|numeric|min:0',
        ]);
        
        // Update product
        $product->update($validated);
        
        // Log the change
        PricingChangeLog::create([
            'action' => 'UPDATE',
            'product_id' => $product->id_produk,
            'mode' => $validated['mode_harga'],
            'old_value' => $product->getOriginal(),
            'new_value' => $validated,
            'changed_by' => auth()->user()->name,
        ]);
        
        return redirect()->back()->with('success', 'Product pricing updated successfully.');
    }
    
    public function generatePricing(Request $request)
    {
        $request->validate([
            'effective_date' => 'required|date|after:today',
            'customer_types' => 'required|array',
            'customer_types.*' => 'in:umum,anggota,karyawan'
        ]);
        
        $products = Product::active()->get();
        $generatedCount = 0;
        
        foreach ($products as $product) {
            foreach ($request->customer_types as $customerType) {
                $calculation = $this->pricingCalculator->calculateProductPrice($product, $customerType);
                
                ProductPricing::create([
                    'product_id' => $product->id_produk,
                    'pricing_mode' => $product->mode_harga,
                    'customer_type' => $customerType,
                    'cost_price' => $calculation['cost_price'],
                    'selling_price' => $calculation['selling_price'],
                    'markup_percent' => $calculation['markup_percent'],
                    'markup_amount' => $calculation['markup_amount'],
                    'overhead_percent' => $calculation['overhead_percent'],
                    'overhead_amount' => $calculation['overhead_amount'],
                    'tax_percent' => $calculation['tax_percent'],
                    'tax_amount' => $calculation['tax_amount'],
                    'effective_date' => $request->effective_date,
                    'created_by' => auth()->user()->name,
                ]);
                
                $generatedCount++;
            }
        }
        
        return response()->json([
            'message' => "Generated {$generatedCount} pricing records",
            'count' => $generatedCount
        ]);
    }
}