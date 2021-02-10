<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Teacher;
use App\Models\Section;
class TeacherController extends Controller
{
  
   //Adding a new Teachers
   public function addTeacher(Request $request){

 $valid=$request->validate([
        'name' => ['required','string','max:50',function($attribute,$value,$fail){
          if($value!='jj'){
               $fail('The '.$attribute.' must be'." ".'jj');
            }
        }],
        'email' => ['required','string','email','max:50'],
        'contact' => ['required','string','max:11','digits:11'],  
        'student_id'=>[],
        'section_id'=>[] 
  ]); 

if($request['section_id']!=null){  
    $sectionTable=Section::where('name','=',$request->section_id)->first(); 
  if($sectionTable->teacher_id==null){
        $request['section_id']=$sectionTable->id; 
        $addTeacher=$request->validate([
            'name' => ['required','string','max:50'],
            'email' => ['required','string','email','max:50'],
            'contact' => ['required','string','max:11','digits:11'],  
            'student_id'=>[],
            'section_id'=>[] 
            ]); 
      try{   
        //Adding a teacher
        $teacher=Teacher::create($addTeacher);
        //Updating the teacher_id in the section table
        $teacherIdTable=Teacher::where('section_id','=',$sectionTable->id)->first();
        $sectionTable->teacher_id=$teacherIdTable->id;
        $sectionTable->save();
        //Getting all of the teacher
        $arrayteacher=Teacher::all();
         return ['message'=>'Successfully Added!','arrayTeacher'=>$arrayteacher];
         }
         catch(\Exception $e){
            return response()->json(['error' => $e->getMessage()],401);  
         }  
    }

}
else{
    try{   
        //Adding a teacher
       $teacher=Teacher::create($valid);
        //Getting all of the teacher
       $arrayteacher=Teacher::all();
         return ['message'=>'Successfully Added!','arrayTeacher'=>$arrayteacher];
     }
     catch(\Exception $e){
         return response()->json(['error' => $e->getMessage()],401);  
     }  
}


    

   }


   //Getting All Teachers
   public function allTeachers()
   {
       try{
       $array=[];
       $List=Teacher::all();
      foreach($List as $teacher){
          if($teacher->section_id==null){
             array_push($array,$teacher);
          }
          else{
            $sectionTable=Section::where('id','=',$teacher->section_id)->first();
            $teacher->section_id=$sectionTable->name;
            array_push($array,$teacher); 
          }
         
        }

         return response()->json($array); 
       }
       catch(\Exception $e){
        return response()->json(['error'=> $e->getMessage()],401);
       }  
   }

   //Deleting or Removing A Teachr
   public function removeTeacher($id) {
       try{
         $del=Teacher::findOrFail($id)->delete();
         $teacher=Teacher::all();
         return ['message'=>'Teacher is successfully deleted!','arrayTeacher'=>$teacher];
       }
        catch(\Exception $e){
        return response()->json(['error' => $e->getMessage()],401);
       }
      
   }

//Updating the data of Teachers
   public function updateTeacher(Request $request,$id){

           $valid=$request->validate([
               'name' => ['required', 'string','max:50'],
               'email' => ['required', 'string','email','max:50'],
               'contact' => ['required', 'string','max:11','digits:11'],    
           ]);

     try{
           $valid=Teacher::findOrFail($id);
           $valid->update([
               'name' => $request['name'],
               'email' =>$request['email'],
               'contact'=> $request['contact']
            ]);  
           $valid->save();  
           $teacher=Teacher::all();       
           return ['message'=>'Successfully Added!','arrayTeacher'=>$teacher];
       }
       catch(\Exception $e){
        return response()->json(['error' => $e->getMessage()], 401);
    }

   }

   //Showing by id of a teacher
   public function showByIdTeacher($id){
       try{
        $teacher=Teacher::findOrFail($id);
        return response()->json($teacher); 
       }
       catch(\Exception $e){
        return response()->json(['error' => $e->getMessage()], 401);
      }
   }  


}
