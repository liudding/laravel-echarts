<div id="{{$element}}" style="width: {{ $size['width'] ?? '100%' }};height:{{$size['height'] ?? '100%'}};"></div>

<script type="text/javascript">
    document.addEventListener("DOMContentLoaded", function (event) {
        (function () {
            "use strict";
            let element = document.getElementById('{!! $element !!}');
            let chart;

            function render() {
                if (chart) return;
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

            @if ($lazy)    
            function getOffsetTop(ele) {
                let actualTop = ele.offsetTop;
                let current = ele.offsetParent;

                while (current !== null) {
                    actualTop += current.offsetTop;
                    current = current.offsetParent;
                }

                return actualTop;
            }

            /**
             * 元素是否可见。 自身或者父元素 display:none; 
             */
            function visible(elem) {
                return !!( elem.offsetWidth || elem.offsetHeight || elem.getClientRects().length );
            }

            function reveal(element, threshold) {
                let th = threshold || 0

                let wt = window.pageYOffset,
                    wb = wt + window.innerHeight,
                    et = getOffsetTop(element),
                    eb = et + element.offsetHeight;

                return eb >= wt - th && et <= wb + th;
            }
        
            if (visible(element) && reveal(element)) {
                render();
            }

            let onWindowChange = function() {
                reveal(element) && render();
            }
        

            window.addEventListener('resize', onWindowChange)
            window.addEventListener('scroll', onWindowChange)

            @else
            render();
            @endif
        })();
    });

</script>
