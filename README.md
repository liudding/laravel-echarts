# laravel echarts

Build ECharts using laravel

## Install

```
composer require ding/laravel-echarts
```



## Usage

In your php file or block:
```php
$chart = app('chart')->locale('ZH')
    ->addSeries([
        'name' => 'Series #1',
        'type' => 'line',
        'data' => [1, 3, 2, 2, 4, 12, 5]
    ])
    ->addSeries([
        'name' => 'Series #2',
        'type' => 'line',
        'data' => [3, 1, 3, 4, 2, 11, 3]
    ])
    ->xAxis(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'])
    ->yAxis()
    ->legend() // 自动根据 series names 设置 legend
    ->lazy(); // 懒加载

```

In your `blade` file

```php
<div style="width: 500px;height:400px;">
    {!! $chart->render() !!}
</div>
```