@extends('layouts.master_clean')

@section('content')
<section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">

        <div class="d-flex justify-content-center py-4">
          <a href="{{ route('index') }}" class="logo d-flex align-items-center w-auto">
            <img src="assets/img/logo.png" alt="">
            <span class="d-none d-lg-block">彰化縣國中小新生編班系統2</span>
          </a>
        </div><!-- End Logo -->

        <div class="card mb-3">

          <div class="card-body">

            <div class="pt-4 pb-2">
              <h5 class="card-title text-center pb-0 fs-4">系統管理員登入</h5>
              <p class="text-center small">請輸入管理員帳號密碼</p>
            </div>

            <form class="row g-3 needs-validation" novalidate method="post" action="{{ route('sauth') }}">
              @csrf
              <div class="col-12">
                <label for="yourUsername" class="form-label">帳號</label>
                <div class="input-group has-validation">
                  <span class="input-group-text" id="inputGroupPrepend"><i class="bi bi-person-fill"></i></span>
                  <input type="text" name="username" class="form-control" id="yourUsername" required autofocus>
                  <div class="invalid-feedback">請輸入帳號.</div>
                </div>
              </div>

              <div class="col-12">
                <label for="yourPassword" class="form-label">密碼</label>
                <input type="password" name="password" class="form-control" id="yourPassword" required>
                <div class="invalid-feedback">請輸入密碼!</div>
              </div>
              <div class="col-4">
                <img src="{{ route('pic') }}" class="img-fluid">
              </div> 
              <div class="col-7">
                <input type="text" name="chaptcha" class="form-control" id="chaptcha" placeholder="左圖數字" required maxlength="5">
                <div class="invalid-feedback">請輸入驗證碼.</div>
              </div> 
              <div class="col-12">
                @include('layouts.errors')
                <button class="btn btn-primary w-100" type="submit">登入</button>
              </div>
            </form>

          </div>
        </div>

        <div class="credits">
          <!-- All the links in the footer should remain intact. -->
          <!-- You can delete the links only if you purchased the pro version. -->
          <!-- Licensing information: https://bootstrapmade.com/license/ -->
          <!-- Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/ -->
          Designed by ET Wang , Theme by<a href="https://bootstrapmade.com/">BootstrapMade</a>
        </div>

      </div>
    </div>
  </div>

</section>
@endsection