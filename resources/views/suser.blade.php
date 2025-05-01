@extends('layouts.master')

@section('page_title')
<br>
<h1>系統管理者</h1>
@endsection

@section('content')

<section class="section">
  <div class="row">    
    <div class="col-lg-12">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title">使用者列表</h5>
          <div style="margin-bottom: 10px;">
            <a href="#" class="btn btn-danger btn-sm" onclick="sw_confirm1('確定登出？','{{ route('slogout') }}')">登出系統管理者</a>                        
          </div>
          <form method="post" action="{{ route('suser_search') }}">
            @csrf
            <table>
              <tr>
                <td><input type="text" id="search" name="want" class="form-control" placeholder="搜尋學校名稱或姓名或職稱" onkeyup="search()" style="width: 300px; margin-bottom: 10px;"> </td>
                <td><button type="submit" class="btn btn-primary btn-sm">搜尋</button></td>
              </tr>
            </table>                    
          </form>
          <table class="table table-striped">
            <thead class="table-primary">
            <tr>
                <th>
                    序號
                </th>
                <th>
                    學校
                </th>
                <th>
                    職稱
                </th>
                <th >
                    姓名
                </th>
                <th>
                    動作 
                </th>
            </tr>
            </thead>
            <tbody>
            @foreach($users as $user)
                <tr>
                    <td>
                        {{ $user->id }}
                    </td>
                    <td>
                      {{ $user->school->name }}
                    </td>
                    <td>
                      {{ $user->title }}
                    </td>
                    <td>
                        {{ $user->name }}
                    </td>
                    <td>
                        <a href="#" class="btn btn-secondary" onclick="sw_confirm1('確定嗎？','{{ route('impersonate',$user->id) }}')">模擬</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
          </table>
          {{ $users->withQueryString()->links('pagination::bootstrap-5') }}       
        </div>
      </div>
    </div>
  </div>
</section>
@endsection