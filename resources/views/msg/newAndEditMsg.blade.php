<!DOCTYPE html>
<html lang="zh-Hant-TW">
<head>
    @include('head_use')
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
                                <p class="form-label">名字：{{ Session::get('name') }}</p>
                                <label for="title" class="form-label">標題：</label>
                                <div class="input-group" tabindex="0" data-bs-toggle="popover" data-bs-trigger="hover focus" data-bs-content="標題需在256字以內">
                                    <select class="form-select" id="subtitle" name="subtitle" aria-label="Example select with button addon">
                                        @foreach($subtitle_bars as $subtitle_bar)
                                            <option value="{{ $subtitle_bar->id }}" @if($msg_edit->subtitle == $subtitle_bar->id) selected @endif>{{ $subtitle_bar->sub_name }}</option>
                                        @endforeach
                                    </select>
                                    <input type="text" class="form-control" id="title" name="title" value="{{ $msg_edit->title }}" style="width: 85%" required>
                                </div>
                                <div class="invalid-feedback">
                                    請輸入標題
                                </div>
                            </div><br>

                            <div class="col-12">
                                <label for="content" class="form-label">留言：</label><br>
                                <!-- <input type="text" class="form-control" id="content" placeholder="1234 Main St" required=""> -->
                                <textarea class="form-control" id="content" name="content" style="font-family: 'Courier New', Courier, monospace;" rows="15" tabindex="0" data-bs-toggle="popover" data-bs-trigger="hover focus" data-bs-content="留言需在5000字以內" required>{{ $msg_edit->content }}</textarea>
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
    var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
    var popoverList = popoverTriggerList.map(function(popoverTriggerEl){
        return new bootstrap.Popover(popoverTriggerEl)
    })
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
