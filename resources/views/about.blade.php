@extends('layouts.master')

@section('page_title')
<br>
<h1>版權與系統功能</h1>
@endsection

@section('content')

<section class="section">
  <div class="row">    
    <div class="col-lg-12">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title">申明如下</h5>

          <div>
            <h6>一、版權申明</h6>
            <p>本系統<span class="text-danger">版權為本人( ET Wang )所有</span>，但可無償提供彰化縣各編班中心使用，我<span class="text-primary">沒有受縣府妥託此業務，也不負編班結果之責任，請了解本程式規則，詳加測試後，自行決定採用與否</span>。</p>
          </div>
          <hr>
          <div class="pt-2">
            <h6>二、程式功能</h6>
            <ol>
              <li>
                本程式盡量平均分配「總人數」、「男女生人數」及「所標註的特殊生」到各班，先編班，再編導師。
              </li>
              <li>
                特殊生(1)、雙胞胎同班(2)、雙胞胎不同班(3)等情況，請於 <a href="https://cloudschool.chc.edu.tw" target="_blank">cloudschool 校務系統</a> 上標註後，方才匯出 xlsx 檔。
              </li>
              <li>
                所匯入的 xlsx 檔，請由 cloudschool 系統產出，並注意名單及人數是否為正確。
              </li>
              <li>
                雙胞胎對應的另一位新生，請注意流水號是否完整正確，如為 A0001，而非 0001。
              </li>
              <li>
                分配到特殊生的班級，系統預設該班酌減一人，請注意是否有班級因此而被分配到一班超過法定上限人數 28 人，尤其是滿編或總量管制的學校又有特殊生時，若超過28人將彈出警告視窗。
              </li>
              <li>
                本系統可以更改特殊生酌減的人數，依規定至多3人。
              </li>
              <li>
                本系統可以特殊生綁定指定導師。
              </li>
              <li>
                本系統可以子女避開父母為導師。
              </li>
            </ol>
            <img src="{{ asset('cloudschool.png') }}" width="80%">
          </div>          
          <hr>
          <div class="pt-2">
            <h6>三、程式沿革</h6>
            <ol>
              <li>
                本系統自2024.7月開發，2024.11月完成，藉以取代舊系統，以達成新的編班規定要求。
              </li>
            </ol>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection