@extends('layout.app')

@section('title', '登入')

@section('content')

    @include('message_box')
    <div class="d-flex align-items-center py-4 bg-body-tertiary">
        <div class="container col-xl-10 px-4 py-5">
            <div class="row align-items-center g-lg-5 py-5">
                <h1 class="text-center">登入</h1>
                <div class="col-md-10 mx-auto col-lg-5">
                    <form class="p-4 p-md-5 border rounded-3 bg-body-tertiary" action="{{ route('member.login') }}" method="post">
                        @csrf
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" name="user_account" id="floatingInput" placeholder="帳號" value="{{ old("user_account") }}" required>
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
                            <button class="btn btn-primary" type="submit">登入</button>
                            <a class="btn btn-primary" href="{{ route('member.create') }}">去註冊</a>
                        </div>
                        <hr class="my-4">
                        <!-- <small class="text-body-secondary">點選「登入」即表示您同意使用條款。</small> -->
                        <div class="text-center">
                            <a class="btn btn-outline-dark me-2" href="{{ route('msg.index') }}">返回首頁</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // 顯示密碼
        const check_box = document.getElementById("check_box");
        const user_password = document.getElementById("floatingPassword");
        check_box.addEventListener("change", function(){
            if(this.checked){
                user_password.type = "text";
            }else{
                user_password.type = "password";
            }
        });
    </script>

@endsection