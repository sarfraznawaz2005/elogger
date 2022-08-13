@push('js')
    <script>
        document.addEventListener('toast-message', event => {
            window.notyf[event.detail.style](event.detail.message);
        });
    </script>
@endpush
