<!DOCTYPE html>
<html lang="zh-Hant-TW">
<head>
    @include('head_use')
    <title>註冊</title>
</head>
<body>
    @include('message_box')
    <div class="d-flex align-items-center py-4 bg-body-tertiary">
        <div class="container col-xl-10 px-4 py-5">
            <div class="row align-items-center g-lg-5 py-5">
                @if($user == 0)
                    <h1 class="text-center">註冊</h1>
                @elseif($user == 1)
                    <h1 class="text-center">登錄</h1>
                @endif
                <div class="col-md-10 mx-auto col-lg-5">
                    @if($user == 0)
                        <form class="p-4 p-md-5 border rounded-3 bg-body-tertiary" action="{{ route('member.store') }}" method="post">
                    @elseif($user == 1)
                        <form class="p-4 p-md-5 border rounded-3 bg-body-tertiary" action="{{ route('member.login') }}" method="post">
                    @endif
                        @csrf
                        @if($user == 0)
                            <div class="form-floating mb-3" tabindex="0" data-bs-toggle="popover" data-bs-trigger="hover focus" data-bs-content="帳號僅能輸入8~20字且為英文及數字">
                        @elseif($user == 1)
                            <div class="form-floating mb-3">
                        @endif
                            <input type="text" class="form-control" name="user_account" id="floatingInput" placeholder="帳號" required>
                            <label for="floatingInput">帳號</label>
                        </div>
                        @if($user == 0)
                            <div class="form-floating mb-3" tabindex="0" data-bs-toggle="popover" data-bs-trigger="hover focus" data-bs-content="暱稱僅能輸入2~20字">
                                <input type="text" class="form-control" name="user_name" id="floatingName" placeholder="密碼" required>
                                <label for="floatingName">暱稱</label>
                            </div>
                        @endif
                        @if($user == 0)
                            <div class="form-floating mb-3" tabindex="0" data-bs-toggle="popover" data-bs-trigger="hover focus" data-bs-content="密碼需輸入輸入8~25字且包含大小寫英文數字及特殊符號">
                        @elseif($user == 1)
                            <div class="form-floating mb-3">
                        @endif
                            <input type="password" class="form-control" name="user_password" id="floatingPassword" placeholder="密碼" required>
                            <label for="floatingPassword">密碼</label>
                        </div>
                        @if($user == 0)
                            <div class="form-floating mb-3" tabindex="0" data-bs-toggle="popover" data-bs-trigger="hover focus" data-bs-content="再次輸入密碼">
                                <input type="password" class="form-control" name="user_password_check" id="floatingPasswordCheck" placeholder="密碼" required>
                                <label for="floatingPasswordCheck">確認密碼</label>
                            </div>
                        @endif
                        <div class="checkbox mb-3">
                            <label>
                                <input type="checkbox" id="check_box"> 顯示密碼
                            </label>
                        </div>
                        <div class="text-center">
                        @if($user == 0)
                            <a class="btn btn-primary" href="{{ route('member.index') }}">去登錄</a>
                            <button class="btn btn-primary" type="submit" onclick="check_password()">註冊</button>
                        @elseif($user == 1)
                            <button class="btn btn-primary" type="submit">登錄</button>
                            <a class="btn btn-primary" href="{{ route('member.create') }}">去註冊</a>
                        @endif
                        </div>
                        <hr class="my-4">
                        <!-- <small class="text-body-secondary">點選「登錄」即表示您同意使用條款。</small> -->
                        <div class="text-center">
                            <a class="btn btn-outline-dark me-2" href="{{ route('msg.index') }}">返回首頁</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>

</body>
<script>
    var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
    var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl)
    })
    // 判斷密碼及確認密碼是否相同
    function check_password(){
        var pw = document.getElementById("floatingPassword").value;
        var pwc = document.getElementById("floatingPasswordCheck").value;
        if(pw != pwc){
            alert('密碼不同');
        }else{
            document.getElementById("form_register").submit();
        }
    }
    // 顯示密碼
    const check_box = document.getElementById("check_box");
    const user_password = document.getElementById("floatingPassword");
    const user_password_confirm = document.getElementById("floatingPasswordCheck");
    check_box.addEventListener("change", function(){
        if(this.checked){
            user_password.type = "text";
            user_password_confirm.type = "text";
        }else{
            user_password.type = "password";
            user_password_confirm.type = "password";
        }
    });
</script>
</html>