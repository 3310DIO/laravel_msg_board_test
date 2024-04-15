<!DOCTYPE html>
<html lang="zh-Hant-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <title>註冊</title>
    
</head>
<body>
    @if (Session::has('message'))
        <div class="alert alert-success text-center" role="alert">
            {{ Session::get('message') }}
            <button class="btn btn-outline-primary rounded-circle p-2 lh-1" type="button" data-bs-dismiss="alert" aria-label="Close">
                <span class="bi" width="16" height="16">&times;</span>
                <span class="visually-hidden">Dismiss</span>
            </button>
        </div>
    @endif
    @if (Session::has('error'))
        <div class="alert alert-danger text-center" role="alert">
            {{ Session::get('error') }}
            <button class="btn btn-outline-primary rounded-circle p-2 lh-1" type="button" data-bs-dismiss="alert" aria-label="Close">
                <span class="bi" width="16" height="16">&times;</span>
                <span class="visually-hidden">Dismiss</span>
            </button>
        </div>
    @endif
    <div class="d-flex align-items-center py-4 bg-body-tertiary">
        <div class="container col-xl-10 px-4 py-5">
            <div class="row align-items-center g-lg-5 py-5">

                <h1 class="text-center">註冊</h1>
                <div class="col-md-10 mx-auto col-lg-5">
                    <form class="p-4 p-md-5 border rounded-3 bg-body-tertiary" action="{{ route('member.store') }}" method="post">
                        @csrf
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" name="user_account" id="floatingInput" placeholder="帳號" required>
                            <label for="floatingInput">帳號</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" name="user_name" id="floatingName" placeholder="密碼" required>
                            <label for="floatingName">暱稱</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="password" class="form-control" name="user_password" id="floatingPassword" placeholder="密碼" required>
                            <label for="floatingPassword">密碼</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="password" class="form-control" name="user_password_check" id="floatingPasswordCheck" placeholder="密碼" required>
                            <label for="floatingPasswordCheck">確認密碼</label>
                        </div>
                        <div class="checkbox mb-3">
                            <label>
                                <input type="checkbox" id="check_box"> 顯示密碼
                            </label>
                        </div>
                        <div class="text-center">
                            <a class="btn btn-primary" href="{{ route('member.index') }}">去登錄</a>
                            <button class="btn btn-primary" type="submit" onclick="check_password()">註冊</button>
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