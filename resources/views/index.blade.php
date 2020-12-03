@extends('head.index')

@section('landing')
<div>
    <div class="content">
        <img src="{{ asset('images/mnhs.jpg') }}" alt="">
        <div class="content-bottom">
            <div class="bottom-container">
                <h1>Mantalongon National High School</h1>
                <h2>Welcome you this year's enrollment</h2>
                <a class="btn" href="{{ url('/enroll') }}">Enroll Now!</a>
                <div class="sign-up">
                    <span>Sign in to view enrollment status. <a href="">Click here</a></span>
                </div>
            </div>
        </div>
    </div>
</div>
<example-component></example-component>
@endsection