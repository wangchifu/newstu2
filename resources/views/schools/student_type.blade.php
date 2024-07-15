@extends('layouts.master')

@section('page_title')
<h1>{{ $semester_year }}學年學生編班類別</h1>
@endsection

@section('content')

<section class="section">
  @if(!empty($student_data))
  <div class="row">
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">{{ $semester_year }}學年已匯入的學生名單</h5>   
        <h4>{{ auth()->user()->school->name }}</h4>
        <table>
          <tr>
            <td>
              一般生：{{ $type[0] }}
            </td>
            <td>
              特殊生：{{ $type[1] }} (共減 {{ $subtract }} 人)
            </td>
          </tr>  
          <tr>
            <td>
              雙胞胎同班：{{ $type[2] }}
            </td>
            <td>
              雙胞胎不同班：{{ $type[3] }}
            </td>
          </tr>
          <tr>
            <td>
              三胞胎全同班：{{ $type[4] }}
            </td>
            <td>
              三胞胎全不同班：{{ $type[5] }}
            </td>
          </tr>
        </table>             
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
              <th scope="col">編班類別</th>
              <th scope="col">相關流水號</th>
              <th scope="col">動作</th>
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
                    @if($v1['sex'] == 1)
                      <img src="{{ asset('img/boy.png') }}"> <span class="text-primary">男</span>
                    @endif
                    @if($v1['sex'] == 2)
                      <img src="{{ asset('img/girl.png') }}"> <span class="text-danger">女</span>
                    @endif                    
                  </td>
                  <td>
                    {{ $v1['name'] }}
                  </td>
                  <td>
                    {{ $v1['id_number'] }}
                  </td>
                  <td>
                    @if($v1['type']==2)
                      <h5><span class="badge bg-warning"><i class="bi bi-check-circle-fill"></i> 雙胞胎同班</span></h5>
                    @endif
                    @if($v1['type']==3)
                      <h5><span class="badge bg-secondary"><i class="bi bi-x-circle-fill"></i> 雙胞胎不同班</span></h5>
                    @endif
                    @if($v1['type']==4)
                      <h5><span class="badge bg-info"><i class="bi bi-check-circle-fill"></i> 三胞胎全同班</span></h5>
                    @endif
                    @if($v1['type']==5)
                      <h5><span class="badge bg-dark"><i class="bi bi-x-circle-fill"></i>三胞胎全不同班</span></h5>
                    @endif
                    @if($v1['special']==null)
                      <h5>
                        <span class="badge rounded-pill bg-primary">一般生</span>
                        @if(!empty($v1['without_teacher']))
                          <span class="badge bg-danger">-{{ $v1['without_teacher'] }}</span>
                        @endif
                      </h5>
                    @endif
                    @if($v1['special']==1)
                      <h5><span class="badge rounded-pill bg-success">特殊生</span>
                        <span class="badge bg-warning">-{{ $v1['subtract'] }}</span>
                        @if(!empty($v1['with_teacher']))
                          <span class="badge bg-info">+{{ $v1['with_teacher'] }}</span>
                        @endif
                        @if(!empty($v1['without_teacher']))
                          <span class="badge bg-danger">-{{ $v1['without_teacher'] }}</span>
                        @endif
                      </h5>
                    @endif                    
                  </td>
                  <td>
                    {{ $v1['another_no'] }}
                  </td>
                  <td>
                    <a href="{{ route('edit_student',$k1) }}" class="btn btn-outline-primary">修改</a>
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