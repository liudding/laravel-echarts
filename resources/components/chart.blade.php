<div id="{{$element}}" style="width: {{ $size['width'] ?? '100%' }};height:{{$size['height'] ?? '100%'}};"></div>

<script type="text/javascript">

    document.addEventListener("DOMContentLoaded", function(event) {
        (function() {
            "use strict";
            let element = document.getElementById("{!! $element !!}");

            var chart = echarts.init(element, '{{$theme}}', {
                    renderer: '{{ $renderer }}',
                    width: 'auto',
                    height: 'auto',
                    locale: '{{ $locale }}',
                })
            chart.setOption(@json($option ?? []));

            window.addEventListener('resize', () => {
                chart.resize();
            })

            const resizeObserver = new ResizeObserver(entries => {
                chart.resize();
            });
            resizeObserver.observe(element.parentElement);
        })();
    });
</script>
