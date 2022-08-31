@push('js')
    <script src="/js/lottie.min.js" defer></script>
@endpush

<div
    wire:ignore
    style="display: none;"
    x-data="{show:false}"
    x-show="show"
    x-init="

        document.addEventListener('animated-ok', () => {
            show = true;

            animation = bodymovin.loadAnimation({
                container: $refs.icon,
                path: '{{ asset('animated-ok.json') }}',
                renderer: 'svg',
                loop: false,
                autoplay: true
            });

            animation.onComplete = () => {
                show = false;
                animation.destroy();
            }
       });
    "
>

    <div
        class="fixed top-0 left-0 right-0 bottom-0 w-full h-screen z-50 overflow-hidden bg-gray-700 bg-opacity-50 flex flex-col items-center justify-center">
        <div class="w-auto">
            <div x-ref="icon" class="w-96 h-96"></div>
        </div>
    </div>

</div>
