@extends('layouts.master')

@section('page_title')
<h1>{{ $semester_year }}學年學生編班類別設定</h1>
@endsection

@section('content')

<section class="section">
  
<div class="row">
  <div class="card">
    <div class="card-body">
      <br>
      <?php           
        $active1 = 'show active';
        $active2 = '';
        if(!empty(session('code'))) {
          if(session('code')=="074603-1" or session('code')=="074774"){
            $active1 = '';
            $active2 = 'show active';
          }
        }          
      ?>
      <nav>
      <div class="nav nav-tabs" id="nav-tab" role="tablist">
        <button class="nav-link {{ $active1 }}" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home" aria-selected="true">{{ auth()->user()->school->name }}</button>
        @if(auth()->user()->school->code=="074603")
          <button class="nav-link {{ $active2 }}" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile" aria-selected="false">建和分校</button>          
        @endif
        @if(auth()->user()->school->code=="074541")
          <button class="nav-link {{ $active2 }}" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile" aria-selected="false">信義國小</button>          
        @endif
      </div>
      </nav>
      <div class="tab-content" id="nav-tabContent">
        <div class="tab-pane fade {{ $active1 }}" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
          <h5 class="card-title">{{ $semester_year }}學年已匯入的學生名單</h5>   
          <h4>{{ auth()->user()->school->name }}</h4>
          <table class="table table-bordered">
            <tr>
              <td width="50%">
                一般生：{{ $type[0] }}
              </td>
              <td width="50%">
                特殊生：{{ $type[1] }} (共減 {{ $subtract }} 人)：
                @foreach($spacial_student as $k=>$v)
                  <?php $wt=(!empty($student_data[$semester_year][$k]['with_teacher']))?$student_data[$semester_year][$k]['with_teacher']:"<span class='text-danger'>未設定</span>" ?>
                  <span class="badge bg-success position-relative" style="font-size: 15px;">{{ $student_data[$semester_year][$k]['no']." ".$v }}
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                      -{{ $student_data[$semester_year][$k]['subtract'] }}
                    </span>                
                  </span><span class="badge bg-info">+{!! $wt !!}</span>
                @endforeach
              </td>
            </tr>  
            <tr>
              <td>
                雙胞胎同班：{{ $type[2] }}：
                @foreach($bao2_same as $k=>$v)
                <span class="badge bg-warning" style="font-size: 15px;"><i class="bi bi-check-circle-fill"></i> {{ $student_data[$semester_year][$k]['no']." ".$v }}</span><span class="badge bg-info">+{{ $student_data[$semester_year][$k]['another_no'] }}</span> 
                @endforeach
              </td>
              <td>
                雙胞胎不同班：{{ $type[3] }}：
                @foreach($bao2_not_same as $k=>$v)
                <span class="badge bg-secondary" style="font-size: 15px;"><i class="bi bi-x-circle-fill"></i> {{ $student_data[$semester_year][$k]['no']." ".$v }}</span><span class="badge bg-info">+{{ $student_data[$semester_year][$k]['another_no'] }}</span>
                @endforeach
              </td>
            </tr>
            <tr>
              <td>
                三胞胎全同班：{{ $type[4] }}：
                @foreach($bao3_same as $k=>$v)
                <span class="badge bg-info" style="font-size: 15px;"><i class="bi bi-check-circle-fill"></i> {{ $student_data[$semester_year][$k]['no']." ".$v }}</span>
                @endforeach
              </td>
              <td>
                三胞胎全不同班：{{ $type[5] }}：
                @foreach($bao3_not_same as $k=>$v)
                <span class="badge bg-dark" style="font-size: 15px;"><i class="bi bi-x-circle-fill"></i> {{ $student_data[$semester_year][$k]['no']." ".$v }}</span>
                @endforeach
              </td>
            </tr>
            <tr>
              <td>
                父母之一是此年級老師：
                @foreach($with_out_teacher as $k=>$v)
                <span class="badge bg-primary position-relative" style="font-size: 15px;">
                  {{ $student_data[$semester_year][$k]['no']." ".$v['student'] }}
                  <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                    -{{ $v['teacher'] }}
                  </span>
                </span>
                @endforeach
              </td>
              <td>
                
              </td>
            </tr>
          </table>        
          <hr>
          @if(!$school->ready==1)
            <table>
              <tr>
                <td>
                  <a href="{{ route('create_student') }}" class="btn btn-outline-success">新增學生</a>
                </td>
                <td>
                  <form action="{{ route('school_ready') }}" method="post" id="go_send">
                    @csrf
                    <input type="hidden" name="semester_year" value="{{ $semester_year }}">
                    @include('layouts.errors')      
                    <input type="hidden" name="code" value="{{ $school->code }}">
                    <a href="#" class="btn btn-primary" onclick="sw_confirm2('上傳編班中心不能再更改了喔！','go_send')"><i class="bi bi-arrow-right-circle-fill"></i> 確定送出不再修改</a>
                  </form>
                </td>
              </tr>
            </table>                  
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
          <p class="text-danger">**注意：若學校滿編，特殊生再設定減人，必定會造成某些班級超過28人上限！，此時唯有設定特殊生減0人，才能班班28人**</p>
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
                @if(!$school->ready==1)
                  <th scope="col">動作</th>
                @endif
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
                        <h5>
                        <span class="badge rounded-pill bg-success position-relative">
                          特殊生
                          <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            -{{ $v1['subtract'] }}
                          </span>
                        </span>                        
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
                    @if(!$school->ready==1)
                      <td>
                        <a href="{{ route('edit_student',$k1) }}" class="btn btn-outline-primary">修改</a>
                        <a href="#!" class="btn btn-outline-danger" onclick="sw_confirm1('確定刪除 {{ $v1['name'] }} ？記得在校務系統也要在「新生作業」將此生標記「不就讀」喔！','{{ route('delete_student',$k1) }}')">刪除</a>
                      </td>
                    @endif
                  </tr>
                  <?php $n++; ?>  
                @endforeach
              @endforeach
            </tbody>
          </table>
        </div>
        @if(auth()->user()->school->code=="074603" or auth()->user()->school->code=="074541")
          <div class="tab-pane fade {{ $active2 }}" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
            <h5 class="card-title">{{ $semester_year2 }}學年已匯入的學生名單</h5>   
            @if(auth()->user()->school->code=="074603")
              <h4>建和分校</h4>
            @endif
            @if(auth()->user()->school->code=="074541")
              <h4>信義國小</h4>
            @endif            
            <table class="table table-bordered">
              <tr>
                <td width="50%">
                  一般生：{{ $type2[0] }}
                </td>
                <td width="50%">
                  特殊生：{{ $type2[1] }} (共減 {{ $subtract2 }} 人)：
                  @foreach($spacial_student2 as $k=>$v)
                    <?php $wt=(!empty($student_data2[$semester_year2][$k]['with_teacher']))?$student_data2[$semester_year2][$k]['with_teacher']:"<span class='text-danger'>未設定</span>" ?>
                    <span class="badge bg-success position-relative" style="font-size: 15px;">{{ $student_data2[$semester_year2][$k]['no']." ".$v }}
                      <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        -{{ $student_data2[$semester_year2][$k]['subtract'] }}
                      </span>                
                    </span><span class="badge bg-info">+{!! $wt !!}</span>
                  @endforeach
                </td>
              </tr>  
              <tr>
                <td>
                  雙胞胎同班：{{ $type2[2] }}：
                  @foreach($bao2_same2 as $k=>$v)
                  <span class="badge bg-warning" style="font-size: 15px;"><i class="bi bi-check-circle-fill"></i> {{ $student_data2[$semester_year2][$k]['no']." ".$v }}</span><span class="badge bg-info">+{{ $student_data2[$semester_year2][$k]['another_no'] }}</span> 
                  @endforeach
                </td>
                <td>
                  雙胞胎不同班：{{ $type2[3] }}：
                  @foreach($bao2_not_same2 as $k=>$v)
                  <span class="badge bg-secondary" style="font-size: 15px;"><i class="bi bi-x-circle-fill"></i> {{ $student_data2[$semester_year2][$k]['no']." ".$v }}</span><span class="badge bg-info">+{{ $student_data2[$semester_year2][$k]['another_no'] }}</span>
                  @endforeach
                </td>
              </tr>
              <tr>
                <td>
                  三胞胎全同班：{{ $type2[4] }}：
                  @foreach($bao3_same2 as $k=>$v)
                  <span class="badge bg-info" style="font-size: 15px;"><i class="bi bi-check-circle-fill"></i> {{ $student_data2[$semester_year2][$k]['no']." ".$v }}</span>
                  @endforeach
                </td>
                <td>
                  三胞胎全不同班：{{ $type2[5] }}：
                  @foreach($bao3_not_same2 as $k=>$v)
                  <span class="badge bg-dark" style="font-size: 15px;"><i class="bi bi-x-circle-fill"></i> {{ $student_data2[$semester_year2][$k]['no']." ".$v }}</span>
                  @endforeach
                </td>
              </tr>
              <tr>
                <td>
                  父母之一是此年級老師：
                  @foreach($with_out_teacher2 as $k=>$v)
                  <span class="badge bg-primary position-relative" style="font-size: 15px;">
                    {{ $student_data2[$semester_year2][$k]['no']." ".$v['student'] }}
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                      -{{ $v['teacher'] }}
                    </span>
                  </span>
                  @endforeach
                </td>
                <td>
                  
                </td>
              </tr>
            </table>        
            <hr>
            @if(!$school2->ready==1)
              <table>
                <tr>
                  <td>
                    @if(auth()->user()->school->code=="074603")
                      <a href="{{ route('create_student','074603-1') }}" class="btn btn-outline-success">新增學生</a>
                    @endif
                    @if(auth()->user()->school->code=="074541")
                      <a href="{{ route('create_student','074774') }}" class="btn btn-outline-success">新增學生</a>
                    @endif                    
                  </td>
                  <td>
                    <form action="{{ route('school_ready') }}" method="post" id="go_send2">
                      @csrf
                      <input type="hidden" name="semester_year" value="{{ $semester_year2 }}">
                      @include('layouts.errors')   
                      <input type="hidden" name="code" value="{{ $school2->code }}">         
                      <a href="#" class="btn btn-primary" onclick="sw_confirm2('上傳編班中心不能再更改了喔！','go_send2')"><i class="bi bi-arrow-right-circle-fill"></i> 確定送出不再修改</a>
                    </form>
                  </td>
                </tr>
              </table>                  
            @else          
              @if(!empty($school2->situation))
                <p><span class="text-success">**編班完成**</span></p>
                <a href="{{ route('school_show_class','17') }}" class="btn btn-outline-primary">編班結果</a>
                <a href="{{ route('school_export','17') }}" class="btn btn-success" target="_blank"><i class="bi bi-cloud-arrow-down-fill"></i> 下載本校編班檔</a>
              @else
                <p><span class="text-danger">**已送交編班中心無法再更動**真有需求時請編班中心打開上鎖**</span></p>
                <p><span class="text-danger">**尚未編班**</span></p>
              @endif
            @endif  
            <p class="text-danger">**注意：若學校滿編，特殊生再設定減人，必定會造成某些班級超過28人上限！，此時唯有設定特殊生減0人，才能班班28人**</p>          
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
                  @if(!$school2->ready==1)
                    <th scope="col">動作</th>
                  @endif
                </tr>
              </thead>
              <tbody>
                <?php $n=1; ?>
                @foreach($student_data2 as $k=>$v)
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
                          <h5>
                          <span class="badge rounded-pill bg-success position-relative">
                            特殊生
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                              -{{ $v1['subtract'] }}
                            </span>
                          </span>                        
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
                      @if(!$school2->ready==1)
                        <td>
                          <a href="{{ route('edit_student',$k1) }}" class="btn btn-outline-primary">修改</a>
                          <a href="#!" class="btn btn-outline-danger" onclick="sw_confirm1('確定刪除 {{ $v1['name'] }} ？記得在校務系統也要在「新生作業」將此生標記「不就讀」喔！','{{ route('delete_student',$k1) }}')">刪除</a>
                        </td>
                      @endif
                    </tr>
                    <?php $n++; ?>  
                  @endforeach
                @endforeach
              </tbody>
            </table>            
          </div>  
        @endif        
      </div>
    </div>
  </div>
</div>
  
</section>
@endsection