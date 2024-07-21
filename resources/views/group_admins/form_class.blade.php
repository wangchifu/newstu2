@extends('layouts.master')

@section('page_title')
<h1>{{ $school->name }}亂數確認</h1>
@endsection

@section('content')

<section class="section">
  <div class="row">
    <div class="container justify-content-center">
      <div class="card">
        <div class="card-body">
            <form method="post" action="{{ route('go_form',$school->id) }}" id="go_form">
            @csrf
            <table class="table table-striped w-50">
              <thead>
                <tr>
                  <th>                    
                    <h2 class="text-primary">電腦亂數編班填寫表單</h2>
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
                  <td><input type="text" name="random_seed" class="form-control" value="{{ rand(1000,9999) }}" id="random_seed" style="color:red;font-size:30px;" maxlength="4"></td>
                </tr>
              </tbody>            
            </table>          
            </form>
            <a href="{{ route('start') }}" class="btn btn-secondary"><i class="bi bi-chevron-double-left"></i> 返回</a>
            <a href="#" class="btn btn-success" onclick="new_seed()"><i class="bi bi-arrow-counterclockwise"></i> 重新取得亂數種子</a>
            <a href="#" class="btn btn-primary" onclick="sw_confirm2('開始編班嗎？','go_form')"><i class="bi bi-play-fill"></i> 開始編班</a>
            <script>
              function new_seed(){
                var r = Math.floor(Math.random() * (9999 - 1000 + 1)) + 1000;
                document.getElementById('random_seed').value = r;                
              }
            </script>
        </div>      
      </div>
    </div>    
  </div>
</section>
@endsection