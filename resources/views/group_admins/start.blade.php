@extends('layouts.master')

@section('page_title')
<h1>學校編班作業</h1>
@endsection

@section('content')

<section class="section">
  <div class="card">
    <div class="card-body">
      <h5 class="card-title">「{{ $group->name }}」學校列表</h5>   
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
            <th scope="col">
              刪除 <small class="text-danger">[全部刪除]</small>
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
                  <a href="{{ route('show_student',$school->id) }}">
                    <small class="text-primary">[檢視]</small>
                  </a>
                @else
                  <small class="text-info">[未上傳]</small>
                @endif
              </td>
              <td>
                @if($school->ready)
                <a href="#" onclick="sw_confirm1('確定？','{{ route('group_admin_unlock',$school->id) }}')">
                  <i class="bi bi-check-circle text-success"></i>
                </a>
                @else
                  @if(!empty($student))
                    <a href="#" onclick="sw_confirm1('確定？','{{ route('group_admin_unlock',$school->id) }}')">
                      <i class="bi bi-x-circle text-danger"></i>
                    </a>
                  @else
                  <i class="bi bi-dash-circle text-dark"></i>
                  @endif
                @endif
              </td>
              <td>
               @if(!empty($student->class))
                <span class="text-success">已經編班</span>
               @else
                <span class="text-danger">尚未編班</span>
               @endif
              </td>
              <td>
                @if($school->ready)
                  <a href="{{ route('form_class',$school->id) }}">
                    <span class="text-primary">進行編班</span>
                  </a>
                @endif
              </td>
            </tr>
            <?php $n++; ?>
          @endforeach
        </tbody>
      </table>
      <p>
        <ul>
          <li>
            <i class="bi bi-check-circle text-success"></i> 表示該校已確定一切不再更改，按一下即可解鎖。
          </li>
          <li>
            <i class="bi bi-x-circle text-danger"></i> 表示該校未確定學生編班屬性，按一下可強制上鎖不讓該校更改。
          </li>
          <li>
            <i class="bi bi-dash-circle text-dark"></i> 表示該校未上傳任何學生。
          </li>
          <li>
            若上述學校列表有缺，請洽和東國小資訊組王老師。
          </li>
        </ul>
      </p>
    </div>
  </div>
</section>
@endsection