<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\School;
use App\Models\Student;
use App\Models\Teacher;

class GroupAdminController extends Controller
{
    public function assign_group_admin(){
        $group = auth()->user()->school->group;

        $schools = School::where('group_id',auth()->user()->school->group_id)->get();
        $data = [
            'group'=>$group,
            'schools'=>$schools,
        ];
        return view('group_admins.assign_group_admin',$data);
    }

    public function do_assign(Request $request){
        $school = School::where('id',$request->input('school_id'))->first();
        $att['group_admin'] = 1;
        $school->update($att);
        
        $att2['group_admin'] = null;
        auth()->user()->school->update($att2);

        return redirect()->route('index');
    }

    public function start(){
        $group = auth()->user()->school->group;

        $schools = School::where('group_id',auth()->user()->school->group_id)->get();
        $data = [
            'group'=>$group,
            'schools'=>$schools,
        ];
        return view('group_admins.start',$data);
    }

    public function group_admin_unlock(School $school){
        if($school->group_id != auth()->user()->group_id){
            return back();    
        }        
        $att['ready'] = ($school->ready==1)?null:1;
        $school->update($att);
        return back();
    }

    public function show_student(School $school){
        if($school->group_id != auth()->user()->group_id){
            return back();    
        }
        $students = Student::where('code',$school->code)->get();
        $student_data['boy'] = 0;
        $student_data['girl'] = 0;
        $student_data['general'] = 0;
        $student_data['special'] = 0;
        $student_data['subtract'] = 0;
        $student_data['bao2'] = 0;
        $student_data['bao2_not'] = 0;
        $student_data['bao3'] = 0;
        $student_data['bao3_not'] = 0;
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
        
        $student = Student::where('code',$school->code)->first();
        $semester_year = $student->semester_year;
        $data = [
            'school'=>$school,
            'students'=>$students,
            'student_data'=>$student_data,
            'teachers'=>$teachers,
            'semester_year'=>$semester_year,
        ];
        return view('group_admins.show_student',$data);
    }

    function form_class(School $school){
        if($school->group_id != auth()->user()->group_id){
            return back();    
        }
        $teachers = Teacher::where('code',$school->code)->get();
        $data = [
            'school'=>$school,
            'teachers'=>$teachers,
        ];
        return view('group_admins.form_class',$data);
    }

    function go_form(Request $request,School $school){
        echo "123";
    }
}
