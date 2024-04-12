<!DOCTYPE html>
<html lang="zh-Hant-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <title>留言板</title>
</head>
<body>
    <nav class="navbar navbar-expand-md bg-dark sticky-top border-bottom" data-bs-theme="dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('msg.index') }}">返回首頁</a>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                @if(Session::has('account'))
                    <ul class="navbar-nav me-auto mb-2 mb-md-0">
                        <li class="nav-item">
                            <a class="btn btn-outline-light me-2" aria-current="page" href="{{ route('member.space', Session::get('account')) }}">{{ Session::get('name') }}</a>
                        </li>
                        <li class="nav-item">
                            <span class="nav-link active" aria-current="page">您好</span>
                        </li>
                    </ul>
                    <a class="btn btn-outline-light me-2" href="{{ route('msg.create') }}">新增留言</a>
                    <a class="btn btn-outline-light me-2" href="{{ route('member.logout') }}">登出</a>
                @else
                    <ul class="navbar-nav me-auto mb-2 mb-md-0">
                        <li class="nav-item">
                            <span class="nav-link active" aria-current="page">請登錄</span>
                        </li>
                    </ul>
                    <a class="btn btn-outline-light me-2" href="{{ route('member.index') }}">登錄</a>
                    <a class="btn btn-warning" href="{{ route('member.create') }}">註冊</a>
                @endif
            </div>
        </div>
    </nav>
    @if (Session::has('message'))
        <div class="alert alert-success text-center" role="alert">
            {{ Session::get('message') }}
            <button class="btn btn-outline-primary rounded-circle p-2 lh-1" type="button" data-bs-dismiss="alert" aria-label="Close">
                <span class="bi" width="16" height="16">&times;</span>
                <span class="visually-hidden">Dismiss</span>
            </button>
        </div>
    @endif
    @if (Session::has('error'))
        <div class="alert alert-danger text-center" role="alert">
            {{ Session::get('error') }}
            <button class="btn btn-outline-primary rounded-circle p-2 lh-1" type="button" data-bs-dismiss="alert" aria-label="Close">
                <span class="bi" width="16" height="16">&times;</span>
                <span class="visually-hidden">Dismiss</span>
            </button>
        </div>
    @endif

    <section class="py-5 text-center container">
        <div class="row py-lg-5">
            <div class="col-lg-6 col-md-8 mx-auto">
                <h1 class="fw-light">留言板</h1>
                <div>
                    <form action="{{ route('msg.show', '0') }}">
                        @csrf
                        <input class="form-control" type="text" id="site-search" name="search" placeholder="輸入標題" value="{{ $search }}"></label>
                        <button class="btn btn-primary my-2">Search</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
    
    <main class="container">
    {{ $message_boards->onEachSide(2)->links('vendor.pagination.bootstrap-5') }}
        @foreach ($message_boards as $message_board)
            @if($message_board->is_del == 0)
                <div class="d-flex align-items-center p-3 my-3 text-white rounded shadow-sm" style="background-color: #6f42c1;">
                    <div class="lh-1">
                        <h1 class="h6 mb-0 text-white lh-1">樓：{{ $message_board->id }}</h1>
                    </div>
                </div>
                <div class="my-3 p-3 bg-body rounded shadow-sm">
                    <a href="{{ route('reply.show', $message_board->id) }}">
                        <h6 class="border-bottom pb-2 mb-0">標題：{{ $message_board->title }}</h6>
                    </a>
                    <div class="d-flex text-body-secondary pt-3">
                        <svg class="bd-placeholder-img flex-shrink-0 me-2 rounded" width="32" height="32" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: 32x32" preserveAspectRatio="xMidYMid slice" focusable="false"><title>{{ $message_board->user_name }}</title><rect width="100%" height="100%" fill="#007bff"></rect></svg>
                        <p class="pb-3 mb-0 small lh-sm border-bottom">
                            <strong class="d-block text-gray-dark">{{ $message_board->user_name }}</strong>
                        </p>
                    </div>
                    <div style="overflow:hidden; white-space: nowrap; text-overflow: ellipsis;">
                        <pre style="overflow:hidden; white-space: nowrap; text-overflow: ellipsis;">{{ $message_board->content }}</pre>
                    </div>
                    <small class="d-block text-end mt-3">
                        <span>建立日期：{{ $message_board->created_at }}</span> |
                        <span>修改日期：{{ $message_board->update_at }}</span>
                    </small>
                </div>
            @else
                <div class="d-flex align-items-center p-3 my-3 text-white rounded shadow-sm" style="background-color: #6f42c1;">
                    <div class="lh-1">
                        <h1 class="h6 mb-0 text-white lh-1">樓：{{ $message_board->id }}</h1>
                    </div>
                </div>
                <div class="my-3 p-3 bg-body rounded shadow-sm">
                    <a href="{{ route('reply.show', $message_board->id) }}">
                        <h6 class="border-bottom pb-2 mb-0">留言已刪除</h6>
                    </a>
                    <small class="d-block text-end mt-3">
                        <span>刪除日期：{{ $message_board->update_at }}</span>
                    </small>
                </div>
            @endif
        @endforeach
        {{ $message_boards->onEachSide(2)->links('vendor.pagination.bootstrap-5') }}
    </main>
</body>
</html>