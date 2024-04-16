<!DOCTYPE html>
<html lang="zh-Hant-TW">
<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <title>編輯留言</title>

</head>
<body>
    @include('top_box')
    @include('message_box')
    <div class="my-3 p-3 bg-body rounded shadow-sm">
        <div class="container">
            <main>
                <div class="py-5 text-center">
                    <!-- <img class="d-block mx-auto mb-4" src="/docs/5.3/assets/brand/bootstrap-logo.svg" alt="" width="72" height="57"> -->
                    @if($id != 0)
                        <h2>編輯留言</h2>
                    @else
                        <h2>新增留言</h2>
                    @endif
                </div>
                <!-- <a class="btn btn-primary" href="{{ route('msg.index') }}" >返回首頁</a> -->
                <!-- <hr class="my-4"> -->
                <div class="row g-5">
                    <div class="row g-3">
                        @if($id != 0)
                            <form class="needs-validation" id="form_sub" action="{{ route('msg.update', $msg_edit->id) }}" method="post">
                        @else
                            <form class="needs-validation" id="form_sub" action="{{ route('msg.store') }}" method="post">
                        @endif
                            @csrf
                            @if($id != 0)
                                @method('PUT')
                            @endif
                            <!-- <input type="hidden" id="msg_id" name="msg_id" value="{{ $msg_edit->id }}"/> -->
                            <!-- <input type="hidden" id="account" name="account" value="{{ $msg_edit->user_account }}"/> -->
                            <div class="col-12">
                                <label for="account" class="form-label">名字：{{ Session::get('name') }}</label><br>
                                <label for="title" class="form-label">標題：</label>
                                <input type="text" class="form-control" id="title" name="title" value="{{ $msg_edit->title }}" required>
                                <div class="invalid-feedback">
                                    請輸入標題
                                </div>
                            </div>

                            <div class="col-12">
                                <label for="content" class="form-label">留言：</label><br>
                                <!-- <input type="text" class="form-control" id="content" placeholder="1234 Main St" required=""> -->
                                <textarea class="form-control" id="content" name="content" rows="15" required>{{ $msg_edit->content }}</textarea>
                                <div class="invalid-feedback">
                                    請輸入內容
                                </div>
                            </div>
                            <hr class="my-4">
                        </form>
                        <div class="text-center">
                            @if($id != 0)
                                <button class="mx-2 w-25 btn btn-primary btn-lg" id="btn_submit" type="button" onclick="edit()">更新</button>
                                <button class="mx-2 w-25 btn btn-warning btn-lg" id="btn_delete" type="button" onclick="del()">刪除</button>
                                <form class="needs-validation" id="form_del" action="{{ route('msg.destroy', $msg_edit->id) }}" method="post">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            @else
                                <button class="mx-2 w-25 btn btn-primary btn-lg" id="btn_submit" type="button" onclick="sub()">送出</button>
                            @endif
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
        
</body>
<script>
    function sub(){ // 若刪除則送出表單
        if(!confirm('要發佈嗎？')){
            return false;
        }else{
            document.getElementById("form_sub").submit();
        }
    }
    function edit(){
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
