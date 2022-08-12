@if (session('toast.message') && session()->has('toast') )
    @push('js')
        <script>
            let notyf = new Notyf({duration: 5000, position: {x: 'right', y: 'bottom'}, ripple: false, dismissible: true});

            notyf.{{session('toast.style')}}('{{session('toast.message')}}');
        </script>
    @endpush
@endif
