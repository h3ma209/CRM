<?php

namespace App\Providers;

use App\Models\Receipt;
use Illuminate\Support\ServiceProvider;

class ReceiptObserver extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function store(Receipt $receipt)
    {
        activity()->log('NEW contract');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Receipt::observe(ReceiptObserver::class);
    }
}
