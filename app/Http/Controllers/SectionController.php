<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GradeLevel;
use App\Models\Section;
use App\Models\Teacher;

use Illuminate\Support\Str;
use App\Http\Requests\SectionRequest;
use App\Http\Requests\UpdateSectionRequest;
class SectionController extends Controller
{

  public function allSections(){
    $sections = Section::with('gradelevel')->get();
    return response()->json(['sections' => $sections],200);
  }

  //Function For Adding Section In Junior High School
  public function addAnySection(SectionRequest $request){
    $addSection=$request->validated();
    if($addSection){
      try{
        \DB::beginTransaction();
      if($request->teacher==null){
          $section=Section::create($addSection);
          $grade=GradeLevel::where('grade_level','=',$request['grade'])->first();
          $updated=Section::where('id','=',$section->id)->update(['gradelevel_id'=>$grade->id]);
           if($grade->sections==null){
             $grade->update(['sections'=>$section->id]); 
             \DB::commit();
             return ['message'=>'Successfully Added!'];
               }
         else{  
             $grade->update(['sections'=>$grade->sections.",".$section->id]); 
            \DB::commit();
             return ['message'=>'Successfully Added!'];    
            } 
       }

     else{
       $teachers=Teacher::where('id','=',$request->teacher)->first();
        if($teachers->section_id!=null){
           $teachers=Teacher::where('id','=',$teachers->id)->with("section")->get();
           return response()->json(['failed'=>$teachers->get(0)->section->name,'teacher'=>$teachers->get(0)->name],200); 
         }
       else{
           $createSection=Section::create($addSection);
           $updateSection=Section::where('id','=',$createSection->id)->update(['teacher_id'=>$teachers->id]);
           $updateTeacher=Teacher::where('id','=',$teachers->id)->update(['section_id'=>$createSection->id]);
           $updateGrade=GradeLevel::where('grade_level','=',$request['grade'])->first();
           $update=Section::where('id','=',$createSection->id)->update(['gradelevel_id'=>$updateGrade->id]);
         if($updateGrade->sections==null){
              $updateGrade->update(['sections'=>$createSection->id]); 
              \DB::commit();
              return ['message'=>'Successfully Added!'];
                }
          else{  
              $updateGrade->update(['sections'=>$updateGrade->sections.",".$createSection->id]); 
             \DB::commit();
              return ['message'=>'Successfully Added!'];    
             } 
        }

     }
         
    }
        catch(\Exception $e){
          \DB::rollback();
          return response()->json(['error' => $e->getMessage()],500);
      }
    }   


}


//Function For Getting The All Sections In GradelevelSections
 public function allGradeLevelSections(){
   try{
  $arraySection=[];
  $data=Section::cursor();
  foreach($data as $val){
        if($val->gradelevel){
           $val->gradelevel->makeHidden(['students','sections','created_at','updated_at']);
            if($val->teacher_id==null){
              $val->gradelevel_id=$val->teacher_id;
              $val->teacher_id="No Teacher";
              array_push($arraySection,$val->makeHidden(['students','created_at','updated_at']));
            }
            else{
             $teacher=Teacher::where('id','=',$val->teacher_id)->first();
             $val->gradelevel_id=$val->teacher_id;
             $val->teacher_id=$teacher->name;
             array_push($arraySection,$val->makeHidden(['students_id','created_at','updated_at']));
            }
        }
  }

  return ['message'=>'Successfully Added!',"sections"=>$arraySection]; 
   }
   catch(\Exception $e){
    return response()->json(['error' => $e->getMessage()],500);
 }


 }



//Function For Deleting Any Sections
public function delAnySection($id){
 try{
   \DB::beginTransaction();
    $section=Section::where('id','=',$id)->with('gradelevel')->get();
    $objectGrade=Str::of($section->get(0)->gradelevel->sections)->split('/[\s,]+/');
    $remove=$objectGrade->diff([$section->get(0)->id]);
    $newSection=null;
    foreach($remove as $val){
       if($newSection==null){
          $newSection.=$val;
         }
       else{
        $newSection.=",".$val;
       }
    } 
   //delete automatically if teacher_id is not null
   if($section->get(0)->teacher_id==null){
    $gradelevel_section=Gradelevel::findOrFail($section->get(0)->gradelevel_id);
    $gradelevel_section->update(['sections'=>$newSection ]); 
    $del=Section::findOrFail($id)->delete();
    \DB::commit();
     return ['message'=>'Successfully Added!','section'=>'Grade '.$section->get(0)->gradelevel->grade_level]; 
   }
   else{
    $gradelevel_section=Gradelevel::findOrFail($section->get(0)->gradelevel_id);
    $gradelevel_section->update([
        'sections' =>$newSection
     ]); 
      $teacher=Teacher::where('id','=',$section->get(0)->teacher_id)->first();
      $teacher->section_id=null;
      $teacher->save();
      $del=Section::findOrFail($id)->delete();
      \DB::commit();
    return ['message'=>'Successfully Added!','section'=>'Grade '.$section->get(0)->gradelevel->grade_level]; 
   }
 
  
   }
  catch(\Exception $e){
    \DB::rollback();
    return response()->json(['error' => $e->getMessage()],500);
  }


}



//Function For Updating A Specific Section
public function updateSection(UpdateSectionRequest $request,$id){
  $updateSection=$request->validated();
  if($updateSection){
    try{
      \DB::beginTransaction();
    if($id!='update'){
      if($request->teacher==null){
        $section=Section::where('id',"=",$id)->update(['name'=>$request['name'],'capacity'=>$request['capacity']]); 
        \DB::commit();
        return ['message'=>'Successfully Added!','section'=>$section]; 
     }
     else{
      $infoTeacher=Teacher::where('id','=',$request->teacher)->first();
      if($infoTeacher->section_id!=null){
        if($infoTeacher->section_id==$id){
         $section=Section::where('id',"=",$id)->update(['name'=>$request['name'],'capacity'=>$request['capacity']]); 
         \DB::commit();
         return ['message'=>'Successfully Added!'];   
        }
        else{
          $assignTeacher=Teacher::where('id','=',$request->teacher)->with("section")->get();
          return response()->json(['failed'=>$assignTeacher->get(0)->section->name,'teacher'=>$assignTeacher->get(0)->name],200);
        }
       
      }
      else{
       $currentSec_idTeacher=Teacher::where('section_id','=',$id)->update(['section_id'=>null]);
       $updateSec=Section::where('id',"=",$id)->update(['name'=>$request['name'],'capacity'=>$request['capacity'],'teacher_id'=>$infoTeacher->id]);
       $infoTeacher->update(['section_id'=>$id]);
       \DB::commit();
       return ['message'=>'Successfully Added!'];  
      }  

     }

    }

   else{

   $Teachers=Teacher::where('id','=',$request->teacher)->with("section")->get();

   //currentSection_id from the section name of the $request['teacher']
   $currentSection_id=Teacher::where('section_id','=',$request->updateId)->update(['section_id'=>null]);
   //currentTeacher_id from the current assigned Teacher to be updated to null 
   $currentTeacher=Section::where('id','=',$Teachers->get(0)->section->id)->update(['teacher_id'=>null]);
   //The new data values from the currentSection of the "Id" you want to be update
   $updateSec=Section::where('id',"=",$request->updateId)->update(['name'=>$request['name'],'capacity'=>$request['capacity'],'teacher_id'=>$Teachers->get(0)->id]);
   //Set section_id to null from the assigned id of the request teacher
   $updateTeacher=Teacher::where('id','=',$Teachers->get(0)->id)->update(['section_id'=>$request->updateId]);
   \DB::commit();
   return response()->json(['newTeacher'=>'Successfully Updated!'],200); 
   } 

     }
     catch(\Exception $e){
      \DB::rollback();
      return response()->json(['error' => $e->getMessage()],500);
    }
  }
 
}

<<<<<<< HEAD

//Function For Getting All Teachers For Sections
public function allTeachersForSection()
{
 try{
    $arrayTeacher=[];
    $List=Teacher::cursor();
   foreach($List as $teacher){
          $teacher->makeHidden(['contact','students_id','section_id','email','updated_at']);
          $teacher->created_at=$teacher->id;
          array_push($arrayTeacher,$teacher);   
     }
       return response()->json($arrayTeacher,200); 
=======
//Function Filter For Getting The Selected GradLevel 
public function selectedGradeLevel($id){
   $grade=GradeLevel::where('grade_level','=',$id)->first();
   if($grade->sections!=null){
     $allSections=[];
    $result=Str::of($grade->sections)->split('/[\s,]+/');
    foreach($result as $val){
      $section=Section::where('id','=',$val)->first();
      array_push($allSections,$section->name);
>>>>>>> 9ba7111ce1c95686d853f0124a4506d804e7b7dd
    }
    catch(\Exception $e){
     return response()->json(['error'=> $e->getMessage()],500);
    } 
}


}

