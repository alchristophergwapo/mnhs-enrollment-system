<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GradeLevel;
use App\Models\Section;
use App\Models\Teacher;

use Illuminate\Support\Str;
use App\Http\Requests\SectionRequest;
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
        $section=Section::create($addSection);
        $grade=GradeLevel::where('grade_level','=',$request['grade'])->first();
        $updated=Section::findOrFail($section->id);
        $updated->update(['gradelevel_id'=>$grade->id]); 
      if($grade->sections==null){
         $grade->update(['sections'=>$section->name]); 
         \DB::commit();
         return ['message'=>'Successfully Added!'];
        }
     else{  
         $grade->update(['sections'=>$grade->sections.",".$section->name]); 
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


//Functin For Getting The Specific Section In A GradeLevel For A Junior High School
public function specificSection($grade){
  try{
    $arraySection=[];
    $grade=GradeLevel::where('grade_level','=',$grade)->first();
    $data=Section::all();
    foreach($data as $val){
        if($val->gradelevel_id==$grade->id){
          if($val->teacher_id==null){
            array_push($arraySection,$val->makeHidden(['student_id','created_at','updated_at']));
          }
          else{
          $teacher=Teacher::where('id','=',$val->teacher_id)->first();
          $val->teacher_id=$teacher->name;
          array_push($arraySection,$val->makeHidden(['student_id','created_at','updated_at']));
          }
        
        }
    }
    return ['message'=>'Successfully Added!',"sections"=>$arraySection,'grade'=>$grade->grade_level]; 
  }
  catch(\Exception $e){
    return response()->json(['error' => $e->getMessage()],500);
 }

 }
 

//Function For Getting The All Sections In Grade 7
 public function grade7($grade){
   try{
  $arraySection=[];
  $grade=GradeLevel::where('grade_level','=',$grade)->first();
  $data=Section::all();
  foreach($data as $val){
      if($val->gradelevel_id==$grade->id){
        if($val->teacher_id==null){
         array_push($arraySection,$val->makeHidden(['gradelevel_id','student_id','created_at','updated_at']));
        }
        else{
        $teacher=Teacher::where('id','=',$val->teacher_id)->first();
        $val->teacher_id=$teacher->name;
        array_push($arraySection,$val->makeHidden(['gradelevel_id','student_id','created_at','updated_at']));
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
    $remove=$objectGrade->diff([$section->get(0)->name]);
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
    $gradelevel_section->update([ 'sections'=>$newSection ]); 
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
 
  //return response()->json($section);

   }
  catch(\Exception $e){
    \DB::rollback();
    return response()->json(['error' => $e->getMessage()],500);
  }


}


//Function For Editing A Specific Section
public function editSection($id){
   try{
    $section=Section::findOrFail($id);
    return ['message'=>'Successfully Added!','section'=>$section];  
   }
   catch(\Exception $e){
    return response()->json(['error' => $e->getMessage()],500);
  }
}


//Function For Updateing A Specific Section
public function updateSection(SectionRequest $request,$id){
  $updateSection=$request->validated();
  if($updateSection){
    try{
      \DB::beginTransaction();
      $section=Section::findOrFail($id);
      $section->update([
        'name'=>$request['name'],
        'capacity'=>$request['capacity']
      ]); 
      \DB::commit();
      return ['message'=>'Successfully Added!','section'=>$section];  
     }
     catch(\Exception $e){
      \DB::rollback();
      return response()->json(['error' => $e->getMessage()],500);
    }
  }
 
}

}
