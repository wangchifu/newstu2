@extends('layouts.master')

@section('page_title')
<h1>學校編班作業</h1>
@endsection

@section('content')

<section class="section">
  @if(!empty($duplicates))
    <body onload="sw_alert('重複身分證','系統中有學生身分證相同','error')">
  @endif
  <style>
    .red-border-table, .red-border-table td {
        border: 2px solid red;
    }
    .red-border-table {
        border-collapse: collapse;
    }
  </style>
  <div class="card">
    <div class="card-body">
      <h5 class="card-title">「{{ $group->name }}」學校列表</h5>   
      <?php
      if(isset($_COOKIE["real".$group->id])){
        if($_COOKIE["real".$group->id] == 1){
          $display="none";
          $checked = "checked";
        }else{
          $display="";
          $checked = "";
        }
      }else{
        $display="";
        $checked = "";
      }
      ?>
      @foreach($duplicates as $k=>$v)
        <?php
          $students = \App\Models\Student::where('id_number',$k)->get();
        ?>
        <span class="text-danger">重複身分證的學生</span>
        <table class="red-border-table">
        @foreach($students as $student)   
          <?php $school = \App\Models\School::where('code',$student->code)->first(); ?>
          <tr>
            <td>{{ $school->name }}</td><td>{{ $student->code }}</td><td>{{ $student->id_number }}</td><td>{{ $student->name }}</td>
          </tr>        
        @endforeach
        </table>
      @endforeach
      <input type="checkbox" id="toggleCheckbox" onclick="toggleCellVisibility()" {{ $checked }}> 
      <label for="toggleCheckbox">正式編班請打勾</label>
      @if(isset($_COOKIE["real".$group->id]))
        <div id="change_mode" class="badge bg-success">
          @if($_COOKIE["real".$group->id] == 1)            
            正式執行
          @else            
            測試當中
          @endif
        </div>      
      @endif
      <table class="table table-hover">
        <thead>
          <tr>
            <th scope="col">
              #
            </th>
            <th scope="col">
              校名
            </th>
            <th scope="col">
              各校確認
            </th>
            <th scope="col">
              編班狀態
            </th>
            <th scope="col">
              動作1
            </th>
            <th scope="col">
              動作2
            </th>
            <th scope="col">
              動作3
            </th>
            <th scope="col">
              下載&列印
            </th>
            <th scope="col" class="toggle-cell" style="display: {{ $display }}">
              刪除 <a class="btn btn-outline-danger" href="#!" onclick="sw_confirm1('確定刪除這個分區所有的學生及導師資料喔？','{{ route('delete_all',$group->id) }}')"><i class="bi bi-rocket-fill"></i> 全部刪除</a>
            </th>
          </tr>          
        </thead>
        <tbody>
          <?php $n=1; ?>
          @foreach($schools as $school)
            <tr>
              <td>
                {{ $n }}
              </td>
              <td>
                <?php
                  $student = \App\Models\Student::where('code',$school->code)->first();
                ?>
                {{ $school->name }}
                @if(!empty($student))                
                  <a href="{{ route('show_student',$school->id) }}" class="btn btn-outline-primary">
                    檢視名冊
                  </a>                
                @else
                  <small class="text-info">未上傳</small>
                @endif
              </td>
              <td>
                @if($school->ready)
                <a href="#!" onclick="sw_confirm1('確定該校改為「未確定」？','{{ route('group_admin_unlock',$school->id) }}')">
                  <i class="bi bi-check-circle text-success"></i>
                </a>
                @else
                  @if(!empty($student))
                    <a href="#!" onclick="sw_confirm1('確定該校不再更改？','{{ route('group_admin_unlock',$school->id) }}')">
                      <i class="bi bi-dash-circle text-dark"></i>
                    </a>
                  @else
                  <i class="bi bi-x-circle text-danger"></i>
                  @endif
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
                @if($school->ready)
                  @if(empty($student->class))
                    <a href="{{ route('form_class',$school->id) }}" class="btn btn-primary">
                      1.進行編班
                    </a>
                  @else
                    <a href="{{ route('show_class',$school->id) }}" class="btn btn-outline-primary">
                      1.編班結果
                    </a>
                  @endif                  
                @endif
              </td>
              <td>
                @if(!empty($student->class) and empty($student->teacher))
                  <a href="{{ route('form_teacher',$school->id) }}" class="btn btn-primary">
                    2.編排導師
                  </a>
                @endif
                @if(!empty($student->teacher))
                  <a href="{{ route('show_teacher',$school->id) }}" class="btn btn-outline-primary">
                    2.導師結果
                  </a>                   
                @endif
              </td>
              <td>
                @if(!empty($student->teacher))
                  <a href="{{ route('form_order',$school->id) }}" class="btn btn-primary">
                    3.編排班序
                  </a>
                @endif
              </td>
              <td>
                @if(!empty($student->class))
                <a href="{{ route('export',$school->id) }}" class="btn btn-secondary" target="_blank"><i class="bi bi-cloud-arrow-down-fill"></i> 下載</a>
                <a href="{{ route('print',$school->id) }}" class="btn btn-success" target="_blank"><i class="bi bi-printer-fill"></i> 列印</a>
                @endif
              </td>
              <td class="toggle-cell" style="display: {{ $display }}">
                @if(!empty($student))      
                  <a href="#!" class="btn btn-outline-danger" onclick="sw_confirm1('確定刪除名冊、編班及導師資料喔？','{{ route('delete123',$school->id) }}')">刪除名冊</a>
                @endif
                @if(!empty($student->class))
                  <a href="#!" class="btn btn-outline-warning" onclick="sw_confirm1('確定刪除編班及導師資料喔？','{{ route('delete23',$school->id) }}')">1.刪除編班</a>
                @endif
                @if(!empty($student->teacher))
                  <a href="#!" class="btn btn-outline-dark" onclick="sw_confirm1('確定刪除導師資料喔？','{{ route('delete3',$school->id) }}')">2.刪除導師</a>
                @endif
              </td>
            </tr>
            <?php $n++; ?>
          @endforeach
        </tbody>
      </table>
      <div class="toggle-cell" style="display: {{ $display }}">
        <p>
          <ul>
            <li>
              <i class="bi bi-check-circle text-success"></i> 表示該校已確定一切不再更改，按一下即可解鎖。
            </li>
            <li>
              <i class="bi bi-dash-circle text-dark"></i> 表示該校未確定學生編班屬性，按一下可強制上鎖不讓該校更改。
            </li>
            <li>
              <i class="bi bi-x-circle text-danger"></i> 表示該校未上傳任何學生。
            </li>
            <li>
              若上述學校列表有缺，請洽和東國小資訊組王老師。
            </li>
          </ul>
        </p>
      </div>
    </div>
  </div>
</section>
<script>
  function toggleCellVisibility() {
    const cells = document.querySelectorAll(".toggle-cell"); // 選取所有帶有 "toggle-cell" class 的 <td> 元素
    const checkbox = document.getElementById("toggleCheckbox");

    // 根據勾選狀態顯示或隱藏所有目標單元格
    cells.forEach(cell => {
        mode = document.getElementById("change_mode");
        if(checkbox.checked){
          cell.style.display = "none";
          setCookie('real{{ $school->group_id }}',1,100);
          mode.innerHTML = "正式執行";
        }else{
          cell.style.display = "";
          setCookie('real{{ $school->group_id }}',0,100);
          mode.innerHTML = "測試當中";
        }
        //cell.style.display = checkbox.checked ? "none" : ""; // 隱藏或顯示單元格        
    });
  }

  function setCookie(name, value, days) {
      let expires = "";
      if (days) {
          const date = new Date();
          date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
          expires = "; expires=" + date.toUTCString();
      }
      document.cookie = name + "=" + (value || "") + expires + "; path=/";
  }
</script>
@endsection