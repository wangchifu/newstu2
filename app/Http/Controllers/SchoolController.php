<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Rap2hpoutre\FastExcel\FastExcel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use App\Models\School;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Log;

class SchoolController extends Controller
{
    public function upload_students($semester_year=null){

        $get_student = Student::where('code',auth()->user()->school->code)->orderBy('created_at','DESC')->first();
        if(empty($semester_year) and !empty($get_student)){            
            $semester_year = $get_student->semester_year;
        }
        
        $students = Student::where('code',auth()->user()->school->code)
        ->where('semester_year',$semester_year)
        ->get();
        $teachers = Teacher::where('code',auth()->user()->school->code)
        ->where('semester_year',$semester_year)
        ->get();
        $student_data = [];
        foreach($students as $student){
            $student_data[$student->semester_year][$student->id]['no'] = $student->no;
            $student_data[$student->semester_year][$student->id]['class'] = $student->class;
            $student_data[$student->semester_year][$student->id]['num'] = $student->num;
            $student_data[$student->semester_year][$student->id]['sex'] = $student->sex;
            $student_data[$student->semester_year][$student->id]['name'] = $student->name;
            $student_data[$student->semester_year][$student->id]['id_number'] = $student->id_number;
            $student_data[$student->semester_year][$student->id]['old_school'] = $student->old_school;
            $student_data[$student->semester_year][$student->id]['type'] = $student->type;
            $student_data[$student->semester_year][$student->id]['another_no'] = $student->another_no;
            $student_data[$student->semester_year][$student->id]['ps'] = $student->ps;
        }
        $school = School::where('code',auth()->user()->school->code)->first(); 
        $data = [
            'semester_year'=>$semester_year,
            'student_data'=>$student_data,
            'teachers'=>$teachers,
            'ready'=>$school->ready,
            'class_num'=>$school->class_num,
        ];
        return view('schools.upload_students',$data);
    }

    public function import_excel(Request $request){        
        //處理檔案上傳
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $file_name_array = explode('_',$file->getClientOriginalName());

            if(chk_file_format($file->getClientOriginalName())=="NO"){
                return back()->withErrors(['errors' => ['錯誤：檔案名稱不符合要求！']]);
            }

            //先清空        
            Student::where('code',auth()->user()->school->code)->delete();
            //系統內已有的學生身分證            
            $all_students = Student::all();
            $all_student_array = [];
            $all_st_name=[];
            $all_st_code=[];
            foreach($all_students as $all_student){
                $all_student_array[$all_student->id] = $all_student->id_number;
                $all_st_name[$all_student->id_number] = $all_student->name;
                $all_st_code[$all_student->id_number] = $all_student->code;
            }

            $spreadsheet = IOFactory::load($file);

            // 選取活動工作表
            $worksheet = $spreadsheet->getActiveSheet();

            // 獲取行數和列數
            $highestRow = $worksheet->getHighestRow();
            $highestColumn = $worksheet->getHighestColumn();

            // 迭代行和列來讀取數據
            $xlsx_data = [];
            for ($row = 1; $row <= $highestRow; $row++) {
                $rowData = [];
                for ($col = 'A'; $col <= $highestColumn; $col++) {
                    $cellValue = $worksheet->getCell($col . $row)->getValue();
                    $rowData[] = $cellValue;
                }
                $xlsx_data[] = $rowData;
            }
        }else{
            return back()->withErrors(['errors' => ['錯誤：沒有夾帶檔案！']]);
        }
        $teacher_array = [];
        $class_num = 0;
        $student_num = 0;
        $students = 0;
        $one = [];
        $all = [];
        $error_id_number = null;
        foreach($xlsx_data as $k=>$row){
            foreach($row as $col){
                if($k==0){
                    $class_num = $row[3];//班級數
                    $student_num = $row[4];//學生數
                }
                if($k==1 and !empty($col)){
                    $teacher_array[] = $col;//老師的陣列
                }
            }
            if($k > 2){
                if(!empty($row[0])){
                    $students++;                
                    $special = ($row[7]==1)?1:null;
                    $subtract = ($special==1)?1:0;
                    if($row[7] != 0 and $row[7] != 1 and $row[7] !=2 and $row[7] !=3){
                        $type = 0;
                    }else{
                        $type = $row[7];
                    }
    
                    //檢查身分證
                    if(!chk_id_number($row[5])){
                        $error_id_number .= " {$row[0]} {$row[4]}, ";
                    }

                    if(in_array($row[5],$all_student_array)){
                        $check_school = School::where('code',$all_st_code[$row[5]])->first();                        
                        return back()->withErrors(['errors' => ['錯誤：學生 '.$row[4].' 的身分證 '.$row[5].' 與 '.$check_school->name.' '.$all_st_name[$row[5]]. '一樣，所以中止上傳！']]);
                    }
                    
    
                    $one = [
                        'code' => auth()->user()->school->code,
                        'semester_year' => $file_name_array[0],
                        'no' => $row[0],
                        'sex' => $row[3],
                        'name' => $row[4],
                        'id_number' => $row[5],
                        'old_school' => $row[6],
                        'type' => $type,
                        'special' => $special,
                        'subtract' => $subtract,
                        'another_no'=>$row[8],
                        'ps'=>$row[9],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                    array_push($all, $one);
                }                
            }
        }
        if($students != $student_num){
            return back()->withErrors(['errors' => ['錯誤：學生總數上('.$student_num.')下('.$students.')不合！？']]);
        }
        if(!empty($error_id_number)){
            return back()->withErrors(['errors' => ['錯誤：學生身分證有誤：'.$error_id_number]]);
        }

        if(count($teacher_array) != $class_num){
            return back()->withErrors(['errors' => ['錯誤：班級數('.$class_num.')與老師數('.count($teacher_array).')不合！？']]);
        }        
        Student::insert($all);

        //填上班級數(有些學校不會送老師)
        $att_class['class_num'] =$class_num;
        $school = School::where('code',auth()->user()->school->code)->first();
        $school->update($att_class);

        $one_teacher = [];
        $all_teacher = [];
        foreach($teacher_array as $k=>$v){
            $one_teacher = [
                'code' => auth()->user()->school->code,
                'semester_year' => $file_name_array[0],
                'name' => $v,
                'created_at' => now(),
                'updated_at' => now(),
            ];
            array_push($all_teacher, $one_teacher);
        }
        //先清空
        Teacher::where('code',auth()->user()->school->code)->delete();
        Teacher::insert($all_teacher);
        //記錄
        $event = "上傳了學生及導師名單。";
        logging($event,auth()->user()->school->code,get_ip());
        return redirect()->route('upload_students');
    }

    public function student_type($semester_year=null){
        $get_student = Student::where('code',auth()->user()->school->code)->orderBy('created_at','DESC')->first();
        if(empty($semester_year) and !empty($get_student)){            
            $semester_year = $get_student->semester_year;
        }
        
        $students = Student::where('code',auth()->user()->school->code)
        ->where('semester_year',$semester_year)
        ->get();
        $teachers = Teacher::where('code',auth()->user()->school->code)
        ->where('semester_year',$semester_year)
        ->get();
        $student_data = [];
        $type[0] = 0;
        $type[1] = 0;
        $type[2] = 0;
        $type[3] = 0;
        $type[4] = 0;
        $type[5] = 0;
        $subtract = 0;
        $spacial_student = [];
        $bao2_same = [];
        $bao2_not_same = [];
        $bao3_same = [];
        $bao3_not_same = [];
        $with_out_teacher = [];
        foreach($students as $student){
            $student_data[$student->semester_year][$student->id]['no'] = $student->no;
            $student_data[$student->semester_year][$student->id]['class'] = $student->class;
            $student_data[$student->semester_year][$student->id]['num'] = $student->num;
            $student_data[$student->semester_year][$student->id]['sex'] = $student->sex;
            $student_data[$student->semester_year][$student->id]['name'] = $student->name;
            $student_data[$student->semester_year][$student->id]['id_number'] = $student->id_number;
            $student_data[$student->semester_year][$student->id]['old_school'] = $student->old_school;
            $student_data[$student->semester_year][$student->id]['type'] = $student->type;
            if($student->type==2){
                $bao2_same[$student->id] = $student->name;
            }
            if($student->type==3){
                $bao2_not_same[$student->id] = $student->name;
            }
            if($student->type==4){
                $bao3_same[$student->id] = $student->name;
            }
            if($student->type==5){
                $bao3_not_same[$student->id] = $student->name;
            }
            $student_data[$student->semester_year][$student->id]['another_no'] = $student->another_no;
            $student_data[$student->semester_year][$student->id]['ps'] = $student->ps;
            $student_data[$student->semester_year][$student->id]['special'] = $student->special;
            if($student->special==1){
                $spacial_student[$student->id] = $student->name;                
            }            
            $student_data[$student->semester_year][$student->id]['subtract'] = $student->subtract;
            if(!empty($student->with_teacher)){
                $student_data[$student->semester_year][$student->id]['with_teacher'] = $student->w_teacher->name;
            }else{
                $student_data[$student->semester_year][$student->id]['with_teacher'] = "";
            }
            if(!empty($student->without_teacher)){
                $student_data[$student->semester_year][$student->id]['without_teacher'] = $student->wo_teacher->name;
                $with_out_teacher[$student->id]['student'] = $student->name; 
                $with_out_teacher[$student->id]['teacher'] = $student->wo_teacher->name; 
            }else{
                $student_data[$student->semester_year][$student->id]['without_teacher'] = "";
            }
            
           /**
            if($student->special==1){
                $special_student[$student->no] = $student->name;
            }            
            if($student->type==2){
                $bao2_student[$student->no] = $student->name;
            }
            if($student->type==3){
                $bao2_student[$student->no] = $student->name;
            }
            */
            if($student->special==null) $type[0]++;
            if($student->special==1) $type[1]++;
            if($student->type==2) $type[2]++;
            if($student->type==3) $type[3]++;
            if($student->type==4) $type[4]++;
            if($student->type==5) $type[5]++;
            $subtract +=$student->subtract;
        }

        $school = School::where('code',auth()->user()->school->code)->first();        
        $data = [
            'semester_year'=>$semester_year,
            'student_data'=>$student_data,
            'teachers'=>$teachers,
            'type'=>$type,
            'subtract'=>$subtract,
            'spacial_student'=>$spacial_student,
            'bao2_same'=>$bao2_same,
            'bao2_not_same'=>$bao2_not_same,
            'bao3_same'=>$bao3_same,
            'bao3_not_same'=>$bao3_not_same,
            'with_out_teacher'=>$with_out_teacher,
            'ready'=>$school->ready,
        ];
        return view('schools.student_type',$data);
    }

    public function edit_student(Student $student){

        //已經 ready 不能再修改
        $school = School::where('code',auth()->user()->school->code)->first();  
        if($school->ready==1){
            return back();
        }

        $teachers = Teacher::where('code',$student->code)
        ->where('semester_year',$student->semester_year)
        ->get();
        $data = [
            'student'=>$student,
            'teachers'=>$teachers,
        ];
        return view('schools.edit_student',$data);
    }

    public function update_student(Request $request,Student $student){
        $att = $request->all();
        //dd($att);
        if($att['special']==null){
            if( $att['subtract'] == 0 and $att['with_teacher'] == null){            
            }else{
                return back()->withErrors(['errors' => ['錯誤：一般生設定有問題！']]);
            }
            
        }elseif($att['special']==1){            
            //if($att['subtract'] > 0 and $att['with_teacher'] != null){  暫時不檢查綁老師
            if($att['subtract'] > 0){

            }else{
                return back()->withErrors(['errors' => ['錯誤：特殊生設定有問題！']]);
            }
        }

        if($att['type']==2 or $att['type']==3){
            if($att['bao1'] != null and $att['bao2'] == null){

            }else{
                return back()->withErrors(['errors' => ['錯誤：雙胞胎設定有問題！']]);
            }
        }

        if($att['type']==4 or $att['type']==5){
            if($att['bao1'] != null and $att['bao2'] != null){

            }else{
                return back()->withErrors(['errors' => ['錯誤：三胞胎設定有問題！']]);
            }
        }

        if($att['type']==2 or $att['type']==3){
            $att['another_no'] = $att['bao1'];
        }
        

        if($att['type']==4 or $att['type']==5){
            $att['another_no'] = $att['bao1'].",".$att['bao2'];
        }

        //沒有多胞胎 不要動 type
        if($att['type'] == "no"){
            unset($att['type']);
        }
        //特殊生而且不是多胞就改 type 為 1
        if($att['special']==1 and !isset($att['type'])){
            $att['type']=1;
        }
        //一般生而且不是多胞就改 type 為 
        if($att['special']==null and !isset($att['type'])){
            $att['another_no'] = null; 
            $att['type']=0;
        }
        
        $student->update($att);

        //記錄
        $event = "修改了學生 ".$student->name." 的個人資料設定。";
        logging($event,auth()->user()->school->code,get_ip());
        return redirect()->route('student_type');
    }

    function school_ready(Request $request){
        $semester_year = $request->input('semester_year');
        $special_students = $students = Student::where('code',auth()->user()->school->code)
        ->where('semester_year',$semester_year)
        ->where('special',1)
        ->get();
        foreach($special_students as $student){
            if(empty($student->with_teacher)){
                //return back()->withErrors(['errors' => ['錯誤：無法送出！有特殊生沒有指定導師！']]);  暫時不規定綁老師
            }
        }
        $att['ready'] = 1;
        $att['ready_user_id'] = auth()->user()->id;
        School::where('code',auth()->user()->school->code)->update($att);

        //記錄
        $event = "確定了學校送出名單，不再更改。";
        logging($event,auth()->user()->school->code,get_ip());
        return redirect()->route('student_type');

        return back();
    }

    function school_log(){
        $logs  = Log::where('for_code',auth()->user()->school->code)->orderBy('created_at','DESC')->paginate(20);
        $data = [
            'logs'=>$logs,
        ];
        return view('schools.school_log',$data);
    }

}
