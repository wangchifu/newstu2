@extends('layouts.master')

@section('page_title')
<h1>{{ $semester_year }}學年{{ $school->name }}參與編班資料</h1>
@endsection

@section('content')

<section class="section">
  <div class="row">
    <div class="card">
      <div class="card-body">
        <table class="table table-striped">
          <thead>
            <tr>
              <th colspan="6">
                {{ $school->name }}匯送之內容資訊
              </th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <th>
                校名
              </th>
              <td>
                {{ $school->name }}
              </td>
              <th>
                填報之班級數
              </th>
              <td>
                {{ $teachers->count() }}
              </td>
              <th>
                填報人數
              </td>
              <th>
                {{ $students->count() }}
              </td>
            </tr>
            <tr>
              <th>
                男生數
              </th>
              <td>
                {{ $student_data['boy'] }}
              </td>
              <th>
                女生數
              </th>
              <td>
                {{ $student_data['girl'] }}
              </td>
            </tr>
            <tr>
              <th>
                一般生
              </th>
              <td>
                {{ $student_data['general'] }}
              </td>
              <th>
                <span class="text-info">特殊生</span>
              </th>
              <td>
                {{ $student_data['special'] }}
              </td>
              <th>
                特殊生總減人數
              </th>
              <td>
                {{ $student_data['subtract'] }}
              </td>
            </tr>
            <tr>
              <th>
                雙胞胎同班
              </th>
              <td>
                {{ $student_data['bao2'] }}
              </td>
              <th>
                雙胞胎不同班
              </th>
              <td>
                {{ $student_data['bao2_not'] }}
              </td>
            </tr>
            <tr>
              <th>
                三胞胎全同班
              </th>
              <td>
                {{ $student_data['bao3'] }}
              </td>
              <th>
                三胞胎全不同班
              </th>
              <td>
                {{ $student_data['bao3_not'] }}
              </td>
              <td>

              </td>
              <td>

              </td>
            </tr>
            <tr>
              <th>
                教師名冊
              </td>
              <td colspan="5">
                @foreach($teachers as $teacher)
                  {{ $teacher->name }} 
                @endforeach
              </td>
            </tr>
          </tbody>
        </table>
          
          <a href="{{ route('start') }}" class="btn btn-secondary"><i class="bi bi-chevron-double-left"></i> 返回</a>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="card">
      <div class="card-body">
        <table class="table table-striped">
          <thead>
            <tr>
              <th colspan="5">
                全部新生一覽 <span class="small text-danger">◎請檢查是否流水號重複，如有缺號乃屬正常</span>
              </th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <?php $n=1; ?>
              @foreach($students as $student)
                <td width="20%">
                  <small class="text-secondary">{{ $student->no }}</small>
                  @if($student->sex==1)
                    <img src="{{ asset('img/boy.png') }}">
                  @endif
                  @if($student->sex==2)
                    <img src="{{ asset('img/girl.png') }}">
                  @endif
                  @if($student->special == 1)
                    <span class="text-info">{{ $student->name }}</span>
                    (-{{ $student->subtract }})
                    @if(!empty($student->w_teacher))
                      <span class="badge bg-primary">+{{ $student->w_teacher->name }}</span>
                    @else
                      <small class="text-danger">未指定</small>
                    @endif
                  @else
                    {{ $student->name }}
                  @endif
                  @if($student->without_teacher)
                    <span class="badge bg-danger">-{{ $student->wo_teacher->name }}</span>
                  @endif
                </td>
                @if($n%5 == 0)
                  </tr><tr>
                @endif
                <?php $n++; ?>
              @endforeach
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</section>
@endsection