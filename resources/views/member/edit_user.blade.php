<!DOCTYPE html>
<html lang="zh-Hant-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <title>編輯帳號</title>

</head>
<body>
    @include('top_box')
    @include('message_box')
    <div class="d-flex align-items-center py-4 bg-body-tertiary">
        <div class="container col-xl-10 px-4 py-5">
            <div class="row align-items-center g-lg-5 py-5">
                @php
                    session()->put('page', 'edit_user');
                @endphp
                <h1 class="text-center">編輯{{ Session::get('account') }}的帳號</h1>
                <div class="col-md-10 mx-auto col-lg-5">
                    <form class="p-4 p-md-5 border rounded-3 bg-body-tertiary" id="form_register" action="{{ route('member.update', Session::get('account')) }}" method="post">
                        @csrf
                        @method('PUT')
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control"  name="user_name" id="user_name" value="{{ Session::get('name') }}" placeholder="輸入暱稱">
                            <label for="user_name">暱稱</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="password" class="form-control" name="user_password_old" id="floatingPasswordOld" placeholder="輸入舊密碼">
                            <label for="floatingPasswordOld">舊密碼</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="password" class="form-control" name="user_password_new" id="floatingPasswordNew" placeholder="輸入新密碼">
                            <label for="floatingPasswordNew">新密碼</label>
                        </div>
                        <div class="form-floating mb-3">
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
                        <!-- <small class="text-body-secondary">點選「登錄」即表示您同意使用條款。</small> -->
                        <div class="text-center">
                            <a class="btn btn-outline-dark me-2" href="{{ route('member.show', Session::get('account')) }}">返回個人空間</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</body>
<script>
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
</html>