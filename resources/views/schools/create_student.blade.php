@extends('layouts.master')

@section('page_title')
<h1>新增學生類別</h1>
@endsection

@section('content')

<section class="section"> 
  <div class="row">
    <div class="col-xl-4">

      <div class="card">
        <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">
          <img src="{{ asset('img/user.png') }}" alt="Profile" class="rounded-circle">
          <br>                    
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
              <form action="{{ route('store_student') }}" method="post" id="student_data">
                @csrf  
              <div class="row">
                <label class="col-sm-2 col-form-label text-danger">流水號</label>
                <div class="col-sm-10">
                  <input class="form-control" text="text" name="no" placeholder="流水號" required>                  
                </div>
              </div>  
              <div class="row">
                <label class="col-sm-2 col-form-label text-danger">姓名</label>
                <div class="col-sm-10">
                  <input class="form-control" text="text" name="name" placeholder="姓名" required>                  
                </div>
              </div>   
              <div class="row">
                <label class="col-sm-2 col-form-label text-danger">身分證字號</label>
                <div class="col-sm-10">
                  <input class="form-control" text="text" name="id_number" placeholder="身分證字號" required>                  
                </div>
              </div>     
              <div class="row">
                <label class="col-sm-2 col-form-label text-danger">性別</label>
                <div class="col-sm-10">
                  <select class="form-select" aria-label="Default select example" name="sex" id="sex">
                    <option value="1" style="color:blue">男生</option>
                    <option value="2" style="color:red">女生</option>                    
                  </select>
                </div>
              </div>     
              <div class="row">
                <label class="col-sm-2 col-form-label text-danger">身分別</label>
                <div class="col-sm-10">
                  <select class="form-select" aria-label="Default select example" name="special" id="special">                    
                    <option value="">一般生</option>
                    <option value="1">特殊生</option>                    
                  </select>
                </div>
              </div>

              <div class="row">
                <label class="col-sm-2 col-form-label text-danger">特殊生減人數</label>
                <div class="col-sm-10">
                  <select class="form-select" aria-label="Default select example" name="subtract" id="subtract">                    
                    <option value="0">0人</option></option>
                    <option value="1">1人</option></option>
                    <option value="2">2人</option></option>
                    <option value="3">3人</option></option>                 
                  </select>
                </div>
              </div>

              <div class="row">
                <label for="inputText" class="col-sm-2 col-form-label text-danger">特殊生綁定老師</label>
                <div class="col-sm-10">
                  <select class="form-select" aria-label="Default select example" name="with_teacher">
                    <option value="">--</option>
                    @foreach($teachers as $teacher)                      
                      <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                    @endforeach
                  </select>
                </div>
              </div>

              <div class="row">
                <label class="col-sm-2 col-form-label text-primary">N胞胎</label>
                <div class="col-sm-10">
                  <select class="form-select" aria-label="Default select example" id="type" name="type">                    
                    <option value="no">無</option>
                    <option value="2">雙胞胎要同班</option>                    
                    <option value="3">雙胞胎不同班</option>                    
                    <option value="4">三胞胎全同班</option>                    
                    <option value="5">三胞胎全不同班</option>                    
                  </select>
                </div>
              </div>
              <div class="row">
                <label for="inputText" class="col-sm-2 col-form-label text-primary">同胎者2流水號</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" name="bao1" placeholder="無則免填">
                </div>
              </div>
              <div class="row">
                <label for="inputText" class="col-sm-2 col-form-label text-primary">同胎者3流水號</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" name="bao2" placeholder="無則免填">
                </div>
              </div>
              <div class="row">
                <label for="inputText" class="col-sm-2 col-form-label text-success">標註父母親是老師</label>
                <div class="col-sm-10">
                  <select class="form-select" aria-label="Default select example" name="without_teacher">                    
                    <option value="">--</option>
                    @foreach($teachers as $teacher)                      
                      <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <hr>
              @include('layouts.errors')
              <a href="{{ route('student_type') }}" class="btn btn-secondary"><i class="bi bi-chevron-double-left"></i> 返回</a>
              <a href="#!" class="btn btn-primary" onclick="sw_confirm2('確定？','student_data')"><i class="bi bi-arrow-right-circle-fill"></i> 確定新增</a>
              </form>
        </div>
      </div>

    </div>
  </div>
</section>
<script
			  src="https://code.jquery.com/jquery-3.7.1.min.js"
			  integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo="
			  crossorigin="anonymous"></script>
<script>
  $(document).ready(function(){
    function checkCondition() {
        var specialVal = $('#special').val();
        var subtractVal = $('#subtract').val();

        if (specialVal == '1' && subtractVal == '0') {
            sw_alert("操作確定","確定特殊生不減任何人？", "warning");
            $('#subtract').focus();
        }
    }

    // 當任一 select 改變時就檢查
    $('#special, #subtract').on('change', function(){
        checkCondition();
    });

    // 頁面載入時先檢查一次 (依需要)
    checkCondition();
});
</script>
@endsection