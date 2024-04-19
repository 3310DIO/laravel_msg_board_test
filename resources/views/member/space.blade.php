<!DOCTYPE html>
<html lang="zh-Hant-TW">
<head>
    @include('head_use') 
    <title>{{ $account->user_account }}的個人空間</title>
</head>
<body>
    @include('top_box')
    @include('message_box')

    <main>
        <section class="py-5 text-center container">
            <div class="row py-lg-5">
                <div class="col-lg-6 col-md-8 mx-auto">
                    <h1 class="fw-light">{{ $account->user_name }}的空間</h1>

                        <div class="align-items-center p-3 my-3" style="word-break: break-all;">
                            @php
                                session()->put('page', 'space');
                            @endphp
                            @if($account->user_account == Session::get('account'))
                                <form action="{{ route('member.update', session()->get('account')) }}" method="post">
                                    @csrf
                                    @method('PUT')
                                    <h5 id="title_introduce" style="display: none;">簡介需在500字之間</h5>
                                    <input type="hidden" name="msg_id" value=""/>
                                    @php
                                        $line_count = substr_count($account->user_introduce, "\n")+1;
                                            if($line_count < 10){
                                                $line_count = 10;
                                        }
                                    @endphp
                                    <textarea class="form-control" type="text" id="text_introduce_modify" name="user_introduce" rows="{{ $line_count }}" style="display: none; font-family: 'Courier New', Courier, monospace;">{{ $account->user_introduce }}</textarea><br>
                                    <p><button class="btn btn-outline-secondary" id="btn_introduce_update" style="display: none;">更新</button></p>
                                </form>
                            @endif
                            <pre class="lead text-body-secondary" type="text" id="text_introduce" style="white-space: pre-wrap; display: block;">{{ $account->user_introduce }}</pre>
                        </div>

                    <p>
                        @if($account->user_account == Session::get('account'))
                            <a href="{{ route('member.edit', Session::get('account')) }}" class="btn btn-primary my-2">帳號設定</a>
                            <button type="button" id="modify_introduce" class="btn btn-secondary my-2">修改簡介</button>
                        @endif
                    </p>
                </div>
            </div>
        </section>

        <div class="album py-5 bg-body-tertiary">
            <div class="container">
                @if($account->user_account == Session::get('account'))
                    <div class="my-3 p-3 bg-body rounded shadow-sm">
                        <form class="text-center" action="{{ route('img.store') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="input-group">
                                <input type="file" class="form-control" id="inputGroupFile04" aria-describedby="inputGroupFileAddon04" aria-label="Upload" name="my_img">
                                <button class="btn btn-outline-secondary" type="submit" id="inputGroupFileAddon04">上傳</button>
                            </div>
                        </form>
                    </div>
                @endif
                <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
                    @php
                        $img_id = 0;
                    @endphp
                    @foreach($user_spaces as $user_space)
                        @if(!($user_space->is_del))
                            <div class="col">
                                <div class="card shadow-sm">
                                    <!-- <svg class="bd-placeholder-img card-img-top" width="100%" height="225" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Thumbnail" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Placeholder</title><rect width="100%" height="100%" fill="#55595c"></rect><text x="50%" y="50%" fill="#eceeef" dy=".3em">Thumbnail</text></svg> -->
                                    <img class="bd-placeholder-img card-img-top" width="100%" height="225" alt="Thumbnail" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Thumbnail" preserveAspectRatio="xMidYMid slice" focusable="false" id="img_id_{{ ++$img_id }}" src="{{ URL('/storage/upload/img/' . $user_space->img_url) }}"></img>
                                    <div class="card-body">
                                        <p class="card-text">圖片{{ $img_id }}</p>
                                        <p class="card-text fw-bold">{{ $user_space->img_content }}</p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="btn-group">
                                                <a class="btn btn-sm btn-outline-secondary" target="_blank" href="{{ URL('/storage/upload/img/' . $user_space->img_url) }}">查看</a>
                                                @if($account->user_account == Session::get('account'))
                                                    <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#exampleModal" data-bs-imgNo="{{ $img_id }}" data-bs-whatever="{{ $user_space->id }}" data-bs-content="{{ $user_space->img_content }}">修改介紹</button>
                                                    <form class="msg" id="form_del_{{ $img_id }}" action="{{ route('img.destroy', $user_space->id) }}" method="post">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                    <button type="button" class="btn btn-sm btn-outline-secondary" id="btn_delete" onclick="del({{ $img_id }})">刪除</button>
                                                @endif
                                            </div>
                                            <small class="text-body-secondary">上傳時間：{{ date("Y-m-d", strtotime($user_space->created_at)) }}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                    @if($account->user_account == Session::get('account'))
                        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">修改內容</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form id="form_sub" action="{{ route('img.update', 0) }}" method="post">
                                            @csrf
                                            @method('PUT')
                                            <div class="mb-3 modal-id_use">
                                                <!-- <label for="recipient-name" class="col-form-label">Recipient:</label> -->
                                                <input type="hidden" class="form-control" id="recipient-name" name="img_id"/>
                                            </div>
                                            <div class="mb-3 modal-content_use">
                                                <label for="message-text" class="col-form-label">圖片簡介(限200個字)</label>
                                                <input class="form-control" id="message-text" name="img_content" rows="7" col="8" required/>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">關閉</button>
                                        <button type="button" class="btn btn-primary" onclick="sub()">修改</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </main>

</body>
<script>

    const toggleButton = document.getElementById('modify_introduce');
    const title_introduce = document.getElementById('title_introduce');
    const text_introduce = document.getElementById('text_introduce');
    const text_introduce_modify = document.getElementById('text_introduce_modify');
    const btn_introduce_update = document.getElementById('btn_introduce_update');

    // 添加按钮的点击事件监听器
    toggleButton.addEventListener('click', function() {
        // 切换隐藏内容的显示状态
        if (text_introduce.style.display === 'block') {
            title_introduce.style.display = 'block';
            text_introduce.style.display = 'none';
            text_introduce_modify.style.display = 'block';
            btn_introduce_update.style.display = 'block';
        } else {
            title_introduce.style.display = 'none';
            text_introduce.style.display = 'block';
            text_introduce_modify.style.display = 'none';
            btn_introduce_update.style.display = 'none';
        }
    });

    var exampleModal = document.getElementById('exampleModal');
    exampleModal.addEventListener('show.bs.modal', function(event){
        // Button that triggered the modal
        var button = event.relatedTarget;
        // Extract info from data-bs-* attributes
        var img_no = button.getAttribute('data-bs-imgNo');
        var recipient = button.getAttribute('data-bs-whatever');
        var content = button.getAttribute('data-bs-content');
        // If necessary, you could initiate an AJAX request here
        // and then do the updating in a callback.
        //
        // Update the modal's content.
        var modalTitle = exampleModal.querySelector('.modal-title');
        var modalBodyInput = exampleModal.querySelector('.modal-id_use input');
        var modalBodyInputContent = exampleModal.querySelector('.modal-content_use input');
        var modalBodyImg = exampleModal.querySelector('.modal-footer img');

        modalTitle.textContent = '修改圖片 ' + img_no;
        modalBodyInput.value = recipient;
        modalBodyInputContent.value = content;
    });
    // 確認刪除圖片
    function del(id){
        if(!confirm('要刪除圖片嗎？')){
            return false;
        }else{
            document.getElementById("form_del_"+id).submit();
        }
    }
    function sub(){
        document.getElementById("form_sub").submit();
    }
    const textarea_size = document.getElementById('text_introduce_modify');
    const max_characters = 70;
    textarea_size.addEventListener('input', function(){
        const characters_rows = (this.value.length)/max_characters;
        const rows = this.value.split('\n').length;
        const rows_sum = rows+characters_rows;
        if(rows_sum > 10){
            this.rows = rows_sum;
        }else{
            this.rows = 10;
        }
    });
</script>
</html>