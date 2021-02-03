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

       $teacher=Teacher::create($valid);
       return response()->json($teacher);   
   }

   //Getting All Teachers
   public function allTeachers(Request $request)
   {
       $List=Teacher::all();
        return response()->json($List);   
   }

   //Deleting or Removing A Teachr
   public function removeTeacher($id) {
       $del=Teacher::findOrFail($id)->delete();
       return response()->json("sucess");
   }

//Updating the data of Teachers
   public function updateTeacher(Request $request,$id){
       try{
           $valid=$request->validate([
               'name' => ['required', 'string','max:50'],
               'email' => ['required', 'string','email','max:50'],
               'contact' => ['required', 'string','max:11','digits:11'],    
           ]);

           $valid=Teacher::findOrFail($id);

           $valid->update([
               'name' => $request['name'],
               'email' =>$request['email'],
               'contact'=> $request['contact']
            ]);  

           $valid->save();         
           return response()->json("updated");
       }
        catch (Throwable $e){         
       }

   }

   //Showing by id of a teacher
   public function showByIdTeacher($id){
       $teacher=Teacher::findOrFail($id);
       return response()->json($teacher); 
   }  


}
