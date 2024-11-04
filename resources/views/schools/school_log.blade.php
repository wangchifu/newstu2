@extends('layouts.master')

@section('page_title')
<h1>學校操作記錄</h1>
@endsection

@section('content')

<section class="section">
  <div class="card">
    <div class="card-body">
      <h5 class="card-title">「{{ auth()->user()->school->name }}」重要操作記錄</h5>             
      <table class="table table-hover">
        <thead>
          <tr>
            <th scope="col">
              #
            </th>
            <th scope="col">
              發生時間
            </th>
            <th scope="col">
              操作者
            </th>
            <th scope="col">
              訊息
            </th>
            <th scope="col">
              ip
            </th>
            <th scope="col">
              對象學校
            </th>            
          </tr>          
        </thead>
        <tbody>
          <?php $n=1; ?>
          @foreach($logs as $log)
            <tr>
              <td>
                {{ $n }}
              </td>
              <td>
                {{ $log->created_at }}
              </td>
              <td>
                {{ $log->user->school->name }} {{ $log->user->title }} {{ $log->user->name }}
              </td>
              <td>
                {{ $log->message }}
              </td>
              <td>
                {{ $log->ip }}
              </td>         
              <td>
                <?php $school = \App\Models\School::where('code',$log->for_code)->first(); ?>
                {{ $school->name }}
              </td>      
            </tr>
            <?php $n++; ?>
          @endforeach
        </tbody>
      </table>
      {{ $logs->withQueryString()->links('pagination::bootstrap-5') }}
    </div>
  </div>
</section>
@endsection