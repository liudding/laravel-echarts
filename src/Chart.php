<?php

namespace Ding\LaravelECharts;

class Chart
{
    public $element;

    public $option;

    protected $autoLegend = false;

    protected $renderer = 'canvas'; // svg

    protected $size = [
        'height' => '100%',
        'width' => '100%',
    ];

    protected $theme;

    protected $locale = 'ZH';
    
    /**
     * 是否启用懒加载
     */
    protected $lazy = false;

    public function __construct(?array $option = null)
    {
        $this->element = $this->uniqueElementId();

        $this->option = $option ?? [];
    }

    public function legend($legend = true)
    {
        if (is_bool($legend)) {
            $this->autoLegend = $legend;
        } else if (is_array($legend)) {
            $this->option['legend'] = [
                'data' => $legend,
            ];
        }

        return $this;
    }

    public function xAxis($data = null, $type = 'category')
    {
        if (!isset($this->option['xAxis'])) {
            $this->option['xAxis'] = [];
        }

        if (empty($data)) {
            $this->option['xAxis'][] = [
                'type' => 'category',
                'data' => [],
            ];

            return $this;
        }

        if (!$this->isAssocArray($data)) {
            $data = [
                'type' => 'category',
                'data' => $data,
            ];
        }

        $this->option['xAxis'][] = $data;

        return $this;
    }

    public function yAxis($data = null, $type = 'value')
    {
        if (!isset($this->option['yAxis'])) {
            $this->option['yAxis'] = [];
        }

        if (empty($data)) {
            $this->option['yAxis'][] = [
                'type' => 'value',
                'data' => [],
            ];
        } else {
            $this->option['yAxis'][] = $data;
        }

        return $this;
    }

    public function tooltip($data = null)
    {
        if (!$data) {
            $this->option['tooltip'] = [
                'trigger' => 'axis',
                'axisPointer' => [
                    'type' => 'shadow',
                ],
            ];
        } else {
            $this->option['tooltip'] = $data;
        }

        return $this;
    }

    public function toolbox(...$args)
    {
        $feature = [];
        $show = true;

        function formatFeature($feature) {
            $formatted = [];
            foreach ($feature as $f) {
                $formatted[$f] = [
                    'show' => true
                ];
            }
            return $formatted;
        }

        if (empty($args)) {
            $show = true;
        } else if (is_bool($args[0])) {
            $show = $args[0];
        } else if (is_string($args[0])) {
            $feature = formatFeature($args);
        } else if (!$this->isAssocArray($args[0])) {
            $feature = formatFeature($args[0]);
        } else {
            $feature = $args[0];
        }

        $this->option['toolbox'] = [
            'show' => $show,
            'feature' => $feature,
        ];

        return $this;
    }

    public function addSeries($series, $emphasis = false, $focus = 'series')
    {
        if (!isset($this->option['series'])) {
            $this->option['series'] = [];
        }

        if ($emphasis) {
            $emphasis === 'scale' ? $series['emphasis'] = [
                $emphasis => true,
            ] : $series['emphasis'] = [
                $emphasis => $focus,
            ];
        }

        $this->option['series'][] = $series;

        return $this;
    }

    public function dataset($dataset)
    {
        $this->option['dataset'] = $dataset;

        return $this;
    }

    public function theme($theme)
    {
        $this->theme = $theme;

        return $theme;
    }

    public function size(...$args)
    {
        if (count($args) === 1 && is_array($args[0])) {
            $this->width($args[0]['width']);
            $this->height($args[0]['height']);
        } else if (count($args) === 1 && is_string($args[0])) {
            $size = $args[0];
        } else {
            $this->width($args[0]);
            $this->height($args[1]);
        }

        return $this;
    }

    public function height($height)
    {
        $this->size['height'] = is_numeric($height) ? $height . 'px' : $height;

        return $this;
    }

    public function width($width)
    {
        $this->size['width'] = is_numeric($width) ? $width . 'px' : $width;

        return $this;
    }

    public function renderer($renderer)
    {
        $this->renderer = $renderer;

        return $this;
    }

    public function locale($locale)
    {
        $this->locale = $locale;

        return $this;
    }

    public function lazy($lazy=true)
    {
        $this->lazy = $lazy;

        return $this;
    }

    protected function formatOption()
    {
        $option = $this->option;

        if ($this->autoLegend && isset($option['series'])) {

            if (count($option['series']) > 1) {
                $legend = collect($option['series'])->pluck('name');

            } else if (isset($option['series'][0]['data'])) {
                $legend = collect($option['series'][0]['data'])->pluck('name');
            }

            $this->legend($legend->toArray());
        }

        return $this->option;
    }

    protected function uniqueElementId($length = 10)
    {
        $string = '';

        while (($len = strlen($string)) < $length) {
            $size = $length - $len;

            $bytes = random_bytes($size);

            $string .= substr(str_replace(['/', '+', '=', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9'], '', base64_encode($bytes)), 0, $size);
        }

        return $string;
    }

    /**
     * 不严谨的处理
     */
    protected function isAssocArray($array)
    {
        return !isset($array[0]);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        $option = $this->formatOption();

        return view('echarts::chart', [
            'element' => $this->element,
            'option' => $option,
            'renderer' => $this->renderer,
            'size' => $this->size,
            'theme' => $this->theme,
            'locale' => $this->locale
        ]);
    }
}
