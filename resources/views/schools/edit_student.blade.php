@extends('layouts.master')

@section('page_title')
<h1>修改學生類別</h1>
@endsection

@section('content')

<section class="section"> 
  <div class="row">
    <div class="col-xl-4">

      <div class="card">
        <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">
          @if($student->sex==1)
            <img src="{{ asset('img/boy2.png') }}" alt="Profile" class="rounded-circle">          
          @endif
          @if($student->sex==2)
            <img src="{{ asset('img/girl2.png') }}" alt="Profile" class="rounded-circle">
          @endif
          <br>
          <h2>
            {{ $student->name }}
            @if($student->sex==1)
             <span class="text-primary">(男)</span>
            @endif
            @if($student->sex==2)
             <span class="text-danger">(女)</span>
            @endif
          </h2>
          <h3>{{ $student->no }}</h3>
          <div class="social-links mt-2">
            
          </div>
        </div>
      </div>

    </div>

    <div class="col-xl-8">

      <div class="card">
        <div class="card-body pt-3">
          <!-- Bordered Tabs -->
          <h5 class="card-title">編班類別</h5>
              <p class="small fst-italic">
                特殊生可減1~3人，並記得綁定導師名單。多胞胎記得填上兄弟姐妹的流水號。父母剛好是一年級導師，記得標註以避掉教自己小孩。
              </p>
              <form action="{{ route('update_student',$student->id) }}" method="post" id="student_data">
                @csrf              
              <div class="row">
                <label class="col-sm-2 col-form-label text-danger">身分別</label>
                <div class="col-sm-10">
                  <select class="form-select" aria-label="Default select example" name="special">
                    <?php 
                      $select0 = ($student->special)?null:"selected";  
                      $select1 = ($student->special)?"selected":null;
                    ?>
                    <option {{ $select0 }} value="">一般生</option>
                    <option {{ $select1 }} value="1">特殊生</option>                    
                  </select>
                </div>
              </div>

              <div class="row">
                <label class="col-sm-2 col-form-label text-danger">特殊生減人數</label>
                <div class="col-sm-10">
                  <select class="form-select" aria-label="Default select example" name="subtract">
                    <?php 
                      if($student->subtract==0){
                        $select0 = "selected";
                        $select1 = null;
                        $select2 = null;
                        $select3 = null;
                      }
                      if($student->subtract==1){
                        $select0 = null;
                        $select1 = "selected";
                        $select2 = null;
                        $select3 = null;
                      }
                      if($student->subtract==2){
                        $select0 = null;
                        $select1 = null;
                        $select2 = "selected";
                        $select3 = null;
                      }
                      if($student->subtract==3){
                        $select0 = null;
                        $select1 = null;
                        $select2 = null;
                        $select3 = "selected";
                      }
                    ?>
                    <option value="0" {{ $select0 }}>--</option></option>
                    <option value="1" {{ $select1 }}>1人</option></option>
                    <option value="2" {{ $select2 }}>2人</option></option>
                    <option value="3" {{ $select3 }}>3人</option></option>                 
                  </select>
                </div>
              </div>

              <div class="row">
                <label for="inputText" class="col-sm-2 col-form-label text-danger">特殊生綁定老師</label>
                <div class="col-sm-10">
                  <select class="form-select" aria-label="Default select example" name="with_teacher">
                    <option value="">--</option>
                    @foreach($teachers as $teacher)
                      <?php $selected = ($teacher->id==$student->with_teacher)?"selected":null; ?>
                      <option value="{{ $teacher->id }}" {{ $selected }}>{{ $teacher->name }}</option>
                    @endforeach
                  </select>
                </div>
              </div>

              <div class="row">
                <label class="col-sm-2 col-form-label text-primary">N胞胎</label>
                <div class="col-sm-10">
                  <select class="form-select" aria-label="Default select example" id="type" name="type">
                    <?php 
                      if($student->type==0 or $student->type==1){
                        $select0 = "selected";
                        $select1 = null;
                        $select2 = null;
                        $select3 = null;
                        $select4 = null;
                        $select5 = null;
                      }
                      if($student->type==2){
                        $select0 = null;
                        $select1 = null;
                        $select2 = "selected";
                        $select3 = null;
                        $select4 = null;
                        $select5 = null;
                      }
                      if($student->type==3){
                        $select0 = null;
                        $select1 = null;
                        $select2 = null;
                        $select3 = "selected";
                        $select4 = null;
                        $select5 = null;
                      }
                      if($student->type==4){
                        $select0 = null;
                        $select1 = null;
                        $select2 = null;
                        $select3 = null;
                        $select4 = "selected";
                        $select5 = null;
                      }
                      if($student->type==5){
                        $select0 = null;
                        $select1 = null;
                        $select2 = null;
                        $select3 = null;
                        $select4 = null;
                        $select5 = "selected";
                      }

                    ?>
                    <option {{ $select0 }} value="no">無</option>
                    <option {{ $select2 }} value="2">雙胞胎要同班</option>                    
                    <option {{ $select3 }} value="3">雙胞胎不同班</option>                    
                    <option {{ $select4 }} value="4">三胞胎全同班</option>                    
                    <option {{ $select5 }} value="5">三胞胎全不同班</option>                    
                  </select>
                </div>
              </div>
              <?php
                $bao = explode(',',$student->another_no);
                if(empty($bao[0])) $bao[0] = null;
                if(empty($bao[1])) $bao[1] = null;
              ?>
              <div class="row">
                <label for="inputText" class="col-sm-2 col-form-label text-primary">同胎者2流水號</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" name="bao1" value="{{ $bao[0]}}" placeholder="無則免填">
                </div>
              </div>
              <div class="row">
                <label for="inputText" class="col-sm-2 col-form-label text-primary">同胎者3流水號</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" name="bao2" value="{{ $bao[1] }}" placeholder="無則免填">
                </div>
              </div>
              <div class="row">
                <label for="inputText" class="col-sm-2 col-form-label text-success">標註父母親是老師</label>
                <div class="col-sm-10">
                  <select class="form-select" aria-label="Default select example" name="without_teacher">                    
                    <option value="">--</option>
                    @foreach($teachers as $teacher)
                      <?php $selected = ($teacher->id==$student->without_teacher)?"selected":null; ?>
                      <option value="{{ $teacher->id }}" {{ $selected }}>{{ $teacher->name }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <hr>
              @include('layouts.errors')
              <a href="#" class="btn btn-secondary" onclick="history.back ();"><i class="bi bi-chevron-double-left"></i> 返回</a>
              <a href="#" class="btn btn-primary" onclick="sw_confirm2('確定？','student_data')"><i class="bi bi-arrow-right-circle-fill"></i> 確定修改</a>
              </form>
        </div>
      </div>

    </div>
  </div>
</section>
@endsection