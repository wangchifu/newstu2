@extends('layouts.master')

@section('page_title')
<h1>上傳老師及學生名冊</h1>
@endsection

@section('content')

<section class="section">
  <div class="row">
    <div class="card">
      <div class="card-body">
        @if(!$ready==1)
          <form method="post" action="{{ route('import_excel') }}" enctype="multipart/form-data" id="send_student">
            @csrf
            <h5 class="card-title">請點選從校務系統 cloudschool 下載來的檔案</h5>
            <div class="row mb-3">
              <label for="inputNumber" class="col-sm-2 col-form-label">檔案上傳</label>
              <div class="col-sm-10">
                <input class="form-control" type="file" id="file" name="file" accept=".xlsx" required>                                            
              </div>
              <small style="margin-top: 20px;">檔案格式：<span class="text-danger">學年度_學校代碼_日期.xlsx</span>，如 (113_074627_20240626.xlsx)</small>
            </div>
            <div class="row mb-3">
              <label class="col-sm-2 col-form-label"></label>
              <div class="col-sm-10">
                @include('layouts.errors')
                <a class="btn btn-primary" onclick="sw_confirm2('重複上傳將先刪除先前資料喔！','send_student')"><i class="bi bi-arrow-right-circle-fill"></i> 送出</a>
              </div>
            </div>
          </form>
        @else
            @if(!empty(auth()->user()->school->situation))
              <p><span class="text-success">**編班完成**</span></p>
              <a href="{{ route('school_show_class',auth()->user()->school->id) }}" class="btn btn-outline-primary">編班結果</a>
              <a href="{{ route('school_export',auth()->user()->school->id) }}" class="btn btn-success" target="_blank"><i class="bi bi-cloud-arrow-down-fill"></i> 下載本校編班檔</a>
            @else
              <p><span class="text-danger">**已送交編班中心無法再更動**真有需求時請編班中心打開上鎖**</span></p>
              <p><span class="text-danger">**尚未編班**</span></p>
            @endif          
        @endif
      </div>
    </div>
  </div>
  @if(!empty($student_data))
  <div class="row">
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">{{ $semester_year }}學年已匯入的老師及學生名冊</h5>    
        <span class="text-danger">以下資料供參，請至右上角「學校名稱」-「<a href="{{ route('student_type') }}"><i class="bi bi-gear"></i> 設定學生</a>」繼續更多的設定！</span>    
        <hr>        
        校名：{{ auth()->user()->school->name }} 班級數：{{ $class_num }} 學生數：{{ count($student_data[$semester_year]) }}<br>
        老師：
        @foreach($teachers as $teacher)
          {{ $teacher->name }}，
        @endforeach
        <table class="table table-hover">
          <thead>
            <tr>
              <th scope="col">#</th>
              <th scope="col">流水號</th>
              <th scope="col">班級</th>
              <th scope="col">座號</th>
              <th scope="col">性別</th>
              <th scope="col">姓名</th>
              <th scope="col">身分證字號</th>
              <th scope="col">原就讀學校</th>
              <th scope="col">編班類別</th>
              <th scope="col">相關流水號</th>
              <th scope="col">備註</th>
            </tr>
          </thead>
          <tbody>
            <?php $n=1; ?>
            @foreach($student_data as $k=>$v)
              @foreach($v as $k1=>$v1)
                <tr>
                  <th scope="row">
                    {{ $n }}
                  </th>                
                  <td>
                    {{ $v1['no'] }}
                  </td>      
                  <td>
                    {{ $v1['class'] }}
                  </td>                                                   
                  <td>
                    {{ $v1['num'] }}       
                  </td>
                  <td>
                    {{ $v1['sex'] }}
                  </td>
                  <td>
                    {{ $v1['name'] }}
                  </td>
                  <td>
                    {{ $v1['id_number'] }}
                  </td>
                  <td>
                    {{ $v1['old_school'] }}
                  </td>
                  <td>
                    {{ $v1['type'] }}
                  </td>
                  <td>
                    {{ $v1['another_no'] }}
                  </td>
                  <td>
                    {{ $v1['ps'] }}
                  </td>
                </tr>
                <?php $n++; ?>  
              @endforeach
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
  @endif
</section>
@endsection