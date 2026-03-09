<?php

namespace App\Services;

use App\Models\Product;
use App\Models\MarkupConfig;
use Carbon\Carbon;

class PricingCalculatorService
{
    protected $config;
    
    public function __construct()
    {
        $this->config = MarkupConfig::first() ?? new MarkupConfig();
    }

    public function calculateProductPrice(Product $product, string $customerType, string $mode = null)
    {
        $mode = $mode ?? $product->mode_harga;
        
        if ($mode === 'manual') {
            return $this->calculateManualPrice($product, $customerType);
        }
        
        return $this->calculateAutoPrice($product, $customerType);
    }

    protected function calculateAutoPrice(Product $product, string $customerType)
    {
        $costPrice = (float) $product->harga_beli;
        
        // Get markup percentage
        $markupPercent = $product->markup_override ?? 
                        $this->getMarkupPercentage($customerType);
        
        // Calculate base price
        $markupAmount = $costPrice * ($markupPercent / 100);
        $basePrice = $costPrice + $markupAmount;
        
        // Apply overhead
        $overheadAmount = $basePrice * ($this->config->persen_overhead / 100);
        $priceBeforeTax = $basePrice + $overheadAmount;
        
        // Apply tax
        $taxAmount = $priceBeforeTax * ($this->config->pajak_persen / 100);
        $finalPrice = $priceBeforeTax + $taxAmount;
        
        // Apply rounding
        $finalPrice = $this->applyRounding($finalPrice);
        
        // Apply min/max constraints
        if ($product->minimum_price && $finalPrice < $product->minimum_price) {
            $finalPrice = $product->minimum_price;
        }
        
        if ($product->maximum_price && $finalPrice > $product->maximum_price) {
            $finalPrice = $product->maximum_price;
        }
        
        return [
            'mode' => 'auto',
            'cost_price' => $costPrice,
            'selling_price' => $finalPrice,
            'markup_percent' => $markupPercent,
            'markup_amount' => $markupAmount,
            'overhead_percent' => $this->config->persen_overhead,
            'overhead_amount' => $overheadAmount,
            'tax_percent' => $this->config->pajak_persen,
            'tax_amount' => $taxAmount,
            'price_before_tax' => $priceBeforeTax,
        ];
    }

    protected function calculateManualPrice(Product $product, string $customerType)
    {
        $costPrice = (float) $product->harga_beli;
        
        // Get manual price
        $manualPrice = match($customerType) {
            'anggota' => (float) $product->harga_anggota,
            'karyawan' => (float) $product->harga_karyawan,
            default => (float) $product->harga_umum
        };
        
        // Calculate markup from manual price
        $markupAmount = $manualPrice - $costPrice;
        $markupPercent = $costPrice > 0 ? ($markupAmount / $costPrice) * 100 : 0;
        
        // Apply overhead
        $overheadAmount = $manualPrice * ($this->config->persen_overhead / 100);
        $priceBeforeTax = $manualPrice + $overheadAmount;
        
        // Apply tax
        $taxAmount = $priceBeforeTax * ($this->config->pajak_persen / 100);
        $finalPrice = $priceBeforeTax + $taxAmount;
        
        // Apply rounding
        $finalPrice = $this->applyRounding($finalPrice);
        
        return [
            'mode' => 'manual',
            'cost_price' => $costPrice,
            'selling_price' => $finalPrice,
            'markup_percent' => $markupPercent,
            'markup_amount' => $markupAmount,
            'overhead_percent' => $this->config->persen_overhead,
            'overhead_amount' => $overheadAmount,
            'tax_percent' => $this->config->pajak_persen,
            'tax_amount' => $taxAmount,
            'price_before_tax' => $priceBeforeTax,
            'manual_price' => $manualPrice,
        ];
    }

    protected function getMarkupPercentage(string $customerType)
    {
        $markup = \DB::table('tb_markup')
            ->where('tipe', $customerType)
            ->where('aktif', 'Y')
            ->first();
            
        return $markup ? (float) $markup->persen : 0;
    }

    protected function applyRounding(float $price)
    {
        $roundTo = $this->config->bulatkan_ke ?? '100';
        
        return match($roundTo) {
            '50' => ceil($price / 50) * 50,
            '100' => ceil($price / 100) * 100,
            '500' => ceil($price / 500) * 500,
            '1000' => ceil($price / 1000) * 1000,
            default => $price,
        };
    }
}