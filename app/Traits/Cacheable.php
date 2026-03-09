<?php

namespace App\Traits;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

trait Cacheable
{
    /**
     * Get cached data or store new data
     */
    protected function getCachedData($key, $callback, $ttl = null)
    {
        $ttl = $ttl ?? config('cache.lifetime', 3600);
        $cacheKey = $this->generateCacheKey($key);
        
        try {
            return Cache::remember($cacheKey, $ttl, $callback);
        } catch (\Exception $e) {
            Log::warning("Cache error for key {$cacheKey}: " . $e->getMessage());
            return $callback();
        }
    }

    /**
     * Generate cache key based on parameters
     */
    protected function generateCacheKey($params)
    {
        if (is_array($params)) {
            $key = implode('_', $params);
        } else {
            $key = $params;
        }
        
        // Tambahkan prefix untuk menghindari konflik
        return 'dashboard_' . md5($key);
    }

    /**
     * Clear specific cache
     */
    protected function clearCache($key)
    {
        $cacheKey = $this->generateCacheKey($key);
        Cache::forget($cacheKey);
    }

    /**
     * Clear all dashboard cache
     */
    protected function clearAllDashboardCache()
    {
        // Hanya untuk Redis - hapus semua key dengan prefix dashboard_
        if (config('cache.default') == 'redis') {
            $keys = Cache::getRedis()->keys('dashboard_*');
            foreach ($keys as $key) {
                Cache::forget($key);
            }
        }
    }
}