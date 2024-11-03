@extends('layouts.master')

@section('page_title')
<h1>{{ $school->name }}編排班序</h1>
@endsection

@section('content')
<style>
  /* 簡單的過場動畫樣式 */
  #loading {
      display: none;
      position: fixed;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      font-size: 24px;
      color: red; 
      background-color: #ffe6e6; 
      border: 2px solid red; 
      border-radius: 8px;
      padding: 10px;                  
  }
</style>
<section class="section">
  <div class="row">
    <div class="container justify-content-center">
      <div class="card">
        <div class="card-body">
            <form method="post" action="{{ route('go_form_order',$school->id) }}" id="go_form">
            @csrf
            <table class="table table-striped">
              <thead>
                <tr>
                  <th colspan="2">                    
                    <h2 class="text-primary">導師班序編排填寫表單</h2>
                  </th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td style="width:300px;" nowrap>
                    <h3>編班學校名稱</h3>
                  </td>
                  <td>
                    <h3 class="text-danger">{{ $school->name }}</h3>
                  </td>
                </tr>
                <tr>
                  <td nowrap>
                    <h3>已送出導師名冊</h3>
                  </td>
                  <td>
                    <table class="table table-bordered table-hover w-50">
                      <thead class="table-primary">
                        <tr>
                          <th style="width:50%">
                            原本
                          </th>
                          <th>
                            改為
                          </th>
                        </tr>
                      </thead>     
                      <tbody>
                      @for($i=0;$i<$school->class_num;$i++)
                        <?php 
                          $check_student = \App\Models\Student::where('code',$school->code)->where('class',$eng_class[$i])->first();
                        ?>
                        <tr>
                          <td>
                            {{ $eng_class[$i] }}班 導師：{{ $check_student->teacher->name }}
                          </td>
                          <td>
                            <select name="teacher[{{ $eng_class[$i] }}]" class="select-option form-control">
                              @for($n=0;$n<$school->class_num;$n++)
                                <option value="{{ $eng_class[$n] }}">
                                  {{ $eng_class[$n] }}班
                                </option>
                              @endfor
                            </select>
                          </td>
                        </tr>
                      @endfor
                      </tbody>                 
                    </table>
                  </td>
                </tr>
              </tbody>            
            </table>                      
            </form>
            @include('layouts.errors')
            <a href="{{ route('start') }}" class="btn btn-secondary"><i class="bi bi-chevron-double-left"></i> 返回</a>            
            <a href="#" class="btn btn-primary" onclick="check_repeat('go_form')"><i class="bi bi-play-fill"></i> 確定班序</a>                        
        </div>      
      </div>
    </div>    
  </div>
</section>
<div id="loading" style=""></div>
<script>
    function check_repeat(id){
      const selectElements = document.querySelectorAll('.select-option'); // 取得所有 select 元素
      const selectedValues = [];

      // 遍歷所有 select 元素並獲取值
      selectElements.forEach(select => {
          const value = select.value;
          if (value) { // 排除空選項
              selectedValues.push(value);
          }
      });

      // 檢查是否有重複的選項
      const hasDuplicates = selectedValues.some((item, index) => selectedValues.indexOf(item) !== index);

      if (hasDuplicates) {
          sw_alert('錯誤！','有選到重複的班級！','error');
      }else{
        sw_confirm3('確定？',id);
      }
    }


      // 文字內容
      const text = "程式處理中，請稍後...";
      const loadingDiv = document.getElementById('loading');
      let index = 0;

      // 打字效果函數
      function typeEffect() {
          if (index < text.length) {
              loadingDiv.innerHTML += text.charAt(index);
              index++;
              setTimeout(typeEffect, 150); // 每個字母顯示的間隔時間（150毫秒）
          } else {
              setTimeout(() => {
                  loadingDiv.innerHTML = ""; // 清除文字
                  index = 0; // 重置索引
                  typeEffect(); // 重新啟動打字效果
              }, 1000); // 延遲一秒後重新開始
          }
      }

  function sw_confirm3(message,id) {
              Swal.fire({
                  title: "操作確認",
                  text: message,
                  showCancelButton: true,
                  confirmButtonText:"確定",
                  cancelButtonText:"取消",
              }).then(function(result) {                
                 if (result.value) {                  
                  showLoading();
                  // 啟動打字效果
                  typeEffect();
                  document.getElementById(id).submit();
                 }
                 else {
                    return false;
                 }
              });
          }

    function showLoading() {
        document.getElementById('loading').style.display = 'block';
    }
    
    // 隱藏過場動畫
    function hideLoading() {
        document.getElementById('loading').style.display = 'none';
    }
    hideLoading();
</script>
@endsection