<div id="{{$element}}" style="width: {{ $size['width'] ?? '100%' }};height:{{$size['height'] ?? '100%'}};"></div>

<script type="text/javascript">
    document.addEventListener("DOMContentLoaded", function (event) {
        (function () {
            "use strict";
            let element = document.getElementById('{!! $element !!}');

            // if hidden do nothing

            var chart;

            function render() {
                if (chart) {
                    return;
                }

                chart = echarts.init(element, '{{$theme}}', {
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
            }

            function getOffsetTop(ele) {
                var actualTop = ele.offsetTop;
                var current = ele.offsetParent;

                while (current !== null) {
                    actualTop += current.offsetTop;
                    current = current.offsetParent;
                }

                return actualTop;
            }

            function visible(threshold) {
                let th = threshold || 0

                var wt = window.pageYOffset,
                    wb = wt + window.innerHeight,
                    et = getOffsetTop(element),
                    eb = et + element.offsetHeight;

                return eb >= wt - th && et <= wb + th;
            }
           

            if (visible()) {
                render();
            }

            window.addEventListener('resize', () => {
                if (visible()) {
                    render();
                }
            })

            window.addEventListener('scroll', () => {
                if (visible()) {
                    render();
                }
            })

        })();
    });

</script>
