@extends('layouts.master')

@section('page_title')
<h1>指定下次「編班中心」學校</h1>
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
              動作
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
                @if($school->group_admin)
                  <i class="bi bi-star-fill text-warning"></i>
                @endif
                {{ $school->name }}                
              </td>
              <td>
                @if(empty($school->group_admin))
                  <form action="{{ route('do_assign') }}" method="post" id="do_assign">
                    @csrf
                    <input type="hidden" name="school_id" value="{{ $school->id }}">
                    <a href="#" class="btn btn-warning" onclick="sw_confirm2('指定後，自己將喪失管理權！','do_assign')"><i class="bi bi-arrow-return-right"></i> 指定</a>
                  </form>
                @endif
              </td>
            </tr>
            <?php $n++; ?>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</section>
@endsection