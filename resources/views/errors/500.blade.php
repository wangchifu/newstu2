@extends('layouts.master')

@section('page_title')
<br>
<h1>500系統錯誤</h1>
@endsection

@section('content')

<section class="section">
  <div class="row">    
    <div class="col-lg-12">
      <a href="#!" class="btn btn-primary" onclick="history.back();">返回前頁</a><br>
      <div class="card">
        <div class="card-header">
          <h5 class="card-title">500 Internal Server Error</h5>
        <div class="card-body">   
          <table>
            <tr>
              <td>
                <img src="{{ asset('img/500-error.png') }}" class="img-fluid" alt="500 Internal Server Error"><br>                
              </td>
              <td style="vertical-align: top;">                
                <p class="card-text">抱歉，系統發生錯誤，請稍後再試。</p>
                <p class="card-text">若您是在上傳學生名冊，請注意是不是您沒有使用<span class="text-danger">從校務系統下載來的檔案</span>！ </p>
                <p class="card-text">如果問題持續存在，請聯繫和東國小資訊組王老師。</p>
              </td>
            </tr>
          </table>                                               
        </div>
      </div>
    </div>
  </div>
</section>
@endsection