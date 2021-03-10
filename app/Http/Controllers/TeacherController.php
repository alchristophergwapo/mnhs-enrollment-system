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
        if($request->section_id!=null){
            //$result=Str::of($secName)->split('/[\s,]+/')[3];
            $sectionTable=Section::where('id','=',$request->section_id)->first(); 
            if($sectionTable->teacher_id==null){
                $valid=Teacher::create([
                    'name' =>$request['name'],
                    'email' =>$request['email'],
                    'contact' =>$request['contact'],  
                    'section_id'=>$sectionTable->id
                  ]);                                       
             //Updating the "teacher_id" in the section table
             $sectionTable->update(['teacher_id' =>$valid->id]); 
             \DB::commit();
             return response()->json(['message'=>'Successfully Added!'],200);  
            }
            else{
              $teacherTable=Section::where('id','=',$sectionTable->id)->with("adviser")->get();
              return response()->json(['teacher'=>$teacherTable->get(0)->adviser->name,'section'=>$teacherTable->get(0)->name],200);  
            }
          }   
       else{
          $teacher=Teacher::create($valids);
          \DB::commit();
          return response()->json(['message'=>'Successfully Added!'],200);             
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
            $teacher->student_id=$teacher->section_id;
             array_push($array,$teacher);
          }
          else{
            $sectionTable=Section::where('id','=',$teacher->section_id)->with("gradelevel")->get();
            $teacher->student_id=$teacher->section_id;
            $teacher->section_id="Gr. ".$sectionTable->get(0)->gradelevel->grade_level." --- ".$sectionTable->get(0)->name;
            array_push($array,$teacher); 
          }     
        }
          return response()->json($array,200); 
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
            return response()->json(['message'=>'Teacher is successfully deleted!'],200); 
         }
         else{
            $updateSection=Section::where("id","=",$teacher->section_id)->update(['teacher_id' =>null]); 
            $del=Teacher::findOrFail($id)->delete();
            \DB::commit();
            return response()->json(['message'=>'Teacher is successfully deleted!'],200); 
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
         if($request->section_id==null){
               $valid=Teacher::where("id","=",$id)->update(['name' => $request['name'],'email' =>$request['email'],'contact'=> $request['contact']]);
              \DB::commit();
              return response()->json(['message'=>'Successfully Updated!'],200); 
            }
         else{
             //$result=Str::of($secName)->split('/[\s,]+/')[3];
            if($id!='update'){
                $sections=Section::where('id','=',$request->section_id)->first();
                if($sections->teacher_id==null){
                   $updateCurrentSec=Section::where('teacher_id','=',$id)->update(['teacher_id'=>null]); 
                   $updated=Teacher::where("id","=",$id)->update(['name' => $request['name'],'email' =>$request['email'],'contact'=> $request['contact'],'section_id'=>$sections->id]);
                   $sections->update(['teacher_id' =>$id]);  
                   \DB::commit();
                   return response()->json(['message'=>'Successfully Updated!'],200); 
                }   
               else{    
               //$teacher=Teacher::where('id','=',$id)->first();
               if($sections->teacher_id==$id){
                   $updated=Teacher::where("id","=",$id)->update(['name' => $request['name'],'email' =>$request['email'],'contact'=> $request['contact'],]);
                   $sections->update(['teacher_id' =>$id]);   
                   \DB::commit();
                   return response()->json(['message'=>'Successfully Updated!'],200); 
                 }
                else{
                 $assignTeacher=Section::where('id','=',$request->section_id)->with("adviser")->get();
                 return response()->json(['teacher'=>$assignTeacher->get(0)->adviser->name,'section'=>$assignTeacher->get(0)->name],200);
                }
                }  
             }
             else{ 

               $sections=Section::where('id','=',$request->section_id)->with("adviser")->get();  
              //The current assigned teacher in this section  where teacher_id must be null
                $requestSections=Section::where('teacher_id','=',$request->updateId)->update(['teacher_id'=>null]);
              //The assigned Teacher from the ($sections=Section::where('name','=',$request->section_id)->with("adviser")->get()) where section_id must be null
               $currentTeacher=Teacher::where('id','=',$sections->get(0)->adviser->id)->update(['section_id'=>null]);
              //The teacher has new assigned sections
                $newSection=Teacher::where('id','=',$request->updateId)->update(['name' => $request['name'],'email' =>$request['email'],'contact'=> $request['contact'],'section_id'=>$sections->get(0)->id]);
              //The new assigned sections has a new teacher 
                $newTeacher=Section::where('id','=',$sections->get(0)->id)->update(['teacher_id'=>$request->updateId]);
                \DB::commit();
                return response()->json(['newSection'=>'Successfully Updated!'],200); 
             }   
          }
           
          }
          catch(\Exception $e){
          \DB::rollback();
           return response()->json(['error' => $e->getMessage()],500);
       }
    }
    

   }





//Function For Getting All OF All Avalable Sectiong
// public function availableSection(){
//     try{
//     $sectionTable=Section::where('teacher_id','=',null)->cursor();
//     $arraySection=[];
//     foreach($sectionTable as $sec){
//       $sec->name="Gr. ".$sec->gradelevel->grade_level." --- ".$sec->name;   
//       array_push($arraySection,$sec->makeHidden(['id','teacher_id','gradelevel','total_students','capacity','gradelevel_id','students_id','created_at','updated_at']));
//     }
//     return response()->json($arraySection); 
//    } 
//     catch(\Exception $e){
//          return response()->json(['error'=> $e->getMessage()],500);
//    } 
// }


}
