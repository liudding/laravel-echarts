<?php

namespace Ding\LaravelECharts;

use Illuminate\Support\ServiceProvider;

class LaravelEChartsServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('chart', function() {
            return new Chart();
        });
    }

    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/components', 'echarts');
    }

}
