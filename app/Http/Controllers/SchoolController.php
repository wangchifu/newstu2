<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Rap2hpoutre\FastExcel\FastExcel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use App\Models\School;
use App\Models\Student;
use App\Models\TestStudent;
use App\Models\Teacher;
use App\Models\Log;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\DB;

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

        $semester_year2 = null;
        $teacher2s = [];
        $student_data2 = [];
        $school2 = [];                
        //如果是平和國小
        if(auth()->user()->school->code=="074603"){        
            $get_student = Student::where('code','074603-1')->orderBy('created_at','DESC')->first();
            if(empty($semester_year2) and !empty($get_student)){            
                $semester_year2 = $get_student->semester_year;
            }   

            $students = Student::where('code','074603-1')
            ->where('semester_year',$semester_year2)
            ->get();

            $teacher2s = Teacher::where('code','074603-1')
            ->where('semester_year',$semester_year2)
            ->get();            
            foreach($students as $student){
                $student_data2[$student->semester_year][$student->id]['no'] = $student->no;
                $student_data2[$student->semester_year][$student->id]['class'] = $student->class;
                $student_data2[$student->semester_year][$student->id]['num'] = $student->num;
                $student_data2[$student->semester_year][$student->id]['sex'] = $student->sex;
                $student_data2[$student->semester_year][$student->id]['name'] = $student->name;
                $student_data2[$student->semester_year][$student->id]['id_number'] = $student->id_number;
                $student_data2[$student->semester_year][$student->id]['old_school'] = $student->old_school;
                $student_data2[$student->semester_year][$student->id]['type'] = $student->type;
                $student_data2[$student->semester_year][$student->id]['another_no'] = $student->another_no;
                $student_data2[$student->semester_year][$student->id]['ps'] = $student->ps;
            }
            $school2 = School::where('code','074603-1')->first(); 
        }        
        
        $data = [
            'semester_year'=>$semester_year,
            'student_data'=>$student_data,
            'teachers'=>$teachers,
            'school'=>$school,                        
            'semester_year2'=>$semester_year2,
            'student_data2'=>$student_data2,
            'teacher2s'=>$teacher2s,
            'school2'=>$school2,                        
        ];
        return view('schools.upload_students',$data);
    }

    public function import_excel(Request $request){  
        $jh_school = $request->input('jh_school',0);      
        //處理檔案上傳
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $file_name_array = explode('_',$file->getClientOriginalName());

            if(chk_file_format($file->getClientOriginalName(),$jh_school)=="NO"){
                return back()->withErrors(['errors' => ['錯誤：檔案名稱不符合要求！']]);
            }            
            if($jh_school==1){
                $code = '074603-1'; //建和分校
            }else{
                $code = auth()->user()->school->code; //其他學校
            }
            //先清空        
            Student::where('code',$code)->delete();
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
        //檢查同一檔內有無相同身分證
        foreach($xlsx_data as $k=>$row){
            if($k > 2){
                if(!empty($row[5])){
                    $check_ID[] = $row[5];
                }
                
            }            
        }        
        $valuesCount = array_count_values($check_ID);
        foreach ($valuesCount as $value => $count) {
            if ($count > 1) {
                return back()->withErrors(['errors' => ['錯誤：身分證「'.$value.'」有出現重複，所以中止上傳！']]);
            }
        }

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
                        'code' => $code,
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
            return back()->withErrors(['errors' => ['錯誤：學生身分證(居留證)有誤：'.$error_id_number]]);
        }

        if(count($teacher_array) != $class_num){
            return back()->withErrors(['errors' => ['錯誤：班級數('.$class_num.')與老師數('.count($teacher_array).')不合！？']]);
        }        
        Student::insert($all);

        //填上班級數(有些學校不會送老師)
        $att_class['class_num'] =$class_num;
        $att2['situation'] = null;
        $school = School::where('code',$code)->first();
        $school->update($att_class);

        $one_teacher = [];
        $all_teacher = [];
        foreach($teacher_array as $k=>$v){
            $one_teacher = [
                'code' => $code,
                'semester_year' => $file_name_array[0],
                'name' => $v,
                'created_at' => now(),
                'updated_at' => now(),
            ];
            array_push($all_teacher, $one_teacher);
        }
        //先清空
        Teacher::where('code',$code)->delete();
        Teacher::insert($all_teacher);
        //記錄
        $event = "上傳了 ".$code." 學生及導師名單。";
        logging($event,auth()->user()->school->code,get_ip());
        return redirect()->back()->with('code', $code);
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
            
            if($student->special==null) $type[0]++;
            if($student->special==1) $type[1]++;
            if($student->type==2) $type[2]++;
            if($student->type==3) $type[3]++;
            if($student->type==4) $type[4]++;
            if($student->type==5) $type[5]++;
            $subtract +=$student->subtract;
        }

        $school = School::where('code',auth()->user()->school->code)->first();  
        
        //如果是平和國小
        if(auth()->user()->school->code=="074603"){ 
            $semester_year2 = null;
            $get_student = Student::where('code','074603-1')->orderBy('created_at','DESC')->first();
            if(empty($semester_year2) and !empty($get_student)){            
                $semester_year2 = $get_student->semester_year;
            }
            
            $students = Student::where('code','074603-1')
            ->where('semester_year',$semester_year)
            ->get();
            $teacher2s = Teacher::where('code','074603-1')
            ->where('semester_year',$semester_year)
            ->get();
            $student_data2 = [];
            $type2[0] = 0;
            $type2[1] = 0;
            $type2[2] = 0;
            $type2[3] = 0;
            $type2[4] = 0;
            $type2[5] = 0;
            $subtract2 = 0;
            $spacial_student2 = [];
            $bao2_same2 = [];
            $bao2_not_same2 = [];
            $bao3_same2 = [];
            $bao3_not_same2 = [];
            $with_out_teacher2 = [];
            foreach($students as $student){
                $student_data2[$student->semester_year][$student->id]['no'] = $student->no;
                $student_data2[$student->semester_year][$student->id]['class'] = $student->class;
                $student_data2[$student->semester_year][$student->id]['num'] = $student->num;
                $student_data2[$student->semester_year][$student->id]['sex'] = $student->sex;
                $student_data2[$student->semester_year][$student->id]['name'] = $student->name;
                $student_data2[$student->semester_year][$student->id]['id_number'] = $student->id_number;
                $student_data2[$student->semester_year][$student->id]['old_school'] = $student->old_school;
                $student_data2[$student->semester_year][$student->id]['type'] = $student->type;
                if($student->type==2){
                    $bao2_same2[$student->id] = $student->name;
                }
                if($student->type==3){
                    $bao2_not_same2[$student->id] = $student->name;
                }
                if($student->type==4){
                    $bao3_same2[$student->id] = $student->name;
                }
                if($student->type==5){
                    $bao3_not_same2[$student->id] = $student->name;
                }
                $student_data2[$student->semester_year][$student->id]['another_no'] = $student->another_no;
                $student_data2[$student->semester_year][$student->id]['ps'] = $student->ps;
                $student_data2[$student->semester_year][$student->id]['special'] = $student->special;
                if($student->special==1){
                    $spacial_student2[$student->id] = $student->name;                
                }            
                $student_data2[$student->semester_year][$student->id]['subtract'] = $student->subtract;
                if(!empty($student->with_teacher)){
                    $student_data2[$student->semester_year][$student->id]['with_teacher'] = $student->w_teacher->name;
                }else{
                    $student_data2[$student->semester_year][$student->id]['with_teacher'] = "";
                }
                if(!empty($student->without_teacher)){
                    $student_data2[$student->semester_year][$student->id]['without_teacher'] = $student->wo_teacher->name;
                    $with_out_teacher2[$student->id]['student'] = $student->name; 
                    $with_out_teacher2[$student->id]['teacher'] = $student->wo_teacher->name; 
                }else{
                    $student_data2[$student->semester_year][$student->id]['without_teacher'] = "";
                }
                
                if($student->special==null) $type2[0]++;
                if($student->special==1) $type2[1]++;
                if($student->type==2) $type2[2]++;
                if($student->type==3) $type2[3]++;
                if($student->type==4) $type2[4]++;
                if($student->type==5) $type2[5]++;
                $subtract2 +=$student->subtract;
            }

            $school2 = School::where('code','074603-1')->first();          
        
        }

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
            'school'=>$school,            
            'semester_year2'=>$semester_year2,
            'student_data2'=>$student_data2,
            'teacher2s'=>$teacher2s,
            'type2'=>$type2,
            'subtract2'=>$subtract2,
            'spacial_student2'=>$spacial_student2,
            'bao2_same2'=>$bao2_same2,
            'bao2_not_same2'=>$bao2_not_same2,
            'bao3_same2'=>$bao3_same2,
            'bao3_not_same2'=>$bao3_not_same2,
            'with_out_teacher2'=>$with_out_teacher2,
            'school2'=>$school2,
        ];
        return view('schools.student_type',$data);
    }

    public function school_show_class(School $school){
        if($school->code != auth()->user()->school->code){
            return back();    
        }
        $students = Student::where('code',$school->code)
            ->orderBy('class')->orderBy('num')->get();
                
        $student = Student::where('code',$school->code)->first();
        $semester_year = $student->semester_year;
        $eng_class = [0=>'A1',1=>'A2',2=>'A3',3=>'A4',4=>'A5',5=>'A6',6=>'A7',7=>'A8',8=>'A9',9=>'A10',10=>'A11',11=>'A12',12=>'A13',13=>'A14',14=>'A15',15=>'A16',16=>'A17',17=>'A18',18=>'A19',19=>'A20',20=>'A21',21=>'A22',22=>'A23',23=>'A24',24=>'A25',25=>'A26'];
        
        foreach($students as $student){            
            $student_data[$student->class]['st'][$student->num]['no'] = $student->no;
            $student_data[$student->class]['st'][$student->num]['special'] = $student->special;
            $student_data[$student->class]['st'][$student->num]['name'] = $student->name;
            $student_data[$student->class]['st'][$student->num]['sex'] = $student->sex;
            if(!empty($student->teacher)){
                $student_data[$student->class]['teacher'] = $student->teacher->name;
            }else{
                $student_data[$student->class]['teacher'] = null;
            }
            
            if(!isset($student_data[$student->class]['all'])) $student_data[$student->class]['all'] = 0;
            if(!isset($student_data[$student->class]['boy'])) $student_data[$student->class]['boy'] = 0;
            if(!isset($student_data[$student->class]['girl'])) $student_data[$student->class]['girl'] = 0;
            if(!isset($student_data[$student->class]['subtract'])) $student_data[$student->class]['subtract'] = 0;
            $student_data[$student->class]['all']++;
            if($student->sex == 1) $student_data[$student->class]['boy']++;
            if($student->sex == 2) $student_data[$student->class]['girl']++;
            $student_data[$student->class]['subtract'] = $student_data[$student->class]['subtract']+$student->subtract;            
        }
        //dd($student_data);
        //記錄                
        $event = "看了 ".$school->name." 班級學生編班資料。";                
        logging($event,auth()->user()->school->code,get_ip());

        $data = [
            'school'=>$school,
            'students'=>$students,
            'semester_year'=>$semester_year,
            'eng_class'=>$eng_class,
            'student_data'=>$student_data,
        ];
        return view('schools.school_show_class',$data);
    }
    
    public function school_export(School $school){
        if($school->code != auth()->user()->school->code){
            return back();    
        }        
        $students = Student::where('code',$school->code)
            ->orderBy('class')->orderBy('num')->get();
        $teachers = Teacher::where('code',$school->code)->get();
        $student = Student::where('code',$school->code)->orderBy('class')->orderBy('num')->first();
        $semester_year = $student->semester_year;
        $eng_class = [0=>'A1',1=>'A2',2=>'A3',3=>'A4',4=>'A5',5=>'A6',6=>'A7',7=>'A8',8=>'A9',9=>'A10',10=>'A11',11=>'A12',12=>'A13',13=>'A14',14=>'A15',15=>'A16',16=>'A17',17=>'A18',18=>'A19',19=>'A20',20=>'A21',21=>'A22',22=>'A23',23=>'A24',24=>'A25',25=>'A26'];
        
        foreach($students as $student){            
            $student_data[$student->class]['st'][$student->num]['no'] = $student->no;                        
            $student_data[$student->class]['st'][$student->num]['name'] = $student->name;
            $student_data[$student->class]['st'][$student->num]['sex'] = $student->sex;
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // 設置工作表內容
        $sheet->setCellValue('A1', '校名');
        $sheet->setCellValue('B1', '彰化縣'.$school->name);
        $sheet->setCellValue('C1', '班級數');
        $sheet->setCellValue('D1', $school->class_num);
        $sheet->setCellValue('E1', count($students));
        $cell_name = [1=>'A',2=>'B',3=>'C',4=>'D',5=>'E',6=>'F',7=>'G',8=>'H',9=>'I',10=>'J',11=>'K',12=>'L',13=>'M',14=>'N',15=>'O',16=>'P',17=>'Q',18=>'R',19=>'S',20=>'T',21=>'U',22=>'V',23=>'W',24=>'X',25=>'Y',26=>'Z'];
        $eng_class = [1=>'A1',2=>'A2',3=>'A3',4=>'A4',5=>'A5',6=>'A6',7=>'A7',8=>'A8',9=>'A9',10=>'A10',11=>'A11',12=>'A12',13=>'A13',14=>'A14',15=>'A15',16=>'A16',17=>'A17',18=>'A18',19=>'A19',20=>'A20',21=>'A21',22=>'A22',23=>'A23',24=>'A24',25=>'A25',26=>'A26'];
        $class_eng = array_flip($eng_class);
        $n=1;
        foreach($teachers as $teacher){
            $sheet->setCellValue($cell_name[$n].'2', $teacher->name);
            $n++;
        }
        $sheet->setCellValue('A3', '流水號');
        $sheet->setCellValue('B3', '班級');
        $sheet->setCellValue('C3', '座號');
        $sheet->setCellValue('D3', '性別');
        $sheet->setCellValue('E3', '姓名');
        $sheet->setCellValue('F3', '身分證字號');
        $sheet->setCellValue('G3', '原就讀學校');
        $sheet->setCellValue('H3', '編班類別');
        $sheet->setCellValue('I3', '相關流水號');
        $sheet->setCellValue('J3', '備註');        

        $n=4;
        foreach($students as $student){
            $sheet->setCellValue('A'.$n, $student->no);
            $sheet->setCellValue('B'.$n, $class_eng[$student->class]);
            $sheet->setCellValue('C'.$n, $student->num);
            $sheet->setCellValue('D'.$n, $student->sex);
            $sheet->setCellValue('E'.$n, $student->name);
            $sheet->setCellValue('F'.$n, $student->id_number);
            $sheet->setCellValue('G'.$n, $school->name);
            $sheet->setCellValue('H'.$n, $student->type);
            $sheet->setCellValue('I'.$n, $student->another_no);
            $sheet->setCellValue('J'.$n, "");
            $n++;
        }


        // 設置標題
        $sheet->setTitle('學生編班資料');


        $filename = $semester_year."_".$school->code."_".date('Ymd')."_OK";
        // 設定 HTTP 標頭，強制瀏覽器下載文件
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$filename.'.xlsx"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1'); // IE支援

        // 建立 Xlsx 寫入器並輸出
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output'); // 將文件輸出到瀏覽器

        //記錄                
        $event = "下載了 ".$school->name." 班級學生編班資料檔案。";                
        logging($event,auth()->user()->school->code,get_ip());

        exit;
    }

    public function create_student($code=null){
        if(empty($code)){
            $code = auth()->user()->school->code;
        }
        //已經 ready 不能再增加
        $school = School::where('code',$code)->first();  
        if($school->ready==1){
            return back();
        }

        $semester_year = date('Y') - 1911;;
        $teachers = Teacher::where('code',$code)        
        ->where('semester_year',$semester_year)
        ->get();
        $data = [            
            'teachers'=>$teachers,
            'code'=>$code,
        ];
        return view('schools.create_student',$data);
    }  

    public function store_student(Request $request){
        $att = $request->all();        
        $att['semester_year'] = date('Y') - 1911;
        if($att['name']==null or $att['no']==null or $att['id_number']==null){
            return back()->withErrors(['errors' => ['錯誤：流水號或姓名或身分證未填！']]);
        }
        //dd($att);
        $check_student = Student::where('code',$att['code'])
        ->where('semester_year',$att['semester_year'])
        ->where('no',$att['no'])
        ->first();
        if(!empty($check_student)){
            return back()->withErrors(['errors' => ['錯誤：流水號 '.$att['no'].' 已經存在！']]);
        }

        $check_student = Student::where('id_number',$att['id_number'])
        ->first();
        if(!empty($check_student)){
            return back()->withErrors(['errors' => ['錯誤：身分證 '.$att['id_number'].' 已經存在！']]);
        }

        if($att['special']==null){
            if( $att['subtract'] == 0 and $att['with_teacher'] == null){            
            }else{
                return back()->withErrors(['errors' => ['錯誤：一般生設定有問題！']]);
            }
            
        }elseif($att['special']==1){            
            //if($att['subtract'] > 0 and $att['with_teacher'] != null){  暫時不檢查綁老師
            if($att['subtract'] > 0){

            }else{
                //也有可能特殊生沒有減人數
                //return back()->withErrors(['errors' => ['錯誤：特殊生設定有問題！']]);
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

        $att['name'] = $request->input('name');
        if(empty($att['name'])){
            return back()->withErrors(['errors' => ['錯誤：姓名不得為空！']]);
        }        
        
        $student = Student::create($att);

        //記錄
        $event = "新增了學生 ".$student->name." 的個人資料設定。";
        logging($event,$att['code'],get_ip());
        return redirect()->route('student_type')->with('code', $att['code']);
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
                //也有可能特殊生沒有減人數
                //return back()->withErrors(['errors' => ['錯誤：特殊生設定有問題！']]);
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

        $att['name'] = $request->input('name');
        if(empty($att['name'])){
            return back()->withErrors(['errors' => ['錯誤：姓名不得為空！']]);
        }        
        
        $student->update($att);

        //記錄
        $event = "修改了學生 ".$student->name." 的個人資料設定。";
        logging($event,$student->code,get_ip());
        return back();
    }

    public function delete_student(Student $student){
        //已經 ready 不能再刪除
        $school = School::where('code',$student->code)->first();  
        if($school->ready==1){
            return back();
        }
        $student->delete();
        //記錄
        $event = "刪除了學生 ".$student->name." 的個人資料設定。";
        logging($event,$student->code,get_ip());
        return back()->with('code', $student->code);
    }

    public function school_ready(Request $request){        
        $code = $request->input('code');
        $semester_year = $request->input('semester_year');
        $special_students = $students = Student::where('code',$code)
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
        School::where('code',$code)->update($att);

        //記錄
        $event = "確定了學校送出名單，不再更改。";
        logging($event,$code,get_ip());        

        return back()->with('code', $code);
    }

    public function school_log(){
        $logs  = Log::where('for_code',auth()->user()->school->code)->orderBy('created_at','DESC')->paginate(20);
        $data = [
            'logs'=>$logs,
        ];
        return view('schools.school_log',$data);
    }

    public function test_start(){
        $school = School::find(auth()->user()->school_id);
        $data = [            
            'school'=>$school,            
        ];
        return view('schools.test_start',$data); 
    }

    public function test_copy(){        
        $students = Student::where('code',auth()->user()->school->code)->get();
        $all = [];
        foreach($students as $student){
            $one = [
                'code' => auth()->user()->school->code,
                'semester_year' => $student->semester_year,
                'no' => $student->no,
                'sex' => $student->sex,
                'name' => $student->name,
                'id_number' => $student->id_number,
                'old_school' => $student->old_school,
                'type' => $student->type,
                'special' => $student->special,
                'subtract' => $student->subtract,
                'another_no'=>$student->another_no,
                'ps'=>$student->ps,
                'with_teacher'=>$student->with_teacher,
                'without_teacher'=>$student->without_teacher,
                'created_at' => now(),
                'updated_at' => now(),
            ];
            array_push($all, $one);
        }        
        TestStudent::where('code',auth()->user()->school->code)->delete();
        TestStudent::insert($all);
        
        return back();

    }

    public function test_show_student(School $school){
        if($school->code != auth()->user()->school->code){
            return back();    
        }
        $students = TestStudent::where('code',$school->code)->get();
        $student_data['boy'] = 0;//男生人數
        $student_data['girl'] = 0;//女生人數
        $student_data['general'] = 0;//一般生人數
        $student_data['special'] = 0;//特殊人人數
        $student_data['subtract'] = 0;//減的人數
        $student_data['bao2'] = 0;//雙胞同班人數
        $student_data['bao2_not'] = 0;//雙胞不同班人數
        $student_data['bao3'] = 0;//三胞同班人數
        $student_data['bao3_not'] = 0;//三胞不同班人數
        foreach($students as $student){
            if($student->sex==1){                
                $student_data['boy']++;
            }else{                
                $student_data['girl']++;
            }
            if($student->special){                
                $student_data['special']++;
                $student_data['subtract'] += $student->subtract;
            }else{                
                $student_data['general']++;
            }
            if($student->type==2){                
                $student_data['bao2']++;
            }
            if($student->type==3){                
                $student_data['bao2_not']++;
            }
            if($student->type==4){                
                $student_data['bao3']++;
            }
            if($student->type==5){                
                $student_data['bao3_not']++;
            }
        }        
        
        $teachers = Teacher::where('code',$school->code)->get();
        
        $student = TestStudent::where('code',$school->code)->first();
        $semester_year = $student->semester_year;
        $data = [
            'school'=>$school,
            'students'=>$students,
            'student_data'=>$student_data,
            'teachers'=>$teachers,
            'semester_year'=>$semester_year,
        ];
        return view('schools.test_show_student',$data);
    }

    public function test_form_class(School $school){
        if($school->code != auth()->user()->school->code){
            return back();    
        }
        $teachers = Teacher::where('code',$school->code)->get();
        $data = [
            'school'=>$school,
            'teachers'=>$teachers,
        ];
        return view('schools.test_form_class',$data);
    }

    public function test_go_form(Request $request,School $school){
        if(empty($request->input('random_seed'))){
            return back()->withErrors(['errors' => ['錯誤：亂數種子不可以空著！']]);
        }
        if((int)$request->input('random_seed') < 1000){
            return back()->withErrors(['errors' => ['錯誤：亂數種子要四位數字！']]);
        };
        $students = TestStudent::where('code',$school->code)->get();
        if(count($students) < 29){
            return back()->withErrors(['errors' => ['錯誤：學生數須大於28人才需編班！']]);
        }

         //有問題時，重新編一次
        $form_error = 1;
        form_again:
        
        if($form_error > 100) dd('設定一定有問題，導致編了100次也沒編出來！很可能是不能同班的，被一直編同班！找出來原因才能解決了！');

        srand(rand(1000, 9999));  // 設定亂數種子
        $school = School::where('code',$school->code)->first();
        $class_num = $school->class_num;//班級數
        $special = [];//特殊生
        $boy = [];//男生
        $girl = [];//女生
        $check_sex = [];//查性別
        $subtract = [];//減人數
        $bao2 = [];//雙胞同班
        $bao2_not = [];//雙胞不同班
        $bao2_not2 = [];
        $bao3 = [];//三胞同班
        $bao3_not = [];//三胞不同班
        $bao3_not2 = [];
        $with_teacher = [];//指定導師
        $new_class = [];
        foreach($students as $student){
            if($student->special==1){
                $special[$student->no] = $student->name;
                $subtract[$student->no] = $student->subtract;
            }
            if(!empty($student->with_teacher)){
                $with_teacher[$student->with_teacher][$student->no] = $student->name;
            }
            if($student->sex==1){
                $boy[$student->no] = $student->name;
                $check_sex[$student->no] = 1;
            }
            if($student->sex==2){
                $girl[$student->no] = $student->name;
                $check_sex[$student->no] = 2;
            }
            if($student->type==2){
                $bao2[$student->no] = $student->name;
                $bao2_2[$student->no] = $student->another_no;
            }
            if($student->type==3){
                $bao2_not[$student->no] = $student->name;
                $bao2_not2[$student->no] = $student->another_no;
            }
            if($student->type==4){
                $bao3[$student->no] = $student->name;
                $bao3_2[$student->no] = $student->another_no;
            }
            if($student->type==5){
                $bao3_not[$student->no] = $student->name;
                $bao3_not2[$student->no] = $student->another_no;
            }
        }

        //算出男女比例
        $boys_num = count($boy);
        $girls_num = count($girl);
        $total_num = count($students);
        $boy_average = round($boys_num/$total_num,2);
        $girl_average = round($girls_num/$total_num,2);        

        //列出班級
        for($i=0;$i<$class_num;$i++){                        
            $new_class[$i]['boy'] = [];
            $new_class[$i]['girl'] = [];         
            $new_class[$i]['count'] = 0;
        }

        //同個指定老師的特殊生一起分發到各班(也許特殊生多於班級數)
        $i=0;
        foreach($with_teacher as $k=>$v){
            foreach($v as $k1=>$v1){
                if(isset($boy[$k1])){
                    $new_class[$i]['boy'][$k1] = $v1;
                    unset($boy[$k1]);                    
                    $new_class[$i]['count']++;
                    $new_class[$i]['count'] += $subtract[$k1];

                    //如果特殊生有多胞胎
                    //雙胞同班
                    if(isset($bao2[$k1])){
                        if(isset($boy[$bao2_2[$k1]])){
                            $new_class[$i]['boy'][$bao2_2[$k1]] = $boy[$bao2_2[$k1]];
                            unset($boy[$bao2_2[$k1]]);                                                
                            $new_class[$i]['count']++;
                        }
                        if(isset($girl[$bao2_2[$k1]])){
                            $new_class[$i]['girl'][$bao2_2[$k1]] = $girl[$bao2_2[$k1]];
                            unset($girl[$bao2_2[$k1]]);                    
                            $new_class[$i]['count']++;
                        }
                        unset($bao2[$k1]);
                    }
                    //雙胞不同班
                    if(isset($bao2_not[$k1])){        
                        $i++;
                        if($i==$class_num) $i=0;     
                        if(isset($boy[$bao2_not2[$k1]])){
                            $new_class[$i]['boy'][$bao2_not2[$k1]] = $boy[$bao2_not2[$k1]];
                            unset($boy[$bao2_not2[$k1]]);                                                
                            $new_class[$i]['count']++;
                        }
                        if(isset($girl[$bao2_not2[$k1]])){
                            $new_class[$i]['girl'][$bao2_not2[$k1]] = $girl[$bao2_not2[$k1]];
                            unset($girl[$bao2_not2[$k1]]);                    
                            $new_class[$i]['count']++;
                        }
                        unset($bao2_not[$k1]);
                        
                        if($i==0){
                            $i = $class_num-1;
                        }else{
                            $i--;
                        }
                    }

                    //三胞同班
                    if(isset($bao3[$k1])){
                        $bs = explode(',',$bao3_2[$k1]);//另兩個
                        foreach($bs as $k2=>$v2){
                            if(isset($boy[$v2])){
                                $new_class[$i]['boy'][$v2] = $boy[$v2];//另一個
                                unset($boy[$v2]);
                                $new_class[$i]['count']++;               
                            }
                            if(isset($girl[$v2])){
                                $new_class[$i]['girl'][$v2] = $girl[$v2];//另一個
                                unset($girl[$v2]);
                                $new_class[$i]['count']++;                    
                            }                                
                            unset($bao3[$v2]);
                        }                         
                        unset($bao3[$k1]);
                    }

                    //抽三胞胎不同班
                    if(isset($bao3_not[$k1])){                        
                        $bs = explode(',',$bao3_not2[$k1]);//另兩個
                        foreach($bs as $k2=>$v2){
                            $i++;
                            if($i==$class_num) $i=0;

                            if(isset($boy[$v2])){
                                $new_class[$i]['boy'][$v2] = $boy[$v2];//另一個
                                unset($boy[$v2]);
                                $new_class[$i]['count']++;               
                            }
                            if(isset($girl[$v2])){
                                $new_class[$i]['girl'][$v2] = $girl[$v2];//另一個
                                unset($girl[$v2]);
                                $new_class[$i]['count']++;                    
                            }                                
                            unset($bao3_not[$v2]);
                        }                         
                        unset($bao3_not[$k1]);   
                        
                        if($i==0){
                            $i = $class_num-1;
                        }else{
                            $i--;
                        }
                        if($i==0){
                            $i = $class_num-1;
                        }else{
                            $i--;
                        }
                    }

                }
                if(isset($girl[$k1])){
                    $new_class[$i]['girl'][$k1] = $v1;
                    unset($girl[$k1]);
                    $new_class[$i]['count']++;
                    $new_class[$i]['count'] += $subtract[$k1];

                    //如果特殊生有多胞胎
                    //雙胞同班
                    if(isset($bao2[$k1])){
                        if(isset($boy[$bao2_2[$k1]])){
                            $new_class[$i]['boy'][$bao2_2[$k1]] = $boy[$bao2_2[$k1]];
                            unset($boy[$bao2_2[$k1]]);                                                
                            $new_class[$i]['count']++;
                        }
                        if(isset($girl[$bao2_2[$k1]])){
                            $new_class[$i]['girl'][$bao2_2[$k1]] = $girl[$bao2_2[$k1]];
                            unset($girl[$bao2_2[$k1]]);                    
                            $new_class[$i]['count']++;
                        }
                        unset($bao2[$k1]);
                    }
                    //雙胞不同班
                    if(isset($bao2_not[$k1])){        
                        $i++;
                        if($i==$class_num) $i=0;     
                        if(isset($boy[$bao2_not2[$k1]])){
                            $new_class[$i]['boy'][$bao2_not2[$k1]] = $boy[$bao2_not2[$k1]];
                            unset($boy[$bao2_not2[$k1]]);                                                
                            $new_class[$i]['count']++;
                        }
                        if(isset($girl[$bao2_not2[$k1]])){
                            $new_class[$i]['girl'][$bao2_not2[$k1]] = $girl[$bao2_not2[$k1]];
                            unset($girl[$bao2_not2[$k1]]);                    
                            $new_class[$i]['count']++;
                        }
                        unset($bao2_not[$k1]);

                        if($i==0){
                            $i = $class_num-1;
                        }else{
                            $i--;
                        }
                    }

                    //三胞同班
                    if(isset($bao3[$k1])){
                        $bs = explode(',',$bao3_2[$k1]);//另兩個
                        foreach($bs as $k2=>$v2){
                            if(isset($boy[$v2])){
                                $new_class[$i]['boy'][$v2] = $boy[$v2];//另一個
                                unset($boy[$v2]);
                                $new_class[$i]['count']++;               
                            }
                            if(isset($girl[$v2])){
                                $new_class[$i]['girl'][$v2] = $girl[$v2];//另一個
                                unset($girl[$v2]);
                                $new_class[$i]['count']++;                    
                            }                                
                            unset($bao3[$v2]);
                        }                         
                        unset($bao3[$k1]);
                    }

                    //抽三胞胎不同班
                    if(isset($bao3_not[$k1])){                        
                        $bs = explode(',',$bao3_not2[$k1]);//另兩個
                        foreach($bs as $k2=>$v2){
                            $i++;
                            if($i==$class_num) $i=0;

                            if(isset($boy[$v2])){
                                $new_class[$i]['boy'][$v2] = $boy[$v2];//另一個
                                unset($boy[$v2]);
                                $new_class[$i]['count']++;               
                            }
                            if(isset($girl[$v2])){
                                $new_class[$i]['girl'][$v2] = $girl[$v2];//另一個
                                unset($girl[$v2]);
                                $new_class[$i]['count']++;                    
                            }                                
                            unset($bao3_not[$v2]);
                        }                         
                        unset($bao3_not[$k1]);   
                        
                        if($i==0){
                            $i = $class_num-1;
                        }else{
                            $i--;
                        }
                        if($i==0){
                            $i = $class_num-1;
                        }else{
                            $i--;
                        }
                    }
                }
                //刪掉特殊生群組內的該生                
                unset($special[$k1]);                
            }
            $i++;
            if($i==$class_num) $i=0;
        }    

        //沒有指定老師，就特殊生自己分發
        //$i=0;       //班級接下去        
        foreach($special as $k=>$v){
            if(isset($boy[$k])){
                $new_class[$i]['boy'][$k] = $v;
                unset($boy[$k]);
                $new_class[$i]['count']++;
                $new_class[$i]['count'] += $subtract[$k];
            }
            if(isset($girl[$k])){
                $new_class[$i]['girl'][$k] = $v;
                unset($girl[$k]);
                $new_class[$i]['count']++;
                $new_class[$i]['count'] += $subtract[$k];
            }
            $i++;
            if($i==$class_num) $i=0;
        }
        
                
        //打亂特殊生的班
        shuffle($new_class);
        
        //取最多人數的班
        //$big_class = 0;
        //foreach($new_class as $k=>$v){
        //    if($v['count'] > $big_class){
        //        $big_class= $v['count'];
        //    }
        //}

        //先補男生讓各班人數一致 會不小必取到雙胞或三胞胎
        //foreach($new_class as $k=>$v){
        //    for($i=$v['count'];$i<$big_class;$i++){
        //        // 隨機選取一個鍵
        //        $randomKey = array_rand($boy);
        //        // 使用鍵來獲取對應的值
        //        $randomValue = $boy[$randomKey];
        //        $new_class[$k]['boy'][$randomKey] = $randomValue;
        //        $new_class[$k]['count']++;
        //        unset($boy[$randomKey]);
        //    }
        //}
        //再打亂一次
        //shuffle($new_class);
        
        //抽雙胞胎同班
        if(!empty($bao2)){
            $i=0;
            foreach($bao2 as $k=>$v){
                if(isset($boy[$k])){
                    $new_class[$i]['boy'][$k] = $v;
                    unset($boy[$k]);
                    $new_class[$i]['count']++;
                    if(isset($boy[$bao2_2[$k]])){
                        $new_class[$i]['boy'][$bao2_2[$k]] = $boy[$bao2_2[$k]];//另一個
                        unset($boy[$bao2_2[$k]]);
                        $new_class[$i]['count']++;               
                    }
                    if(isset($girl[$bao2_2[$k]])){
                        $new_class[$i]['girl'][$bao2_2[$k]] = $girl[$bao2_2[$k]];//另一個
                        unset($girl[$bao2_2[$k]]);
                        $new_class[$i]['count']++;                    
                    }   
                    $i++;           
                    if($i==$class_num) $i=0;              
                }                                

                if(isset($girl[$k])){
                    $new_class[$i]['girl'][$k] = $v;
                    unset($girl[$k]);
                    $new_class[$i]['count']++;      
                    if(isset($boy[$bao2_2[$k]])){
                        $new_class[$i]['boy'][$bao2_2[$k]] = $boy[$bao2_2[$k]];//另一個
                        unset($boy[$bao2_2[$k]]);
                        $new_class[$i]['count']++;               
                    }
                    if(isset($girl[$bao2_2[$k]])){
                        $new_class[$i]['girl'][$bao2_2[$k]] = $girl[$bao2_2[$k]];//另一個
                        unset($girl[$bao2_2[$k]]);
                        $new_class[$i]['count']++;                    
                    }              
                    $i++;     
                    if($i==$class_num) $i=0;   
                }

                unset($bao2[$k]);
                unset($bao2[$bao2_2[$k]]);
                        
            }
        }        
        
        //抽雙胞胎不同班
        if(!empty($bao2_not)){
            //$i=0;  接下去編，不要同班一堆雙胞胎
            foreach($bao2_not as $k=>$v){
                if(isset($boy[$k])){
                    $new_class[$i]['boy'][$k] = $v;
                    unset($boy[$k]);
                    $new_class[$i]['count']++;                               
                    unset($bao2_not[$k]);
                    $i++;
                    if($i==$class_num) $i=0;
                }
                if(isset($girl[$k])){
                    $new_class[$i]['girl'][$k] = $v;
                    unset($girl[$k]);
                    $new_class[$i]['count']++;                               
                    unset($bao2_not[$k]);
                    $i++;
                    if($i==$class_num) $i=0;
                }                 
            }
        }

        
        //抽三胞胎同班
        if(!empty($bao3)){
            //$i=0;
            foreach($bao3 as $k=>$v){
                if(isset($boy[$k])){
                    $new_class[$i]['boy'][$k] = $v;
                    unset($boy[$k]);
                    $new_class[$i]['count']++;
                    $bs = explode(',',$bao3_2[$k]);//另兩個
                    foreach($bs as $k1=>$v1){
                        if(isset($boy[$v1])){
                            $new_class[$i]['boy'][$v1] = $boy[$v1];//另一個
                            unset($boy[$v1]);
                            $new_class[$i]['count']++;               
                        }
                        if(isset($girl[$v1])){
                            $new_class[$i]['girl'][$v1] = $girl[$v1];//另一個
                            unset($girl[$v1]);
                            $new_class[$i]['count']++;                    
                        }                                
                    }                                               
                }                                

                if(isset($girl[$k])){
                    $new_class[$i]['girl'][$k] = $v;
                    unset($girl[$k]);
                    $new_class[$i]['count']++;      
                    $bs = explode(',',$bao3_2[$k]);//另兩個
                    foreach($bs as $k1=>$v1){
                        if(isset($boy[$v1])){
                            $new_class[$i]['boy'][$v1] = $boy[$v1];//另一個
                            unset($boy[$v1]);
                            $new_class[$i]['count']++;               
                        }
                        if(isset($girl[$v1])){
                            $new_class[$i]['girl'][$v1] = $girl[$v1];//另一個
                            unset($girl[$v1]);
                            $new_class[$i]['count']++;                    
                        }                                 
                    }
                }    
                $i++;           
                if($i==$class_num) $i=0;                     
            }
        }          

        //抽三胞胎不同班
        if(!empty($bao3_not)){
            //$i=0;  接下去編，不要同班一堆雙胞胎
            foreach($bao3_not as $k=>$v){
                if(isset($boy[$k])){
                    $new_class[$i]['boy'][$k] = $v;
                    unset($boy[$k]);
                    $new_class[$i]['count']++;                               
                    unset($bao3_not[$k]);
                    $i++;
                    if($i==$class_num) $i=0;
                }
                if(isset($girl[$k])){
                    $new_class[$i]['girl'][$k] = $v;
                    unset($girl[$k]);
                    $new_class[$i]['count']++;                               
                    unset($bao3_not[$k]);
                    $i++;
                    if($i==$class_num) $i=0;
                }                 
            }
        }

        //再打亂一次
        shuffle($new_class);

        //取最多人數的班
        $big_class = 0;
        foreach($new_class as $k=>$v){
            if($v['count'] > $big_class){
                $big_class= $v['count'];
            }
        }

        //依比例補男女讓各班人數盡量一致
        foreach($new_class as $k=>$v){
            for($i=$v['count'];$i<$big_class;$i++){
                $class_boy_num = count($v['boy']);
                $class_num = count($v['boy'])+count($v['girl']);
                if($class_num==0){
                    $class_boy_average = 0.5; 
                }else{
                    $class_boy_average = round($class_boy_num/$class_num,2); 
                }                
                if($class_boy_average > $boy_average){
                    // 隨機選取一個鍵
                    $randomKey = array_rand($girl);
                    // 使用鍵來獲取對應的值
                    $randomValue = $girl[$randomKey];
                    $new_class[$k]['girl'][$randomKey] = $randomValue;
                    $new_class[$k]['count']++;
                    unset($girl[$randomKey]);
                }else{
                    // 隨機選取一個鍵
                    $randomKey = array_rand($boy);
                    // 使用鍵來獲取對應的值
                    $randomValue = $boy[$randomKey];
                    $new_class[$k]['boy'][$randomKey] = $randomValue;
                    $new_class[$k]['count']++;
                    unset($boy[$randomKey]);
                }                
            }
        }
        
        //再打亂一次
        shuffle($new_class);

        for($t=0;$t<10000;$t++){
            foreach($new_class as $k=>$v){
                $class_boy_num = count($v['boy']);
                $class_num = count($v['boy'])+count($v['girl']);
                if($class_num==0){
                    $class_boy_average = 0.5;
                }else{
                    $class_boy_average = round($class_boy_num/$class_num,2);
                }                
                if($class_boy_average > $boy_average){
                    // 隨機選取一個鍵
                    //先排女生
                    if(!empty($girl)){
                        $randomKey = array_rand($girl);
                        // 使用鍵來獲取對應的值
                        $randomValue = $girl[$randomKey];
                        $new_class[$k]['girl'][$randomKey] = $randomValue;
                        $new_class[$k]['count']++;
                        unset($girl[$randomKey]);
                    }else{
                        //女生沒了 只好排男生
                        if(!empty($boy)){
                            // 隨機選取一個鍵
                            $randomKey = array_rand($boy);
                            // 使用鍵來獲取對應的值
                            $randomValue = $boy[$randomKey];
                            $new_class[$k]['boy'][$randomKey] = $randomValue;
                            $new_class[$k]['count']++;
                            unset($boy[$randomKey]);
                        }
                    }            
                }else{
                    //先排男生
                    if(!empty($boy)){
                        // 隨機選取一個鍵
                        $randomKey = array_rand($boy);
                        // 使用鍵來獲取對應的值
                        $randomValue = $boy[$randomKey];
                        $new_class[$k]['boy'][$randomKey] = $randomValue;
                        $new_class[$k]['count']++;
                        unset($boy[$randomKey]);
                    }else{
                        //男生沒了，只好排女生
                        if(!empty($girl)){
                            $randomKey = array_rand($girl);
                            // 使用鍵來獲取對應的值
                            $randomValue = $girl[$randomKey];
                            $new_class[$k]['girl'][$randomKey] = $randomValue;
                            $new_class[$k]['count']++;
                            unset($girl[$randomKey]);
                        }
                    }
                    
                }   
            }
            //都沒了 就停止迴圈
            if(empty($boy) and empty($girl)) break;
        }        

        //檢查一下 是否把不可同老師的 分到同一班             
        foreach($students as $student){
            $check_with[$student->no] = $student->with_teacher;
            $check_without[$student->no] = $student->without_teacher;
        }        
        foreach($new_class as $k=>$v){
            foreach($v['boy'] as $k1=>$v1){
                foreach($v['boy'] as $k2=>$v2){
                    if($check_with[$k1]==$check_without[$k2] and !empty($check_with[$k1]) and !empty($check_without[$k2])){
                        $form_error++;
                        goto form_again;
                    }
                }
                foreach($v['girl'] as $k2=>$v2){
                    if($check_with[$k1]==$check_without[$k2] and !empty($check_with[$k1]) and !empty($check_without[$k2])){
                        $form_error++;
                        goto form_again;
                    }
                }
            }
            foreach($v['girl'] as $k1=>$v1){
                foreach($v['boy'] as $k2=>$v2){
                    if($check_with[$k1]==$check_without[$k2] and !empty($check_with[$k1]) and !empty($check_without[$k2])){
                        $form_error++;
                        goto form_again;
                    }
                }
                foreach($v['girl'] as $k2=>$v2){
                    if($check_with[$k1]==$check_without[$k2] and !empty($check_with[$k1]) and !empty($check_without[$k2])){
                        $form_error++;
                        goto form_again;
                    }
                }
            }
        }

        //dd('通過！');
        


        //再打亂一次
        //shuffle($new_class);

        //同班內 男女生亂排一次
        foreach($new_class as $k=>$v){
            $boy_array = $v['boy'];
            $boy_keys = array_keys($v['boy']);
            shuffle($boy_keys);
            $new_class[$k]['boy'] = [];
            foreach ($boy_keys as $key) {
                $new_class[$k]['boy'][$key] = $boy_array[$key];
            }

            $girl_array = $v['girl'];
            $girl_keys = array_keys($v['girl']);
            shuffle($girl_keys);
            $new_class[$k]['girl'] = [];
            foreach ($girl_keys as $key) {
                $new_class[$k]['girl'][$key] = $girl_array[$key];
            }
            
        }

        //dd($new_class);
        //寫入資料庫
        $eng_class = [0=>'A1',1=>'A2',2=>'A3',3=>'A4',4=>'A5',5=>'A6',6=>'A7',7=>'A8',8=>'A9',9=>'A10',10=>'A11',11=>'A12',12=>'A13',13=>'A14',14=>'A15',15=>'A16',16=>'A17',17=>'A18',18=>'A19',19=>'A20',20=>'A21',21=>'A22',22=>'A23',23=>'A24',24=>'A25',25=>'A26'];
        
        
        //批量寫法
        $ids = "";
        $string1 = "";
        $string2 = "";
        foreach($students as $student){                
            foreach($new_class as $k=>$v){
                $n=1;        
                foreach($v['boy'] as $k1=>$v1){
                    if($student->no==$k1){
                        $att['class'] = $eng_class[$k];
                        $att['num'] = $n;
                    }
                    $n++;
                }
                foreach($v['girl'] as $k1=>$v1){
                    if($student->no==$k1){
                        $att['class'] = $eng_class[$k];
                        $att['num'] = $n;
                    }
                    $n++;
                }                  
            }
            $ids = $ids.$student->id.",";
            $string1 = $string1." when id=".$student->id." then '".$att['class']."'";
            $string2 = $string2." when id=".$student->id." then '".$att['num']."'";
        }
        $ids = substr($ids,0,-1);
        //dd($ids);
        DB::statement("UPDATE test_students SET 
        class = CASE 
        ".$string1." 
        END,
        num = CASE 
        ".$string2."
        END
        WHERE id IN (".$ids.")");        
        /**迴圈寫法 效果差
        foreach($students as $student){                
            foreach($new_class as $k=>$v){
                $n=1;        
                foreach($v['boy'] as $k1=>$v1){
                    if($student->no==$k1){
                        $att['class'] = $eng_class[$k];
                        $att['num'] = $n;
                    }
                    $n++;
                }
                foreach($v['girl'] as $k1=>$v1){
                    if($student->no==$k1){
                        $att['class'] = $eng_class[$k];
                        $att['num'] = $n;
                    }
                    $n++;
                }                  
            }
            $student->update($att);
        }
        */

        //$att2['situation'] = 1;測試不用記錄
        //$school->update($att2);測試不用記錄
        
        //$event = "是管理者，他為 ".$school->name." 編了學生的班級。";                
        //測試不用記錄
        //logging($event,$school->code,get_ip());     

        return redirect()->route('test_start');
        
    }


    public function test_show_class(School $school){
        if($school->code != auth()->user()->school->code){
            return back();    
        }
        $students = TestStudent::where('code',$school->code)
            ->orderBy('class')->orderBy('num')->get();
                
        $student = TestStudent::where('code',$school->code)->first();
        $semester_year = $student->semester_year;
        $eng_class = [0=>'A1',1=>'A2',2=>'A3',3=>'A4',4=>'A5',5=>'A6',6=>'A7',7=>'A8',8=>'A9',9=>'A10',10=>'A11',11=>'A12',12=>'A13',13=>'A14',14=>'A15',15=>'A16',16=>'A17',17=>'A18',18=>'A19',19=>'A20',20=>'A21',21=>'A22',22=>'A23',23=>'A24',24=>'A25',25=>'A26'];
        
        foreach($students as $student){            
            $student_data[$student->class]['st'][$student->num]['no'] = $student->no;
            $student_data[$student->class]['st'][$student->num]['special'] = $student->special;
            $student_data[$student->class]['st'][$student->num]['name'] = $student->name;
            $student_data[$student->class]['st'][$student->num]['sex'] = $student->sex;
            if(!empty($student->teacher)){
                $student_data[$student->class]['teacher'] = $student->teacher->name;
            }else{
                $student_data[$student->class]['teacher'] = null;
            }
            
            if(!isset($student_data[$student->class]['all'])) $student_data[$student->class]['all'] = 0;
            if(!isset($student_data[$student->class]['boy'])) $student_data[$student->class]['boy'] = 0;
            if(!isset($student_data[$student->class]['girl'])) $student_data[$student->class]['girl'] = 0;
            if(!isset($student_data[$student->class]['subtract'])) $student_data[$student->class]['subtract'] = 0;
            $student_data[$student->class]['all']++;
            if($student->sex == 1) $student_data[$student->class]['boy']++;
            if($student->sex == 2) $student_data[$student->class]['girl']++;
            $student_data[$student->class]['subtract'] = $student_data[$student->class]['subtract']+$student->subtract;            
        }
        //dd($student_data);
        $data = [
            'school'=>$school,
            'students'=>$students,
            'semester_year'=>$semester_year,
            'eng_class'=>$eng_class,
            'student_data'=>$student_data,
        ];
        return view('schools.test_show_class',$data);
    }

    public function test_form_teacher(School $school){
        if($school->code != auth()->user()->school->code){
            return back();    
        }
        $teachers = Teacher::where('code',$school->code)->get();
        $eng_class = [0=>'A1',1=>'A2',2=>'A3',3=>'A4',4=>'A5',5=>'A6',6=>'A7',7=>'A8',8=>'A9',9=>'A10',10=>'A11',11=>'A12',12=>'A13',13=>'A14',14=>'A15',15=>'A16',16=>'A17',17=>'A18',18=>'A19',19=>'A20',20=>'A21',21=>'A22',22=>'A23',23=>'A24',24=>'A25',25=>'A26'];
        
        $with_students = [];
        $with_teachers = [];        
        for($n=0;$n<$school->class_num;$n++){
            $check_student =  TestStudent::where('code',$school->code)->where('class',$eng_class[$n])->where('with_teacher','<>',null)->first();
            if(!empty($check_student->id)){
                $with_students[$eng_class[$n]] = $check_student->name;
                $with_teachers[$eng_class[$n]] = $check_student->w_teacher->name;
            }            
        }
        $without_teachers = [];        
        for($n=0;$n<$school->class_num;$n++){
            $check_students =  TestStudent::where('code',$school->code)->where('class',$eng_class[$n])->where('without_teacher','<>',null)->get();            
            if(count($check_students) > 0){                
                foreach($check_students as $student){
                    if(!isset($without_teachers[$eng_class[$n]])) $without_teachers[$eng_class[$n]] = "";
                    $without_teachers[$eng_class[$n]] .= $student->wo_teacher->name.",";
                }                
            }            
        }
        
        
        $data = [
            'school'=>$school,
            'teachers'=>$teachers,
            'eng_class'=>$eng_class,
            'with_students'=>$with_students,
            'with_teachers'=>$with_teachers,
            'without_teachers'=>$without_teachers,
        ];
        return view('schools.test_form_teacher',$data);
    }

    public function test_go_form_teacher(Request $request,School $school){        
        if(empty($request->input('random_seed'))){
            return back()->withErrors(['errors' => ['錯誤：亂數種子不可以空著！']]);
        }
        if((int)$request->input('random_seed') < 1000){
            return back()->withErrors(['errors' => ['錯誤：亂數種子要四位數字！']]);
        };
        $students = TestStudent::where('code',$school->code)->get();
        if(count($students) < 29){
            return back()->withErrors(['errors' => ['錯誤：學生數須大於28人才能編班！']]);
        }
        srand(rand(1000, 9999));  // 設定亂數種子

        $teachers = Teacher::where('code',$school->code)->get();
        $teacher_array = [];
        foreach($teachers as $teacher){
            $teacher_array[$teacher->id] = $teacher->name;
        }        
        $att = $request->all();
        $assign_teacher = $att['teacher'];     
                
        
        //先把指定的老師分下去
        foreach($assign_teacher as $k=>$v){
            if($v <> 0){
                $class_teacher[$k] = $v;
                unset($teacher_array[$v]);
                unset($assign_teacher[$k]);
            }            
        }                
        //如果還有沒指定的
        if(!empty($teacher_array)){
            //再分指定不可以的老師
            $without_teacher = [];      
            if(!isset($att['without_teacher'])) $att['without_teacher'] = [];   
            
            foreach($att['without_teacher'] as $k=>$v){
                //如果指定不可以的老師的那班  已經先分了老師
                if(!empty($class_teacher[$k])){
                }else{
                    $v = substr($v,0,-1);                         
                    $without_teacher = explode(",",$v);                            
                    $new_teacher_array = array_diff($teacher_array,$without_teacher);                        
                    //如果為空 返回不編
                    if(empty($new_teacher_array)){
                        return back()->withErrors(['errors' => ['錯誤！某師不可為某班導師']]);
                    }
                    $teacher_id = array_rand($new_teacher_array);            
                    $class_teacher[$k] = (string)$teacher_id;                
                    unset($teacher_array[$teacher_id]);                        
                    unset($assign_teacher[$k]);
                }                
            }                  
        
            //最後分亂數
            foreach($assign_teacher as $k=>$v){
                if($v == 0){
                    $teacher_id = array_rand($teacher_array);
                    $class_teacher[$k] = (string)$teacher_id;
                    unset($teacher_array[$teacher_id]);
                    unset($assign_teacher[$k]);
                }            
            }        
        }       

        $students = TestStudent::where('code',$school->code)->get();
        $ids = "";
        $string1 = "";
        foreach($students as $student){
            foreach($class_teacher as $k=>$v){
                if($student->class == $k){
                    $att2['teacher_id'] = $v;
                    $ids = $ids.$student->id.",";
                    $string1 = $string1." when id=".$student->id." then '".$att2['teacher_id']."'";                    
                    //$student->update($att2);
                }
            }
            
        }

        $ids = substr($ids,0,-1);
        //dd($ids);
        DB::statement("UPDATE test_students SET 
        teacher_id = CASE 
        ".$string1." 
        END
        WHERE id IN (".$ids.")");  
        
        //$event = "是管理者，他為 ".$school->name." 編了班級的導師。";                
        //測試不記錄
        //logging($event,$school->code,get_ip());  

        return redirect()->route('test_start');

    }

    public function test_show_teacher(School $school){
        if($school->code != auth()->user()->school->code){
            return back();    
        }
        $class_teachers = TestStudent::where('code',$school->code)->orderBy('class')->get()->groupBy('class');        

//        dd($class_teachers);
        $student = TestStudent::where('code',$school->code)->first();
        $semester_year = $student->semester_year;
        $data = [
            'semester_year'=>$semester_year,
            'school'=>$school,
            'class_teachers'=>$class_teachers,
        ];
        return view('schools.test_show_teacher',$data);
    }

    public function test_form_order(School $school){
        if($school->code != auth()->user()->school->code){
            return back();    
        }
        $teachers = Teacher::where('code',$school->code)->get();
        $eng_class = [0=>'A1',1=>'A2',2=>'A3',3=>'A4',4=>'A5',5=>'A6',6=>'A7',7=>'A8',8=>'A9',9=>'A10',10=>'A11',11=>'A12',12=>'A13',13=>'A14',14=>'A15',15=>'A16',16=>'A17',17=>'A18',18=>'A19',19=>'A20',20=>'A21',21=>'A22',22=>'A23',23=>'A24',24=>'A25',25=>'A26'];
                
        $data = [
            'school'=>$school,
            'teachers'=>$teachers,
            'eng_class'=>$eng_class,
        ];
        return view('schools.test_form_order',$data);
    }

    public function test_go_form_order(Request $request,School $school){
        $eng_class = [0=>'A1',1=>'A2',2=>'A3',3=>'A4',4=>'A5',5=>'A6',6=>'A7',7=>'A8',8=>'A9',9=>'A10',10=>'A11',11=>'A12',12=>'A13',13=>'A14',14=>'A15',15=>'A16',16=>'A17',17=>'A18',18=>'A19',19=>'A20',20=>'A21',21=>'A22',22=>'A23',23=>'A24',24=>'A25',25=>'A26'];
        for($i=0;$i<$school->class_num;$i++){
            $students[$eng_class[$i]] = TestStudent::where('code',$school->code)->where('class',$eng_class[$i])->get();
        }
        $att['teacher'] = $request->input('teacher');
        
        $ids = "";
        $string1 = "";
        foreach($att['teacher'] as $k=>$v){
            if($k <> $v){
                $att2['class'] = $v;
                foreach($students[$k] as $student){
                    $ids = $ids.$student->id.",";
                    $string1 = $string1." when id=".$student->id." then '".$att2['class']."'";                    
                    //$student->update($att2);
                }                
            }            
        }
        $ids = substr($ids,0,-1);
        //dd($ids);
        DB::statement("UPDATE test_students SET 
        class = CASE 
        ".$string1." 
        END
        WHERE id IN (".$ids.")");  

        //$event = "是管理者，他為 ".$school->name." 重新排了班級順序。";                
        //測試不記錄
        //logging($event,$school->code,get_ip());  

        return redirect()->route('test_start');
    }

    public function test_delete123(School $school){
        if($school->code != auth()->user()->school->code){
            return back();    
        }
        TestStudent::where('code',$school->code)->delete();                
        
        return redirect()->route('test_start');
    }

    public function test_delete23(School $school){
        if($school->code != auth()->user()->school->code){
            return back();    
        }
        $att['class'] = null;
        $att['num'] = null;
        $att['teacher_id'] = null;
        TestStudent::where('code',$school->code)->update($att);                
        return redirect()->route('test_start');
    }

    public function test_delete3(School $school){
        $att['teacher_id'] = null;        
        TestStudent::where('code',$school->code)->update($att);        
        
        return redirect()->route('test_start');
    }

    public function test_print(School $school){
        if($school->group_id != auth()->user()->group_id){
            return back();    
        }
        $students = TestStudent::where('code',$school->code)
            ->orderBy('class')->orderBy('num')->get();
                
        $student = TestStudent::where('code',$school->code)->orderBy('class')->orderBy('num')->first();
        $semester_year = $student->semester_year;
        $eng_class = [0=>'A1',1=>'A2',2=>'A3',3=>'A4',4=>'A5',5=>'A6',6=>'A7',7=>'A8',8=>'A9',9=>'A10',10=>'A11',11=>'A12',12=>'A13',13=>'A14',14=>'A15',15=>'A16',16=>'A17',17=>'A18',18=>'A19',19=>'A20',20=>'A21',21=>'A22',22=>'A23',23=>'A24',24=>'A25',25=>'A26'];
        
        foreach($students as $student){            
            $student_data[$student->class]['st'][$student->num]['no'] = $student->no;            
            //$student_data[$student->class]['st'][$student->num]['special'] = $student->special;
            $student_data[$student->class]['st'][$student->num]['name'] = $student->name;
            $student_data[$student->class]['st'][$student->num]['sex'] = $student->sex;
            $student_data[$student->class]['st'][$student->num]['id_number'] = substr($student->id_number,-3);
            
            if(!empty($student->teacher)){
                $student_data[$student->class]['teacher'] = $student->teacher->name;
            }else{
                $student_data[$student->class]['teacher'] = null;
            }                
            /**
            if(!isset($student_data[$student->class]['all'])) $student_data[$student->class]['all'] = 0;
            if(!isset($student_data[$student->class]['boy'])) $student_data[$student->class]['boy'] = 0;
            if(!isset($student_data[$student->class]['girl'])) $student_data[$student->class]['girl'] = 0;
            if(!isset($student_data[$student->class]['subtract'])) $student_data[$student->class]['subtract'] = 0;
            $student_data[$student->class]['all']++;
            if($student->sex == 1) $student_data[$student->class]['boy']++;
            if($student->sex == 2) $student_data[$student->class]['girl']++;
            $student_data[$student->class]['subtract'] = $student_data[$student->class]['subtract']+$student->subtract;            
            */
        }
        //dd($student_data);
        //$event = "是管理者，他列印了 ".$school->name." 班級學生編班資料。";                
        //測試不記錄
        //logging($event,$school->code,get_ip());

        $data = [
            'school'=>$school,
            'students'=>$students,
            'semester_year'=>$semester_year,
            'eng_class'=>$eng_class,
            'student_data'=>$student_data,
        ];
        return view('schools.test_print',$data);
    }

    public function test_print2(School $school){
        if($school->group_id != auth()->user()->group_id){
            return back();    
        }
        $students = TestStudent::where('code',$school->code)
            ->orderBy('class')->orderBy('num')->get();
                
        $student = TestStudent::where('code',$school->code)->orderBy('class')->orderBy('num')->first();
        $semester_year = $student->semester_year;
        $eng_class = [0=>'A1',1=>'A2',2=>'A3',3=>'A4',4=>'A5',5=>'A6',6=>'A7',7=>'A8',8=>'A9',9=>'A10',10=>'A11',11=>'A12',12=>'A13',13=>'A14',14=>'A15',15=>'A16',16=>'A17',17=>'A18',18=>'A19',19=>'A20',20=>'A21',21=>'A22',22=>'A23',23=>'A24',24=>'A25',25=>'A26'];
        
        foreach($students as $student){            
            $student_data[$student->class]['st'][$student->num]['no'] = $student->no;            
            //$student_data[$student->class]['st'][$student->num]['special'] = $student->special;
            $student_data[$student->class]['st'][$student->num]['name'] = substr_cut_name($student->name);
            $student_data[$student->class]['st'][$student->num]['sex'] = $student->sex;
            $student_data[$student->class]['st'][$student->num]['id_number'] = substr($student->id_number,-3);
            
            if(!empty($student->teacher)){
                $student_data[$student->class]['teacher'] = $student->teacher->name;
            }else{
                $student_data[$student->class]['teacher'] = null;
            }                
            /**
            if(!isset($student_data[$student->class]['all'])) $student_data[$student->class]['all'] = 0;
            if(!isset($student_data[$student->class]['boy'])) $student_data[$student->class]['boy'] = 0;
            if(!isset($student_data[$student->class]['girl'])) $student_data[$student->class]['girl'] = 0;
            if(!isset($student_data[$student->class]['subtract'])) $student_data[$student->class]['subtract'] = 0;
            $student_data[$student->class]['all']++;
            if($student->sex == 1) $student_data[$student->class]['boy']++;
            if($student->sex == 2) $student_data[$student->class]['girl']++;
            $student_data[$student->class]['subtract'] = $student_data[$student->class]['subtract']+$student->subtract;            
            */
        }
        //dd($student_data);
        //$event = "是管理者，他列印了 ".$school->name." 班級學生編班資料。";                
        //測試不記錄
        //logging($event,$school->code,get_ip());

        $data = [
            'school'=>$school,
            'students'=>$students,
            'semester_year'=>$semester_year,
            'eng_class'=>$eng_class,
            'student_data'=>$student_data,
        ];
        return view('schools.test_print',$data);
    }

}
