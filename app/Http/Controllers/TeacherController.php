<?php

namespace App\Http\Controllers;
use Illuminate\Support\LazyCollection;
use Illuminate\Http\Request;
use App\Models\Teacher;
use App\Models\Section;
use App\Models\GradeLevel;
use Illuminate\Support\Str;
use App\Http\Requests\TeacherRequest;
class TeacherController extends Controller
{
 
    
//Function For Adding A New Teacher
public function addTeacher(TeacherRequest $request){
 $valids=$request->validated();
if($valids){
    try{
        \DB::beginTransaction();
        if($request['section_id']!=null){  
            $result=Str::of($request['section_id'])->split('/[\s,]+/')[3];
            $sectionTable=Section::where('name','=',$result)->first(); 
             $valid=Teacher::create([
                    'name' =>$request['name'],
                    'email' =>$request['email'],
                    'contact' =>$request['contact'],  
                    'section_id'=>$sectionTable->id
                  ]);                                       
             //Updating the "teacher_id" in the section table
             $sectionTable->update(['teacher_id' =>$valid->id]); 
             \DB::commit();
             return ['message'=>'Successfully Added!'];      
          }   
       else{
          $teacher=Teacher::create($valids);
          \DB::commit();
          return ['message'=>'Successfully Added!'];              
        }    
    }
    catch(\Exception $e){
        \DB::rollback();
        return response()->json(['error'=> $e->getMessage()],500);
    } 
}


}


//Function For Getting All Teachers
   public function allTeachers()
   {
    try{
       $array=[];
       $List=Teacher::cursor();
      foreach($List as $teacher){
          if($teacher->section_id==null){
             array_push($array,$teacher);
          }
          else{
            $sectionTable=Section::where('id','=',$teacher->section_id)->with("gradelevel")->get();
            $teacher->section_id="Gr. ".$sectionTable->get(0)->gradelevel->grade_level." --- ".$sectionTable->get(0)->name;
            array_push($array,$teacher); 
          }     
        }
          return response()->json($array); 
       }
       catch(\Exception $e){
        return response()->json(['error'=> $e->getMessage()],500);
       } 
   }


 //Function For Deleting Or Removing A Teacher
 public function removeTeacher($id){
     try{
        \DB::beginTransaction();
         $teacher=Teacher::findOrFail($id);
         if($teacher->section_id==null){
            $del=Teacher::findOrFail($id)->delete();
            \DB::commit();
            return ['message'=>'Teacher is successfully deleted!'];
         }
         else{
            $updateSection=Section::findOrFail($teacher->section_id);
            $updateSection->update(['teacher_id' =>null]); 
            $del=Teacher::findOrFail($id)->delete();
            \DB::commit();
            return ['message'=>'Teacher is successfully deleted!'];
          }       
       }
        catch(\Exception $e){
        \DB::rollback();
        return response()->json(['error' => $e->getMessage()],500);
       }
      
   }

//Function For Updating The Data Of Teachers
   public function updateTeacher(TeacherRequest $request,$id){
    $update=$request->validated();
    if($update){
        try{
        \DB::beginTransaction();
         if($request['section_id']==null){
               $valid=Teacher::findOrFail($id);
               $valid->update([
                   'name' => $request['name'],
                   'email' =>$request['email'],
                   'contact'=> $request['contact']
                ]);  
              \DB::commit();
               return ['message'=>'Successfully Added!'];
            }
   
         else{
             $result=Str::of($request['section_id'])->split('/[\s,]+/')[3];
             $section=Section::where('name','=',$result)->first();
                 $updated=Teacher::findOrFail($id);
                 $updated->update([
                   'name' => $request['name'],
                   'email' =>$request['email'],
                   'contact'=> $request['contact'],
                   'section_id'=>$section->id
                  ]);  
                 $section->update(['teacher_id' =>$id]);   
                 \DB::commit();
                 return ['message'=>'Successfully Added!'];           
            }
           
          }
          catch(\Exception $e){
          \DB::rollback();
           return response()->json(['error' => $e->getMessage()],500);
       }
    }
    

   }


//Function For Showing By Id Of A Teacher
   public function showByIdTeacher($id){
    try{
        $teacher=Teacher::where('id','=',$id)->first();
        if($teacher->section_id==null){
           return response()->json($teacher); 
        }
        else{
         $sectionTable=Section::where('id','=',$teacher->section_id)->with("gradelevel")->get();
         $teacher->section_id="Gr. ".$sectionTable->get(0)->gradelevel->grade_level." --- ".$sectionTable->get(0)->name;
          return response()->json($teacher); 
        }
      
       }
       catch(\Exception $e){
        return response()->json(['error' => $e->getMessage()],500);
      }
   }  



//Function For Getting All OF All Avalable Section
public function availableSection(){
    try{
    $sectionTable=Section::where('teacher_id','=',null)->cursor();
    $arraySection=[];
    foreach($sectionTable as $sec){
      error_log($sec->gradelevel->grade_level);
      $sec->name="Gr. ".$sec->gradelevel->grade_level." --- ".$sec->name;   
      array_push($arraySection,$sec->makeHidden(['id','teacher_id','gradelevel','total_students','capacity','gradelevel_id','students_id','created_at','updated_at']));
    }
    return response()->json($arraySection); 
   } 
    catch(\Exception $e){
         return response()->json(['error'=> $e->getMessage()],500);
   } 
}


}
