@if (session('toast.message') && session()->has('toast') )
    @push('js')
        <script>
            window.notyf.{{session('toast.style')}}('{{session('toast.message')}}');
        </script>
    @endpush
@endif
