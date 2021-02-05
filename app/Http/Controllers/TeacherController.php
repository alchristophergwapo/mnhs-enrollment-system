<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Teacher;

class TeacherController extends Controller
{
  
   //Adding a new Teachers
   public function addTeacher(Request $request)
   {
        $valid=$request->validate([
            'name' => ['required','string','max:50'],
            'email' => ['required','string','email','max:50'],
            'contact' => ['required','string','max:11','digits:11'],  
            'student_id'=>[],
            'section_id'=>[] 
        ]);
  try{
        $teacher=Teacher::create($valid);
        return response()->json($teacher); 
      }
      catch(\Exception $e){
        return response()->json(['error' => $e->getMessage()], 401);  
      }
   }


   //Getting All Teachers
   public function allTeachers()
   {
       try{
         $List=Teacher::all();
         return response()->json($List); 
       }
       catch(\Exception $e){
        return response()->json(['error'=> $e->getMessage()], 401);
       }  
   }

   //Deleting or Removing A Teachr
   public function removeTeacher($id) {
       try{
         $del=Teacher::findOrFail($id)->delete();
         return response()->json("success");
       }
        catch(\Exception $e){
        return response()->json(['error' => $e->getMessage()], 401);
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
           return response()->json("updated");
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
