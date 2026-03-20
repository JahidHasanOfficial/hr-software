<form action="{{ $action }}" method="GET" class="d-flex mr-2 search-form">
    <div class="input-group input-group-sm rounded shadow-sm border" style="background: white; overflow: hidden;">
        <div class="input-group-prepend">
            <span class="input-group-text bg-transparent border-0 pr-0">
                <i class="mdi mdi-magnify text-muted" style="font-size: 1.2rem;"></i>
            </span>
        </div>
        <input type="search" name="search" class="form-control border-0 bg-transparent" placeholder="{{ $placeholder ?? 'Search...' }}" value="{{ request('search') }}" oninput="debounceSubmit(this.form)">
    </div>
</form>

<script>
    let timer;
    function debounceSubmit(form) {
        clearTimeout(timer);
        timer = setTimeout(() => {
            form.submit();
        }, 500); // 500ms debounce
    }
</script>
