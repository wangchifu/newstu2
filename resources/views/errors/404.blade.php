@extends('layouts.master')

@section('page_title')
<br>
<h1>404找不到頁面</h1>
@endsection

@section('content')

<section class="section">
  <div class="row">    
    <div class="col-lg-12">
      <a href="#!" class="btn btn-primary" onclick="history.back();">返回前頁</a><br>
      <div class="card">
        <div class="card-header">
          <h5 class="card-title">404 Page Not Found</h5>
        <div class="card-body">   
          <table>
            <tr>
              <td>
                <img src="{{ asset('img/404-error.png') }}" class="img-fluid" alt="500 Internal Server Error"><br>                
              </td>
              <td style="vertical-align: top;">                
                <p class="card-text">抱歉，這個網址在系統裡找不到頁面。</p>
                <p class="card-text">您是不是<span class="text-danger">自行輸入網址</span>？</p>
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