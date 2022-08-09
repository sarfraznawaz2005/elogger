@push('js')
    <script>
        const notyf = new Notyf({duration: 5000, position: {x: 'right', y: 'bottom'}, ripple: false, dismissible: true});

        document.addEventListener('toast-message', event => {
            notyf[event.detail.style](event.detail.message);
        });
    </script>
@endpush
