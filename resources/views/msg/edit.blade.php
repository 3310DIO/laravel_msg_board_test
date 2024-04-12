<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <title>編輯留言</title>

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
    <div class="my-3 p-3 bg-body rounded shadow-sm">
        <div class="container">
            <main>
                <div class="py-5 text-center">
                    <!-- <img class="d-block mx-auto mb-4" src="/docs/5.3/assets/brand/bootstrap-logo.svg" alt="" width="72" height="57"> -->
                    <h2>編輯留言</h2>
                </div>
                <!-- <a class="btn btn-primary" href="{{ route('msg.index') }}" >返回首頁</a> -->
                <!-- <hr class="my-4"> -->
                <div class="row g-5">
                    <div class="row g-3">
                        <form class="needs-validation" id="form_sub" action="{{ route('msg.update', $msg_edit[0]->id) }}" method="post">
                            @csrf
                            @method('PUT')
                            <!-- <input type="hidden" id="msg_id" name="msg_id" value="{{ $msg_edit[0]->id }}"/> -->
                            <!-- <input type="hidden" id="account" name="account" value="{{ $msg_edit[0]->user_account }}"/> -->
                            <div class="col-12">
                                <label for="title" class="form-label">標題：</label>
                                <input type="text" class="form-control" id="title" name="title" value="{{ $msg_edit[0]->title }}" required>
                                <div class="invalid-feedback">
                                    請輸入標題
                                </div>
                            </div>

                            <div class="col-12">
                                <label for="content" class="form-label">留言：</label><br>
                                <!-- <input type="text" class="form-control" id="content" placeholder="1234 Main St" required=""> -->
                                <textarea class="form-control" id="content" name="content" rows="15" required>{{ $msg_edit[0]->content }}</textarea>
                                <div class="invalid-feedback">
                                    請輸入內容
                                </div>
                            </div>
                            <hr class="my-4">
                        </form>
                        <form class="needs-validation" id="form_del" action="{{ route('msg.destroy', $msg_edit[0]->id) }}" method="post">
                            @csrf
                            @method('DELETE')
                        </form>
                        <button class="w-100 btn btn-primary btn-lg" id="btn_submit" type="button" onclick="sub()">更新</button>
                        <button class="w-100 btn btn-warning btn-lg" id="btn_delete" type="button" onclick="del()">刪除</button>
                    </div>
                </div>
            </main>
        </div>
    </div>
        
</body>
<script>
    function sub(){
        document.getElementById("form_sub").submit();
    }
    function del(){ // 若刪除則送出表單
        if(!confirm('要刪除嗎？')){
            return false;
        }else{
            document.getElementById("form_del").submit();
        }
    }
    // 判斷輸入內若超過文字框則加大文字框範圍
    const textarea_size = document.getElementById('content');
    const max_characters = 70;
    textarea_size.addEventListener('input', function(){
        const characters_rows = (this.value.length)/max_characters;
        const rows = this.value.split('\n').length;
        const rows_sum = rows+characters_rows;
        if(rows_sum > 15){
            this.rows = rows_sum;
        }else{
            this.rows = 15;
        }
    });
</script>
</html>
