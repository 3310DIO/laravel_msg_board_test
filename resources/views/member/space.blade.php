@extends('layout.app')

@section('title', ($account->user_account . '的個人空間'))

@section('content')

    @include('top_box')
    @include('message_box')

    <main>
        <section class="py-5 text-center" style="background-color: #0000001a;">
            <div class="row py-lg-5">
                <div class="col-lg-6 col-md-8 mx-auto">
                    <h1 class="fw-light">
                        <svg class="bd-placeholder-img flex-shrink-0 me-2 rounded" width="32" height="32" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: 32x32" preserveAspectRatio="xMidYMid slice" focusable="false">
                            <rect width="100%" height="100%" fill="{{ $account->user_color }}"></rect>
                        </svg>
                        {{ $account->user_name }}的空間
                    </h1>
                    <div class="align-items-center p-3 my-3" style="word-break: break-all;">
                        <pre class="lead text-body-secondary" type="text" id="text_introduce" style="white-space: pre-wrap; display: block;">{{ $account->user_introduce }}</pre>
                    </div>
                    @if($account->user_account == Session::get('account'))
                        <a class="btn btn-primary my-2" href="{{ route('member.edit', Session::get('account')) }}">帳號設定</a>
                    @endif
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

@endsection

@section('script')

    <script>

        var exampleModal = document.getElementById('exampleModal');
        exampleModal.addEventListener('show.bs.modal', function(event){
            // Button that triggered the modal
            var button = event.relatedTarget;

            var img_no = button.getAttribute('data-bs-imgNo');
            var recipient = button.getAttribute('data-bs-whatever');
            var content = button.getAttribute('data-bs-content');

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
    </script>

@endsection