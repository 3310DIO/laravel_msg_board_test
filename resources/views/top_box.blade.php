<nav class="navbar navbar-expand-md bg-dark sticky-top border-bottom" data-bs-theme="dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{ route('msg.index') }}">返回首頁</a>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            @if(Session::has('account'))
                <ul class="navbar-nav me-auto mb-2 mb-md-0">
                    <li class="nav-item">
                        <a class="btn btn-outline-light me-2" aria-current="page" href="{{ route('member.show', Session::get('account')) }}">{{ Session::get('name') }}</a>
                    </li>
                    <li class="nav-item">
                        <span class="nav-link active" aria-current="page">您好</span>
                    </li>
                </ul>
                <a class="btn btn-outline-light me-2" href="{{ route('msg.create') }}"><ion-icon class="fs-5 align-text-bottom" name="add-outline"></ion-icon>新增留言</a>
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