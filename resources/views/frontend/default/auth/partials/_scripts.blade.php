
@php echo Toastr::message(); @endphp
<script>
    @if(session()->has('messege'))
    let type = "{{session()->get('alert-type','info')}}";
    switch(type){
        case 'info':
            toastr.info("{{ session()->get('messege') }}");
            break;
        case 'success':
            toastr.success("{{ session()->get('messege') }}");
            break;
        case 'warning':
            toastr.warning("{{ session()->get('messege') }}");
            break;
        case 'error':
            toastr.error("{{ session()->get('messege') }}");
            break;
    }
    @endif
</script>
@stack('scripts')
