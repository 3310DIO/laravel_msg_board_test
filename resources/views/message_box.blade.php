@if(Session::has('message'))
    <div class="alert alert-success text-center" role="alert">
        {{ Session::get('message') }}
        <button class="btn btn-outline-success rounded-circle" type="button" data-bs-dismiss="alert" aria-label="Close">
            <span class="bi" width="16" height="16">&times;</span>
            <span class="visually-hidden">Dismiss</span>
        </button>
    </div>
@endif
@if(Session::has('error') || $errors->any())
    <div class="alert alert-danger text-center" role="alert">
        <h1>錯誤</h1>
        {{ Session::get('error') }}
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
        <button class="btn btn-outline-danger rounded-circle" type="button" data-bs-dismiss="alert" aria-label="Close">
            <span class="bi" width="16" height="16">&times;</span>
            <span class="visually-hidden">Dismiss</span>
        </button>
    </div>
@endif