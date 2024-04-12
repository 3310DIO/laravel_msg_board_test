<!DOCTYPE html>
<html lang="zh-Hant-TW">
<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <title>留言</title>
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

    <div class="col-lg-8 mx-auto p-4 py-md-5 bg-body-tertiary">
        <header class="d-flex align-items-center pb-3 mb-5 border-bottom">
            <a href="{{ route('member.space', $message_content[0]->user_account) }}" class="d-flex align-items-center text-body-emphasis text-decoration-none">
                <svg class="bi me-2" width="40" height="32"><use xlink:href="#bootstrap"></use></svg>
                <span class="fs-4">作者：{{$message_content[0]->user_name}}</span>
            </a>
        </header>
        <main>
            @if ($message_content[0]->is_del == 0)
                <h1 class="text-body-emphasis">{{$message_content[0]->title}}</h1><br>
                <div style="word-break: break-all;" >
                    <pre style="white-space: pre-wrap;" class="fs-5">{{$message_content[0]->content}}</pre>
                </div>
                @if(Session::has('account'))
                    @if(Session::get('account') == $message_content[0]->user_account)
                        <div class="mb-5">
                            <a href="{{ route('msg.edit',$message_content[0]->id) }}" class="btn btn-primary btn-lg px-4">修改</a>
                        </div>
                    @endif
                @endif
                <small class="d-block text-end mt-3">
                    <span>建立日期：{{$message_content[0]->created_at}}</span> |
                    <span>修改日期：{{$message_content[0]->update_at}}</span>
                </small>
            @else
                <h1 class="text-body-emphasis">留言已刪除</h1></h1><br>
                <small class="d-block text-end mt-3">
                    <span>建立日期：{{$message_content[0]->update_at}}</span>
                </small>
            @endif
        <div class="wrap">
            @for($i = 0 ; $i < count($message_reply) ; $i++)

                <div class="d-flex align-items-center p-3 my-3 text-white rounded shadow-sm" style="background-color: #89009b;">
                    <div class="lh-1">
                        <div class="check_box">
                            <h1 class="h6 mb-0 text-white lh-1">{{ $i+1 }}樓</h1>
                            @if(!($message_content[0]->is_del) && !($message_reply[$i]->is_del))
                                @if(Session::has('account'))
                                    @if(Session::get('account') == $message_reply[$i]->user_account)
                                        <input type="checkbox" id="check_box{{ $i+1 }}" value="{{ $i+1 }}">
                                        <label for="check_box{{ $i+1 }}">修改</label>
                                    @endif
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
                <div class="my-3 p-3 bg-body rounded shadow-sm">
                    @if(!($message_reply[$i]->is_del))
                        <a class="account_font" href="{{ route('member.space', $message_reply[$i]->user_account) }}">
                            <h6 class="border-bottom pb-2 mb-0">{{$message_reply[$i]->user_name}}</h6>
                        </a>
                    @else
                        <h6 class="border-bottom pb-2 mb-0">無</h6>
                    @endif
                    <div style="overflow:hidden; white-space: nowrap; text-overflow: ellipsis;">
                        @if(!($message_reply[$i]->is_del))
                            <pre type="text" id="text_reply{{ $i+1 }}" style="white-space: pre-wrap;">{{$message_reply[$i]->user_reply}}</pre>
                            <form style="width: 250px;" id="form_del{{ $i+1 }}" action="{{ route('reply.destroy', $message_reply[$i]->id) }}" method="post">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="msg_id" value="{{$message_content[0]->id}}"/>
                            </form>
                            <form style="width: 250px;" id="form_sub{{ $i+1 }}" action="{{ route('reply.update', $message_reply[$i]->id) }}" method="post">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="msg_id" value="{{$message_content[0]->id}}"/>
                                <?php $line_count = substr_count($message_reply[$i]->user_reply, "\n");
                                    if($line_count < 2){
                                        $line_count = 2;
                                } ?>
                                <textarea id="text_reply_modify{{ $i+1 }}" name="reply" rows="{{$line_count}}" cols="50" hidden>{{$message_reply[$i]->user_reply}}</textarea>
                            </form>
                            <button class="btn btn-outline-secondary" type="button" id="btn_delete{{ $i+1 }}" onclick="del({{ $i+1 }})" hidden>刪除</button>
                            <button class="btn btn-outline-secondary" type="button" id="btn_reply_update{{ $i+1 }}" onclick="sub({{ $i+1 }})" hidden>更新</button>
                        @else
                            回覆已刪除
                        @endif
                    </div>
                    <small class="d-block text-end mt-3">
                        @if(!($message_reply[$i]->is_del))
                            <span>回覆日期：{{$message_reply[$i]->reply_time}}</span> |
                            <span>修改日期：{{$message_reply[$i]->update_time}}</span>
                        @else
                            <span>刪除日期：{{$message_reply[$i]->update_time}}</span>
                        @endif
                    </small>
                </div>
            @endfor
        </main>
        @if(!($message_content[0]->is_del))
            <form class="align-items-center p-3 my-3 rounded shadow-sm" action="{{ route('reply.store') }}" method="post">
                @csrf
                <input type="hidden" name="msg_id" value="{{$message_content[0]->id}}"/>
                <p><button class="btn btn-outline-secondary" type="submit">回覆</button></p>
                <textarea  class="form-control" type="text" id="reply" name="reply" rows="10" required></textarea>
            </from>
        @endif
    </div>

</body>
<script>
    var checkboxes = document.querySelectorAll("input[type='checkbox']");

    checkboxes.forEach(function(checkbox){
        checkbox.addEventListener("click", function(){
            var id = this.value;
            const check_box = document.getElementById("check_box"+id);
            const text_reply_modify = document.getElementById("text_reply_modify"+id);
            const btn_reply_update = document.getElementById("btn_reply_update"+id);
            const btn_delete = document.getElementById("btn_delete"+id);
            const text_reply = document.getElementById("text_reply"+id);
            check_box.addEventListener("change", function(){
                if(this.checked){
                    text_reply_modify.removeAttribute('hidden');
                    btn_reply_update.removeAttribute('hidden');
                    btn_delete.removeAttribute('hidden');
                    text_reply.setAttribute('hidden', true);
                }else{
                    text_reply_modify.setAttribute('hidden', true);
                    btn_reply_update.setAttribute('hidden', true);
                    btn_delete.setAttribute('hidden', true);
                    text_reply.removeAttribute('hidden');
                }
            });
            
        });
    });
    function sub(id){
        document.getElementById("form_sub"+id).submit();
    }
    function del(id){
        if(!confirm('要刪除嗎？')){
            return false;
        }else{
            document.getElementById("form_del"+id).submit();
        }
    }
    const textarea_size = document.getElementById('reply');
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

