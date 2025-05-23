@extends('layouts.master')

@section('page_title')
<h1 class="text-danger">{{ $semester_year }}學年{{ $school->name }}「測試」導師編排結果</h1>
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
                <a href="{{ route('test_start') }}" class="btn btn-secondary"><i class="bi bi-chevron-double-left"></i> 返回</a> 全部導師一覽 <span class="small text-danger">◎請檢查是否流水號重複，如有缺號乃屬正常</span>
              </th>
            </tr>
          </thead>
        </table>
      <table class="table table-striped w-25 mx-auto">         
          <tbody>
            @foreach($class_teachers as $class => $students)
              <tr>
                <td>
                  {{ $class}}班
                </td>
                <td>
                <?php $student = $students->first(); ?>                  
                    {{ $student->teacher->name }}              
                </td>
              </tr>              
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</section>
@endsection