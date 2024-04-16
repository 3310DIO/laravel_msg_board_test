@if(Session::has('message'))
    <div class="alert alert-success text-center" role="alert">
        {{ Session::get('message') }}
        <button class="btn btn-outline-primary rounded-circle p-2 lh-1" type="button" data-bs-dismiss="alert" aria-label="Close">
            <span class="bi" width="16" height="16">&times;</span>
            <span class="visually-hidden">Dismiss</span>
        </button>
    </div>
@endif
@if(Session::has('error'))
    <div class="alert alert-danger text-center" role="alert">
        {{ Session::get('error') }}
        <button class="btn btn-outline-primary rounded-circle p-2 lh-1" type="button" data-bs-dismiss="alert" aria-label="Close">
            <span class="bi" width="16" height="16">&times;</span>
            <span class="visually-hidden">Dismiss</span>
        </button>
    </div>
@endif