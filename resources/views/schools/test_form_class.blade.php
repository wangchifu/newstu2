@extends('layouts.master')

@section('page_title')
<h1>{{ $school->name }}亂數確認</h1>
@endsection

@section('content')
<style>
  /* 簡單的過場動畫樣式 */
  #loading {
      display: none;
      position: fixed;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      font-size: 24px;
      color: red; 
      background-color: #ffe6e6; 
      border: 2px solid red; 
      border-radius: 8px;
      padding: 10px;                  
  }
</style>
<section class="section">
  <div class="row">
    <div class="container justify-content-center">
      <div class="card">
        <div class="card-body">
            <form method="post" action="{{ route('test_go_form',$school->id) }}" id="go_form">
            @csrf
            <table class="table table-striped">
              <thead>
                <tr>
                  <th colspan="2">                    
                    <h2 class="text-danger">電腦亂數「測試」編班填寫表單</h2>
                  </th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td><h3>編班學校名稱</h3></td>
                  <td><h3 class="text-danger">{{ $school->name }}</h3></td>
                </tr>
                <tr>
                  <td><h3>班級數</h3></td>
                  <td><h3 class="text-danger">{{ $teachers->count() }}</h3></td>
                </tr>
                <tr>
                  <td><h3>亂數種子(4碼)</h3></td>
                  <td><input type="text" name="random_seed" class="form-control" value="{{ rand(1000,9999) }}" id="random_seed" style="color:red;font-size:30px;" maxlength="4" required></td>
                </tr>
              </tbody>            
            </table>                      
            </form>
            @include('layouts.errors')
            <a href="{{ route('test_start') }}" class="btn btn-secondary"><i class="bi bi-chevron-double-left"></i> 返回</a>
            <a href="#" class="btn btn-success" onclick="new_seed()"><i class="bi bi-arrow-counterclockwise"></i> 重新取得亂數種子</a>
            <a href="#" class="btn btn-primary" onclick="sw_confirm3('開始編班嗎？','go_form')"><i class="bi bi-play-fill"></i> 開始測試編班</a>            
            <script>
              function new_seed(){
                var r = Math.floor(Math.random() * (9999 - 1000 + 1)) + 1000;
                document.getElementById('random_seed').value = r;                
              }
              window.addEventListener("load", function () {
                new_seed();  // 重設 input 值
              });
            </script>
        </div>      
      </div>
    </div>    
  </div>
</section>
<div id="loading" style=""></div>
<script>
      // 文字內容
      const text = "程式處理中，請稍後...";
      const loadingDiv = document.getElementById('loading');
      let index = 0;

      // 打字效果函數
      function typeEffect() {
          if (index < text.length) {
              loadingDiv.innerHTML += text.charAt(index);
              index++;
              setTimeout(typeEffect, 150); // 每個字母顯示的間隔時間（150毫秒）
          } else {
              setTimeout(() => {
                  loadingDiv.innerHTML = ""; // 清除文字
                  index = 0; // 重置索引
                  typeEffect(); // 重新啟動打字效果
              }, 1000); // 延遲一秒後重新開始
          }
      }

  function sw_confirm3(message,id) {
              Swal.fire({
                  title: "操作確認",
                  text: message,
                  showCancelButton: true,
                  confirmButtonText:"確定",
                  cancelButtonText:"取消",
              }).then(function(result) {
                 if (result.value) {
                  showLoading();
                  // 啟動打字效果
                  typeEffect();
                  document.getElementById(id).submit();
                 }
                 else {
                    return false;
                 }
              });
          }

    function showLoading() {
        document.getElementById('loading').style.display = 'block';
    }
    
    // 隱藏過場動畫
    function hideLoading() {
        document.getElementById('loading').style.display = 'none';
    }
    hideLoading();
</script>
@endsection