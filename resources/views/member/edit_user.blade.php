@extends('layout.app')

@section('title', '編輯帳號')

@section('content')

    @include('top_box')
    @include('message_box')
    <div class="d-flex align-items-center py-4 bg-body-tertiary">
        <div class="container col-xl-10 px-4 py-5">
            <div class="row align-items-center g-lg-5 py-5">
                <h1 class="text-center">編輯{{ Session::get('account') }}的帳號</h1>
                <form class="p-4 p-md-5 border rounded-3 bg-body-tertiary" id="form_register" action="{{ route('member.update', Session::get('account')) }}" method="post">
                    @csrf
                    @method('PUT')
                    <div class="my-3 p-3 bg-body rounded shadow-sm">
                        <p class="text-center" id="user_color" style="display: flex; justify-content: center; align-items: center;">
                            修改代表色：<input type="color" name="user_color" value="{{ $account->user_color }}"/>
                        </p>
                        @php
                            $line_count = substr_count($account->user_introduce, "\n")+1;
                                if($line_count < 10){
                                    $line_count = 10;
                            }
                        @endphp
                        <textarea class="form-control" type="text" id="text_introduce_modify" name="user_introduce" rows="{{ $line_count }}" style="font-family: 'Courier New', Courier, monospace;" data-bs-toggle="popover" data-bs-trigger="hover focus" data-bs-content="僅能輸入500字">{{ $account->user_introduce }}</textarea>
                    </div>
                    <!-- <div class="col-md-10 mx-auto col-lg-5"> -->
                        <div class="form-floating mb-3" tabindex="0" data-bs-toggle="popover" data-bs-trigger="hover focus" data-bs-content="暱稱僅能輸入2~20字">
                            <input type="text" class="form-control"  name="user_name" id="user_name" value="{{ Session::get('name') }}" placeholder="輸入暱稱">
                            <label for="user_name">暱稱</label>
                        </div>
                        <div class="form-floating mb-3" tabindex="0" data-bs-toggle="popover" data-bs-trigger="hover focus" data-bs-content="輸入舊密碼">
                            <input type="password" class="form-control" name="user_password_old" id="floatingPasswordOld" placeholder="輸入舊密碼">
                            <label for="floatingPasswordOld">舊密碼</label>
                        </div>
                        <div class="form-floating mb-3" tabindex="0" data-bs-toggle="popover" data-bs-trigger="hover focus" data-bs-content="密碼需輸入輸入8~25字且包含大小寫英文數字及特殊符號">
                            <input type="password" class="form-control" name="user_password_new" id="floatingPasswordNew" placeholder="輸入新密碼">
                            <label for="floatingPasswordNew">新密碼</label>
                        </div>
                        <div class="form-floating mb-3" tabindex="0" data-bs-toggle="popover" data-bs-trigger="hover focus" data-bs-content="再次輸入密碼">
                            <input type="password" class="form-control" name="user_password_check" id="floatingPasswordCheck" placeholder="確認新密碼">
                            <label for="floatingPasswordCheck">新密碼確認</label>
                        </div>
                        <div class="checkbox mb-3">
                            <label>
                                <input type="checkbox" id="check_box"> 顯示密碼
                            </label>
                        </div>
                        <div class="text-center">
                            <button class="btn btn-primary" type="button" id="btn_delete" onclick="check_password()" >送出</button>
                        </div>
                        <hr class="my-4">
                        <!-- <small class="text-body-secondary">點選「登入」即表示您同意使用條款。</small> -->
                        <div class="text-center">
                            <a class="btn btn-outline-dark me-2" href="{{ route('member.show', Session::get('account')) }}">返回個人空間</a>
                        </div>
                    <!-- </div> -->
                </form>
            </div>
        </div>

    </div>

@endsection

@section('script')

<script>
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
    var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
    var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl)
    })
    // 判斷密碼及確認密碼是否相同
    function check_password(){
        var pw = document.getElementById("floatingPasswordNew").value;
        var pwc = document.getElementById("floatingPasswordCheck").value;
        if(pw != pwc){
            alert('密碼與確認密碼不同');
        }else{
            document.getElementById("form_register").submit();
        }
    }
    // 顯示密碼
    const check_box = document.getElementById("check_box");
    const user_password_old = document.getElementById("floatingPasswordOld");
    const user_password_new = document.getElementById("floatingPasswordNew");
    const user_password_confirm = document.getElementById("floatingPasswordCheck");
    check_box.addEventListener("change", function(){
        if(this.checked){
            user_password_old.type = "text";
            user_password_new.type = "text";
            user_password_confirm.type = "text";
        }else{
            user_password_old.type = "password";
            user_password_new.type = "password";
            user_password_confirm.type = "password";
        }
    });
</script>

@endsection