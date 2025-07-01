@extends('layouts.master')

@section('page_title')
<h1 class="text-danger">自校「測試」編班作業</h1>
@endsection

@section('content')

<section class="section">  
  <div class="card">
    <div class="card-body">
      <h5 class="card-title">僅供除錯與練習，不是正式編班的地方，請不要搞錯了！</h5>    
      <a href="#!" class="btn btn-outline-success" onclick="sw_confirm1('會刪除目前測試區的學生喔！','{{ route('test_copy') }}')">複製目前的學生到這裡來測試</a>                 
      <table class="table table-hover table-bordered">
        <thead>
          <tr>
            <th scope="col" nowrap>
              校名
            </th>            
            <th scope="col" nowrap>
              編班狀態
            </th>
            <th scope="col" nowrap>
              動作1
            </th>
            <th scope="col" nowrap>
              動作2
            </th>
            <th scope="col" nowrap>
              動作3
            </th>
            <th scope="col" nowrap>
              測試列印
            </th>
            <th scope="col" nowrap>
              刪除
            </th>
          </tr>          
        </thead>
        <tbody>
          <tr>
            <td>
              <?php
                $student_num = \App\Models\TestStudent::where('code',$school->code)->count();
                $student = \App\Models\TestStudent::where('code',$school->code)->first();
              ?>
              {{ $school->name }}
              @if(!empty($student))                
                <a href="{{ route('test_show_student',$school->id) }}" class="btn btn-outline-primary">
                  檢視測試名冊
                </a>                
              @else
                <small class="text-info">未有學生</small>
              @endif
            </td>            
            <td>
             @if(!empty($student->class))
              <span class="text-success">已編班</span>
             @else
              <span class="text-danger">未編班</span>
             @endif
            </td>
            <td>
              @if($student_num <> 0)
                @if(empty($student->class))
                  <a href="{{ route('test_form_class',$school->id) }}" class="btn btn-primary">
                    1.進行「測試」編班
                  </a>
                @else
                  <a href="{{ route('test_show_class',$school->id) }}" class="btn btn-outline-primary">
                    1.「測試」編班結果
                  </a>
                @endif                  
              @endif
            </td>
            <td>
              @if(!empty($student->class) and empty($student->teacher))
                <a href="{{ route('test_form_teacher',$school->id) }}" class="btn btn-primary">
                  2.「測試」編排導師
                </a>
              @endif
              @if(!empty($student->teacher))
                <a href="{{ route('test_show_teacher',$school->id) }}" class="btn btn-outline-primary">
                  2.「測試」導師結果
                </a>                                 
              @endif
            </td>
            <td>
              @if(!empty($student->teacher))
                <a href="{{ route('test_form_order',$school->id) }}" class="btn btn-primary">
                  3.「測試」編排班序
                </a>
              @endif
            </td>
            <td>
              @if(!empty($student->class))              
              <a href="{{ route('test_print',$school->id) }}" class="btn btn-success" target="_blank">列印</a>
              <a href="{{ route('test_print2',$school->id) }}" class="btn btn-info" target="_blank">列印(藏)</a>
              @endif
            </td>
            <td>
              @if(!empty($student))      
                <a href="#!" class="btn btn-outline-danger" onclick="sw_confirm1('確定刪除名冊、編班及導師資料喔？','{{ route('test_delete123',$school->id) }}')">刪除測試名冊</a>
              @endif
              @if(!empty($student->class))
                <a href="#!" class="btn btn-outline-warning" onclick="sw_confirm1('確定刪除編班及導師資料喔？','{{ route('test_delete23',$school->id) }}')">1.刪除測試編班</a>
              @endif
              @if(!empty($student->teacher))
                <a href="#!" class="btn btn-outline-dark" onclick="sw_confirm1('確定刪除導師資料喔？','{{ route('test_delete3',$school->id) }}')">2.刪除測試導師</a>
              @endif
            </td>
          </tr>
        </tbody>
      </table>  
      @if(auth()->user()->school->code == '074603')      
        <hr>
        <a href="#!" class="btn btn-outline-success" onclick="sw_confirm1('會刪除目前測試區的學生喔！','{{ route('test_copy','074603-1') }}')">複製目前建和分校的學生到這裡來測試</a>                 
        <table class="table table-hover table-bordered">
          <thead>
            <tr>
              <th scope="col" nowrap>
                校名
              </th>            
              <th scope="col" nowrap>
                編班狀態
              </th>
              <th scope="col" nowrap>
                動作1
              </th>
              <th scope="col" nowrap>
                動作2
              </th>
              <th scope="col" nowrap>
                動作3
              </th>
              <th scope="col" nowrap>
                測試列印
              </th>
              <th scope="col" nowrap>
                刪除
              </th>
            </tr>          
          </thead>
          <tbody>
            <tr>
              <td>
                <?php
                  $student_num = \App\Models\TestStudent::where('code','074603-1')->count();
                  $student = \App\Models\TestStudent::where('code','074603-1')->first();
                ?>
                建和分校
                @if(!empty($student))                
                  <a href="{{ route('test_show_student','17') }}" class="btn btn-outline-primary">
                    檢視測試名冊
                  </a>                
                @else
                  <small class="text-info">未有學生</small>
                @endif
              </td>            
              <td>
              @if(!empty($student->class))
                <span class="text-success">已編班</span>
              @else
                <span class="text-danger">未編班</span>
              @endif
              </td>
              <td>
                @if($student_num <> 0)
                  @if(empty($student->class))
                    <a href="{{ route('test_form_class','17') }}" class="btn btn-primary">
                      1.進行「測試」編班
                    </a>
                  @else
                    <a href="{{ route('test_show_class','17') }}" class="btn btn-outline-primary">
                      1.「測試」編班結果
                    </a>
                  @endif                  
                @endif
              </td>
              <td>
                @if(!empty($student->class) and empty($student->teacher))
                  <a href="{{ route('test_form_teacher','17') }}" class="btn btn-primary">
                    2.「測試」編排導師
                  </a>
                @endif
                @if(!empty($student->teacher))
                  <a href="{{ route('test_show_teacher','17') }}" class="btn btn-outline-primary">
                    2.「測試」導師結果
                  </a>                                 
                @endif
              </td>
              <td>
                @if(!empty($student->teacher))
                  <a href="{{ route('test_form_order','17') }}" class="btn btn-primary">
                    3.「測試」編排班序
                  </a>
                @endif
              </td>
              <td>
                @if(!empty($student->class))              
                <a href="{{ route('test_print','17') }}" class="btn btn-success" target="_blank">列印</a>
                <a href="{{ route('test_print2','17') }}" class="btn btn-info" target="_blank">列印(藏)</a>
                @endif
              </td>
              <td>
                @if(!empty($student))      
                  <a href="#!" class="btn btn-outline-danger" onclick="sw_confirm1('確定刪除名冊、編班及導師資料喔？','{{ route('test_delete123','17') }}')">刪除測試名冊</a>
                @endif
                @if(!empty($student->class))
                  <a href="#!" class="btn btn-outline-warning" onclick="sw_confirm1('確定刪除編班及導師資料喔？','{{ route('test_delete23','17') }}')">1.刪除測試編班</a>
                @endif
                @if(!empty($student->teacher))
                  <a href="#!" class="btn btn-outline-dark" onclick="sw_confirm1('確定刪除導師資料喔？','{{ route('test_delete3','17') }}')">2.刪除測試導師</a>
                @endif
              </td>
            </tr>
          </tbody>
        </table>      
      @endif
    </div>
  </div>
</section>
@endsection