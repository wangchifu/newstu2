@extends('layouts.master')

@section('page_title')
<h1>{{ $semester_year }}學年{{ $school->name }}編班結果</h1>
@endsection

@section('content')

<section class="section">  
  <div class="row">
    <div class="card">
      <div class="card-body">
        <table class="table table-striped">
          <thead>
            <tr>
              <th colspan="5">
                <a href="{{ route('start') }}" class="btn btn-secondary"><i class="bi bi-chevron-double-left"></i> 返回</a> 全部分班一覽 <span class="small text-danger">◎請檢查是否流水號重複，如有缺號乃屬正常</span>
              </th>
            </tr>
          </thead>
          <tbody>
            @for($i=0;$i<$school->class_num;$i++)
              <tr>
                <th colspan="5">
                  {{ $eng_class[$i] }}班 {{ $student_data[$eng_class[$i]]['all'] }}
                  @if($student_data[$eng_class[$i]]['subtract'] > 0)
                    <span class="text-danger">+{{ $student_data[$eng_class[$i]]['subtract'] }}</span>
                  @endif
                  人                  
                  (男：{{ $student_data[$eng_class[$i]]['boy'] }} 女：{{ $student_data[$eng_class[$i]]['girl'] }})
                </th>
              </tr>              
              <tr>
              <?php $n=1; ?>
              @foreach($student_data[$eng_class[$i]]['st'] as $k=>$v)
                <td width="20%">
                  <small class="text-secondary">{{ $v['no'] }}</small>
                  @if($v['sex']==1)
                    <img src="{{ asset('img/boy.png') }}">
                  @endif
                  @if($v['sex']==2)
                    <img src="{{ asset('img/girl.png') }}">
                  @endif
                  {{ sprintf("%02s",$k) }}號                  
                  @if($v['sex']==1)
                    <span class="text-primary">{{ $v['name'] }}</span>
                  @endif
                  @if($v['sex']==2)
                    <span class="text-danger">{{ $v['name'] }}</span>
                  @endif
                  @if($v['special'] == 1)
                    *
                  @endif                  
                </td>
                @if($n%5 == 0)
                  </tr><tr>
                @endif
                <?php $n++; ?>
              @endforeach
            @endfor
          </tbody>
        </table>
      </div>
    </div>
  </div>
</section>
@endsection