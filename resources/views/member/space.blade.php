<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <title>{{ $account }}的個人空間</title>
    <style>
        /* .img_size img{
            width: 100%;
            height: auto;
        } */
        /* body {
            flex-wrap: wrap;
        }
        li {
            text-align: -webkit-match-parent;
        } */
        /* .img_box{
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 200px));
            grid-gap: 10px;
        } */
    </style>

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

    <main>
        <section class="py-5 text-center container">
            <div class="row py-lg-5">
                <div class="col-lg-6 col-md-8 mx-auto">
                    <h1 class="fw-light">{{ $account }}的空間</h1>
                    <p class="lead text-body-secondary">寫一些東西........</p>
                    <p>
                        <a href="{{ route('member.show', Session::get('account')) }}" class="btn btn-primary my-2">修改帳號</a>
                        <a href="#" class="btn btn-secondary my-2">還沒想到的功能2</a>
                    </p>
                </div>
            </div>
        </section>
        @if($account == Session::get('account'))
            <form class="text-center" action="config_img.php?method=upload" method="post" enctype="multipart/form-data">
                <input type="file" name="my_img">
                <input type="submit" name="submit_img" value="上傳">
            </form>
        @endif

        <div class="album py-5 bg-body-tertiary">
            <div class="container">
                <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
                    <?php $img_id=0; ?>
                    @foreach($user_spaces as $user_space)
                        <?php if($user_space->width_height){
                            $img_w_h = "width: 190px;";
                        }else{
                            $img_w_h = "height: 190px;";
                        } ?>
                        <div class="col">
                            <div class="card shadow-sm">
                                <img class="img_img_size" id="img_id_{{ $img_id }}" style="<?php echo $img_w_h ?>;" src="{{ assert('/storage/app/public/upload/img/' . $user_space->img_url) }}">
                                <svg class="bd-placeholder-img card-img-top" width="100%" height="225" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Thumbnail" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Placeholder</title><rect width="100%" height="100%" fill="#55595c"></rect><text x="50%" y="50%" fill="#eceeef" dy=".3em">Thumbnail</text></svg>
                                <div class="card-body">
                                    <p class="card-text">(圖片說明)This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="btn-group">
                                            @if($account == Session::get('account'))
                                                <button type="button" class="btn btn-sm btn-outline-secondary">查看(未設計)</button>
                                                <button type="button" class="btn btn-sm btn-outline-secondary" id="btn_delete" onclick="del({{ $img_id }})">刪除</button>
                                            @endif
                                        </div>
                                        <small class="text-body-secondary">9 mins</small>
                                    </div>
                                    <form class="msg" id="form_del_{{ ++$img_id }}" action="config_img.php?method=img_del" method="post"> <?php // 刪除留言 ?>
                                        <input type="hidden" name="btn_delete_id" value="{{ $user_space->id }}"/>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </main>

</body>
<script>
    // 確認刪除圖片
    function del(id){
        if(!confirm('要刪除圖片嗎？')){
            return false;
        }else{
            document.getElementById("form_del_"+id).submit();
        }
    }
</script>
</html>