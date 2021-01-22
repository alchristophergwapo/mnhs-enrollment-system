@extends('head.head')

@section('sign-in')

<div class="sign-in col-md-6">
    <div class="container">
        <div class="sign-in-header">
            <h1 class="heading">Sign in</h1>
            <h3 class="sub-heading">Welcome!</h3>
        </div>
        <form action="{{ url('/enrolled') }}" class="sign-in-form">
            @csrf
            <div class="form-group">
                <label for="lrn">Learner's Reference No. (LRN)</label>
                <input type="text" class="form-input">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="" id="" class="form-input">
            </div>
            <div class="form-group">
                <input type="checkbox" name="remember" id="remember">
                <label for="">Remember me</label>
            </div>
            <div class="form-group">
                <input type="submit" class="btn" value="Sign in">
            </div>
        </form>
    </div>
</div>
<div class="side-d col-md-6">
    <div class="system-name">
        <h1 class="s-heading">MNHS</h1>
        <h1 class="s-subheading">Enrollment System</h1>
    </div>
    <div class="image">
        <img src="{{ asset('images/student-login.png') }}" alt="">
    </div>
</div>

@endsection