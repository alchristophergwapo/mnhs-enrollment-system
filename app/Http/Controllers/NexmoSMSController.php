<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Exception;

class NexmoSMSController extends Controller
{
    /**
     * Write code on Method
     *
     * @return response()
     */

    public function SMS($id)
    {
        try {
            $basic = new \Nexmo\Client\Credentials\Basic('1c01b8e6', '3ejmjCn4UUs2kMxV');
            $client = new \Nexmo\Client($basic);
            $phone = Enrollment::where('id', '=', $id)->with('student')->get();

            $number = '63' . Str::substr($phone->get(0)->student->contact, 1);

            $senTo = "Hi " . $phone->get(0)->student->firstname . " " . $phone->get(0)->student->middlename . " " . $phone->get(0)->student->lastname . ",\n\n";

            $sendFrom = "Regards,\n\nMantalongon National High School\n\n\n";

            // $sectionEnrolled = 'You are enrolled in section ' . $phone->get(0)->student_section .".\n";

            $username = $phone->get(0)->student->LRN;

            $password = $phone->get(0)->student->lastname . $phone->get(0)->student->LRN;

            $account = "And you can log-in using the following credentials below:\n\n" . 'USERNAME=' . $username . "\n\nPASSWORD=" . $password . "\n\n";

            $message = $client->message()->send([
                'to' => '' . $number . '',
                'from' => 'Mantalongon National',
                'text' => '' . $senTo . '' . "CONGRATULATIONS!\nYour enrollment has been approved.\n" . '' . $account . '' . '' . $sendFrom . '',
            ]);

            return response()->json(['success' => 'success'], 200);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
