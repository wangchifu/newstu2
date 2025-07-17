@extends('layouts.master')

@section('carousel')
<div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
  <div class="carousel-inner">
    <div class="carousel-item active">
      <img src="{{ asset('img/slides-1.png') }}" class="d-block w-100" alt="...">
    </div>
    <div class="carousel-item">
      <img src="{{ asset('img/slides-2.png') }}" class="d-block w-100" alt="...">
    </div>
  </div>

  <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Previous</span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Next</span>
  </button>
</div>
@endsection

@section('page_title')
<br>
<h1>歡迎使用</h1>
<i class="bi bi-star-fill text-warning"></i> 為編班中心學校，各分區尚未取得編班權限的學校，請聯絡目前有權限的學校轉移權限。
@endsection

@section('content')

<section class="section">
  <div class="row">
    @foreach($groups as $group)
      <div class="col-xxl-3 col-md-3 col-sm-6">
        <div class="card info-card sales-card">
          <div class="card-body">
            <h5 class="card-title">{{ $group->name }} <span>| 學校名單</span></h5>            
            <div class="d-flex align-items-center">
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col" nowrap>學校代碼</th>
                    <th scope="col" nowrap>學校名稱</th>
                    <th scope="col" nowrap>已編</th>                    
                  </tr>
                </thead>
                <tbody>
                  <?php $n=1; ?>
                  @foreach($group->schools as $school)
                    <tr>
                      <th scope="row">{{ $n }}</th>
                      <td>{{ $school->code }}</td>
                      <td>
                        @if($school->group_admin)
                          <i class="bi bi-star-fill text-warning"></i>
                        @endif
                        {{ $school->name }}
                      </td>
                      <td>
                        @if(!empty($school->situation))
                          <i class="bi bi-check-circle text-success"></i>
                        @else
                          <i class="bi bi-x-circle text-danger"></i>
                        @endif
                      </td>                      
                    </tr>
                    <?php $n++; ?>
                  @endforeach                  
                </tbody>
              </table>
            </div>
          </div>

        </div>
      </div>
    @endforeach
  </div>
</section>
@endsection