<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <title>登錄</title>

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
        <div class="container col-xl-10 col-xxl-8 px-4 py-5">
            <div class="row align-items-center g-lg-5 py-5">

                <h1 class="text-center">登錄</h1>
                <div class="col-md-10 mx-auto col-lg-5">
                    <form class="p-4 p-md-5 border rounded-3 bg-body-tertiary" action="{{ route('member.login') }}" method="post">
                        @csrf
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" name="user_account" id="floatingInput" placeholder="帳號" required>
                            <label for="floatingInput">帳號</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="password" class="form-control" name="user_password" id="floatingPassword" placeholder="密碼" required>
                            <label for="floatingPassword">密碼</label>
                        </div>
                        <div class="checkbox mb-3">
                            <label>
                                <input type="checkbox" id="check_box"> 顯示密碼
                            </label>
                        </div>
                        <div class="text-center">
                            <button class="btn btn-primary" type="submit">登錄</button>
                            <a class="btn btn-primary" href="{{ route('member.create') }}">去註冊</a>
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
    // 顯示密碼
    const check_box = document.getElementById("check_box");
    const floating_password = document.getElementById("floatingPassword");
    check_box.addEventListener("change", function(){
        if(this.checked){
            floating_password.type = "text";
        }else{
            floating_password.type = "password";
        }
    });
</script>
</html>