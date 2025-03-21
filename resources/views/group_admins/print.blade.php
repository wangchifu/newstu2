@extends('layouts.master_clean')

@section('page_title')
{{ $semester_year }}學年{{ $school->name }}一年級編班結果
@endsection

@section('content')
<body onload="window.print()">
<style>
  .styled-table {
    width:100%;
    border: 2px solid black; /* 設定外框為 2px 寬的實線 */
    border-collapse: collapse; /* 合併表格內外邊框 */    
  }

  .styled-table tr td {
    border: 2px solid black; /* 設定每個單元格的邊框 */
    padding: 2px; /* 單元格內距 */
    text-align: center;
  }
  .page-break {
            page-break-after: always; /* 在該元素之後強制換頁 */
  }
  .underline {
            border-bottom: 1px solid black; /* 調整這裡的值來改變底線的粗細 */
            display: inline; /* 確保底線僅適用於文本 */
            padding-bottom: 1px; /* 可選：增加底部間距 */
        }
</style>
<table style="width:100%">
  <tr>
    <td>
      <h6>校名：彰化縣{{ $school->name }}</h6>
    </td>
    <td>
      <h6>班級數：{{ $school->class_num }} 班</h6>
    </td>
    <td>
      <h6>總人數：{{ count($students) }} 人</h6>
    </td>
  </tr>
</table>
@for($i=0;$i<$school->class_num;$i++)
<table class="styled-table">
  <tr>
    <td colspan="7">
      <h4 style="margin-bottom: -10px;">彰化縣{{ $school->name }} 一年{{ $eng_class[$i] }}班 新生編班名冊</h4>
      <p style="text-align:right;margin:2px;">導師：<span class="underline">　{{ $student_data[$eng_class[$i]]['teacher'] }}　</span></p>
    </td>
  </tr>
  <tr>
    <td>
      流水號
    </td>
    <td>
      班級
    </td>
    <td>
      臨時座號
    </td>
    <td>
      性別
    </td>
    <td>
      姓名
    </td>
    <td>
      身分證末三碼
    </td>
    <td>
      原就讀學校
    </td>
    <td>
      備註
    </td>
  </tr>
  @foreach($student_data[$eng_class[$i]]['st'] as $k=>$v)
    <tr>
      <td>{{ $v['no'] }}</td>
      <td>{{ $eng_class[$i] }}</td>
      <td>{{ $k }}</td>
      <td>
        @if($v['sex']==1)
          男
        @endif
        @if($v['sex']==2)
          女
        @endif
      </td>
      <td>{{ $v['name'] }}</td>
      <td>{{ $v['id_number'] }}</td>
      <td></td>
      <td></td>
    </tr>
  @endforeach
</table>
第{{ $i+1 }}頁 製表日期：{{ date('Y-m-d') }}
<table style="width:100%">
  <tr>
    <td>承辦人：</td><td>承辦學校：</td><td>教育處：</td>
  </tr>
</table> 	 	
<div class="page-break"></div>
@endfor
@endsection