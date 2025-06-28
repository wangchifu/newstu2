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
              <h5 class="card-title text-center pb-0 fs-4">
                <a href="https://eip.chc.edu.tw" target="_blank"><img src="{{ asset('img/chc2.png') }}" alt="CHC Logo" width="50" class="me-2" style="margin-right:10px; border:1px solid #000000;"></a>
                彰化縣教育雲端帳號登入                
              </h5>
              <p class="text-center small">請按下方按鈕</p>
            </div>            
            <div class="text-center">
              <a href="{{ route('sso') }}" class="image-button">
                <img src="{{ asset('img/chc.jpg') }}" alt="彰化chc的logo" width="120">
              </a>
              <br>OpenID登入
            </div>     
            <div class="text-center mt-3">
              <a href="https://eip.chc.edu.tw/recovery-password" target="_blank" class="btn btn-warning">
                忘記密碼？
              </a>              
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