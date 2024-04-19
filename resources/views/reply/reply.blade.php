<!DOCTYPE html>
<html lang="zh-Hant-TW">
<head>
    @include('head_use')
    <title>留言</title>
</head>
<body>
    @include('top_box')
    @include('message_box')

    <div class="col-lg-8 mx-auto p-4 py-md-5 bg-body-tertiary">
            <header class="d-flex align-items-center pb-3 mb-5 border-bottom">
                <a href="{{ route('member.show', $message_content->user_account) }}" class="d-flex align-items-center text-body-emphasis text-decoration-none">
                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-hexagon-half" viewBox="0 0 16 16">
                        <path d="M14 4.577v6.846L8 15V1zM8.5.134a1 1 0 0 0-1 0l-6 3.577a1 1 0 0 0-.5.866v6.846a1 1 0 0 0 .5.866l6 3.577a1 1 0 0 0 1 0l6-3.577a1 1 0 0 0 .5-.866V4.577a1 1 0 0 0-.5-.866z"/>
                    </svg>
                    <span class="fs-4">作者：{{ $message_content->user_name }}</span>
                </a>
            </header>
            <main>
                @if ($message_content->is_del == 0)
                    <h3 class="text-body-emphasis">[{{ $message_content->subtitle }}] {{ $message_content->title }}</h3><br>
                    <div style="word-break: break-all;" >
                        <pre style="white-space: pre-wrap;" class="fs-5">{{ $message_content->content }}</pre>
                    </div>
                    @if(Session::has('account'))
                        @if(Session::get('account') == $message_content->user_account)
                            <div class="mb-5">
                                <a href="{{ route('msg.edit',$message_content->id) }}" class="btn btn-primary btn-lg px-4">修改</a>
                            </div>
                        @endif
                    @endif
                    <small class="d-block text-end mt-3">
                        <span>建立日期：{{ $message_content->created_at }}</span> |
                        <span>修改日期：{{ $message_content->update_at }}</span>
                    </small>
                @else
                    <h1 class="text-body-emphasis">留言已刪除</h1></h1><br>
                    <small class="d-block text-end mt-3">
                        <span>建立日期：{{ $message_content->update_at }}</span>
                    </small>
                @endif
                @if(sizeof($message_reply) != 0)
                    <p><button class="btn btn-outline-secondary control-all ">全部展開</button></p>
                @endif
                @foreach($message_reply as $msg_reply)
                    <div class="accordion" id="accordionExample{{ $loop->iteration }}">
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" style="background-color: #3ff8ff;" data-bs-toggle="collapse" data-bs-target="#collapseOne{{ $loop->iteration }}" aria-expanded="true" aria-controls="collapseOne{{ $loop->iteration }}">
                                    <h1 class="h6 mb-0 lh-1">{{ $loop->iteration }}樓</h1>
                                </button>
                            </h2>
                            <div id="collapseOne{{ $loop->iteration }}" class="accordion-collapse collapse" data-bs-parent="#accordionExample{{ $loop->iteration }}">
                                <div class="accordion-body">
                                    @if(!($message_content->is_del) && !($msg_reply->is_del))
                                        @if(Session::has('account'))
                                            @if(Session::get('account') == $msg_reply->user_account)
                                                <input type="checkbox" id="check_box{{ $loop->iteration }}" value="{{ $loop->iteration }}">
                                                <label for="check_box{{ $loop->iteration }}">修改</label>
                                            @endif
                                        @endif
                                    @endif
                                    <div class="my-3 p-3 bg-body rounded shadow-sm">
                                        @if(!($msg_reply->is_del))
                                            <a class="account_font" href="{{ route('member.show', $msg_reply->user_account) }}">
                                                <h6 class="border-bottom pb-2 mb-0">{{ $msg_reply->user_name }}</h6>
                                            </a>
                                        @endif
                                        <div  style="word-break: break-all;">
                                            @if(!($msg_reply->is_del))
                                                <pre type="text" id="text_reply{{ $loop->iteration }}" style="white-space: pre-wrap;">{{ $msg_reply->user_reply }}</pre>
                                                <form style="width: 250px;" id="form_del{{ $loop->iteration }}" action="{{ route('reply.destroy', $msg_reply->id) }}" method="post">
                                                    @csrf
                                                    @method('DELETE')
                                                    <input type="hidden" name="msg_id" value="{{ $message_content->id }}"/>
                                                </form>
                                                <form style="width: 250px;" id="form_sub{{ $loop->iteration }}" action="{{ route('reply.update', $msg_reply->id) }}" method="post">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="msg_id" value="{{ $message_content->id }}"/>
                                                    @php
                                                        $line_count = substr_count($msg_reply->user_reply, "\n")+1;
                                                            if($line_count < 3){
                                                                $line_count = 3;
                                                        }
                                                    @endphp
                                                    <textarea id="text_reply_modify{{ $loop->iteration }}" style="font-family: 'Courier New', Courier, monospace;" name="reply" rows="{{ $line_count }}" cols="50" hidden>{{ $msg_reply->user_reply }}</textarea>
                                                </form>
                                                <button class="btn btn-outline-secondary" type="button" id="btn_delete{{ $loop->iteration }}" onclick="del({{ $loop->iteration }})" hidden>刪除</button>
                                                <button class="btn btn-outline-secondary" type="button" id="btn_reply_update{{ $loop->iteration }}" onclick="sub({{ $loop->iteration }})" hidden>更新</button>
                                            @else
                                                回覆已刪除
                                            @endif
                                        </div>
                                        <small class="d-block text-end mt-3">
                                            @if(!($msg_reply->is_del))
                                                <span>回覆日期：{{ $msg_reply->reply_time }}</span> |
                                                <span>修改日期：{{ $msg_reply->update_time }}</span>
                                            @else
                                                <span>刪除日期：{{ $msg_reply->update_time }}</span>
                                            @endif
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </main>
        @if(!($message_content->is_del))
            <form class="align-items-center p-3 my-3 rounded shadow-sm" action="{{ route('reply.store') }}" method="post">
                @csrf
                <input type="hidden" name="msg_id" value="{{ $message_content->id }}"/>
                <textarea  class="form-control" type="text" id="reply" name="reply" rows="10" style="resize: none; font-family: 'Courier New', Courier, monospace;" data-bs-toggle="popover" data-bs-trigger="hover focus" data-bs-content="回覆需在1500字以內" required></textarea><br>
                <p><button class="btn btn-outline-secondary" type="submit">回覆</button></p>
            </from>
        @endif
    </div>

</body>
<script>
    var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
    var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl)
    })
    var control_all = document.querySelector("button.control-all");
    control_all.addEventListener("click", function(){
        if(this.getAttribute("data-lastState") === "0"){
            document.querySelectorAll('.accordion-collapse').forEach(function(collapse){
                collapse.classList.remove('show');
            });
            document.querySelectorAll('.accordion-button').forEach(function(collapsed){
                collapsed.classList.add('collapsed');
                collapsed.setAttribute("aria-expanded", "false");
            });
            this.setAttribute("data-lastState", "1");
            this.textContent = "全部展開";
        }else{
            document.querySelectorAll('.accordion-collapse').forEach(function(collapse){
                // collapse.classList.remove('collapse');
                // collapse.classList.add('collapsing');
                // collapse.classList.remove('collapsing');
                collapse.classList.add('show');
            });
            document.querySelectorAll('.accordion-button').forEach(function(collapsed){
                collapsed.classList.remove('collapsed');
                collapsed.setAttribute("aria-expanded", "true");
            });
            this.setAttribute("data-lastState", "0");
            this.textContent = "全部關閉";
        }
    });

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

