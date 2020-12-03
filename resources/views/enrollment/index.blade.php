@extends('head.index')

@section('enroll')
<div class="enrollment">
    <div class="header">
        <img src="images/logo.jpg" alt="">
        <h1>Welcome to Mantalongon, Dalaguete NHS</h1>
        <a href="{{ url('/not-enrolled') }}" class="btn">Login</a>
    </div>

    <div class="form">
        <div class="form-header">
            Please fill out the information below and SUBMIT.
        </div>

        <div class="form-container">
            <form action="{{ url('/sign-in') }}" method="" class="enrollment">
                @csrf
                <div class="student">
                    <div class="form-head">Student information</div>
                    <div class="form-row">
                        <div class="col-md-4 col-sm-4">
                            <label for="psa">PSA Birth Certificate No.</label>
                            <input type="text" class="form-input" id="psa">
                        </div>
                        <div class="col-md-4 col-sm-4">
                            <label for="lrn">Learners Reference No. (LRN)</label>
                            <input type="text" class="form-input" id="lrn" placeholder="">
                        </div>
                        <div class="col-md-4 col-sm-4">
                            <label for="average">Average&nbsp;</label>
                            <input type="number" class="form-input" id="average" placeholder="">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-4 col-sm-4">
                            <label for="fname">First name</label>
                            <input type="text" class="form-input" id="fname">
                        </div>
                        <div class="col-md-4 col-sm-4">
                            <label for="mname">Middle name</label>
                            <input type="text" class="form-input" id="mname" placeholder="">
                        </div>
                        <div class="col-md-4 col-sm-4">
                            <label for="lname">Last name</label>
                            <input type="text" class="form-input" id="lname" placeholder="">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-4 col-sm-4 datepicker">
                            <label for="dob">Date of Birth</label>
                            <input type="date" class="form-input" id="dob">
                        </div>
                        <div class="col-md-4 col-sm-4">
                            <label for="age">Age</label>
                            <input type="number" class="form-input" id="age" placeholder="">
                        </div>
                        <div class="col-md-4 col-sm-4">
                            <label for="">Gender</label>
                            <div id="gender">
                                <div class="male">
                                    <input type="checkbox" class="form-check-input" placeholder="" id="male"
                                        value="male">
                                    <label for="male">Male</label>
                                </div>
                                <div class="female">
                                    <input type="checkbox" class="form-check-input" placeholder="" id="female"
                                        value="female">
                                    <label for="female">Female</label>
                                </div>
                            </div>
                            <br><br>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-4 col-sm-4">
                            <div class="label-ip">Belonging to any Indegenous People (IP) Community/Indegenous Cultural
                                Community?</div>
                        </div>
                        <div class="col-md-2 col-md-2 col-sm-2">
                            <input type="checkbox" class="form-check-input" id="indigenous" value="yes">
                            <label for="indigenous">YES</label>
                        </div>
                        <div class="col-md-2 col-sm-2">
                            <input type="checkbox" class="form-check-input" id="indigenous" value="no">
                            <label for="indigenous">NO</label>
                        </div>
                        <div class="col-md-4 col-sm-4">
                            <div class="check-indigenous">
                                If yes, please specify: <input type="text" class="form-input">
                            </div>
                        </div>
                    </div>
                    <div class="space"></div>

                    <div class="form-row">
                        <div class="col-md-6 col-sm-6">
                            <label for="">Mother Tongue</label>
                            <input type="text" class="form-input">
                        </div>
                        <div class="col-md-6 col-sm-6">
                            <label for="contact">Contact Number</label>
                            <input type="number" class="form-input" id="contact">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col-md-6 col-sm-6">
                            <label for="address">Address</label>
                            <input type="text" class="form-input" name="address" id="address">
                        </div>
                        <div class="col-md-6 col-sm-6">
                            <label for="zip_code1">Zip Code &nbsp;&nbsp;&nbsp;</label>
                            <input type="number" class="zipcode" pattern="[0-9]*" id="zip_code1" name="zip_code1">
                            <input type="number" class="zipcode" pattern="[0-9]*" id="zip_code2" name="zip_code2">
                            <input type="number" class="zipcode" pattern="[0-9]*" id="zip_code3" name="zip_code3">
                            <input type="number" class="zipcode" pattern="[0-9]*" id="zip_code4" name="zip_code4">
                            <br><br><br>
                            <br>
                        </div>
                    </div>
                </div>
                <div class="parent">
                    <div class="form-head">
                        Parent/guardian information
                    </div>

                    <div class="form-row">
                        <div class="col-md-6 col-sm-6">
                            <label for="father">Father's Name</label>
                            <input type="text" class="form-input">
                        </div>
                        <div class="col-md-6 col-sm-6">
                            <label for="mother">Mother's Maiden Name</label>
                            <input type="text" class="form-input">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col-md-6 col-sm-6">
                            <label for="guardian">Guardians's Name</label>
                            <input type="text" class="form-input">
                        </div>
                        <div class="col-md-6 col-sm-6">
                            <label for="contact">Contact Number</label>
                            <input type="text" class="form-input">
                        </div>
                    </div>
                </div>

                <div class="balik-aral">
                    <div class="balik-aral-h">For Returning Learners (Balik Aral) and Those Who Shall Transfer/ Move In
                    </div>

                    <div class="form-row">
                        <div class="col-md-6 col-sm-6">
                            <label for="grade-level">Last Grade Completed</label>
                            <input type="text" class="form-input">
                        </div>
                        <div class="col-md-6 col-sm-6">
                            <label for="school-year">Last School Year Completed</label>
                            <input type="text" class="form-input">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-6 col-sm-6">
                            <label for="school-name">School name</label>
                            <input type="text" class="form-input">
                        </div>
                        <div class="col-md-6 col-sm-6">
                            <label for="school-id">School ID</label>
                            <input type="text" class="form-input">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-6 col-sm-6">
                            <label for="school-address">School Adress</label>
                            <input type="text" class="form-input">
                        </div>
                    </div>
                </div>

                <div class="space-s"></div>

                <div class="senior-high">
                    <div class="form-head">For Senior High Learners</div>

                    <div class="form-row sem">
                        <div class="col-md-12">
                            <span>Semester</span>
                            <input type="checkbox" name="" id="first-sem">
                            <label for="first-sem">First Semester</label>
                            <input type="checkbox" name="" id="second-sem">
                            <label for="second-sem">Second Semester</label>
                        </div>
                        <br><br>
                    </div>
                    <div class="form-row">
                        <div class="col-md-6 col-sm-6">
                            <label for="track">Track</label>
                            <select class="form-control" id="track">
                                <option>1</option>
                                <option>2</option>
                                <option>3</option>
                                <option>4</option>
                                <option>5</option>
                            </select>
                        </div>
                        <div class="col-md-6 col-sm-6">
                            <label for="strand">Strand (if any)</label>
                            <select class="form-control" id="strand">
                                <option>1</option>
                                <option>2</option>
                                <option>3</option>
                                <option>4</option>
                                <option>5</option>
                            </select>
                            <br><br>
                        </div>
                    </div>
                </div>
                <div class="certify">
                    <div class="form-row">
                        <div class="col-md-12">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;I hereby certify that the
                            above information given are tru and correct to the best of my knowledge and I allow the
                            Department of Education to use my child’s details to create and/ or update his/her learner
                            profile in the Learner Information System. The information herein shall be treated as
                            confidential in compliance with the Data Privacy Act of 2012.</div>
                    </div>
                </div>
                <div class="space-s"></div>
                <div class="signature">
                    <div class="form-row">
                        <center>
                            <div class="enroll-date">
                                <input type="date" name="" id="" class="form-input" value="<?= date("Y-m-d"); ?>">
                                <label for="enroll-date">Date</label>
                            </div>
                        </center>
                    </div>
                </div>

                <div class="submit">
                    <button type="submit" class="btn btn-submit">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection