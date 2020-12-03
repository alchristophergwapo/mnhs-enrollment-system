@extends('head.index')

@section('dashboard')

<header class="dashboard-header">
    <div class="logo">
        <img src="images/logo.jpg" alt="">
        <h1>Welcome to Mantalongon, Dalaguete NHS</h1>
    </div>
    <div class="user">
        <p>Hello, <span>Christopher</span></p>
    </div>
    <a href="" class="btn">Logout</a>
</header>
<div class="dashboard-container">
    <div class="dashboard-content">
        <h3>Congrats, you are officially enrolled to MNHS!</h3>
        <br>
        <div class="row">
            <div class="col-md-4">
                <div class="profile">
                    <div class="card">
                        <img src="{{ asset('images/avatar.png') }}" alt="" class="avatar">
                        <div class="card-body">
                            <div class="student-name">
                                <h5>Christopher Alonzo</h5>
                            </div>
                            <div class="details">
                                <div class="lrn">
                                    <p>LRN: 303000123456</p>
                                </div>
                                <div class="grade">
                                    <p>Grade: 7</p>
                                </div>
                                <div class="section">
                                    <p>Section: Rose</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="table-container">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Age</th>
                                <th>Address</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Alonzo, Christopher</td>
                                <td>20 yrs. old</td>
                                <td>Dalaguete</td>
                            </tr>
                            <tr>
                                <td>Caballero, Danica</td>
                                <td>20 yrs. old</td>
                                <td>Moalboal</td>
                            </tr>
                            <tr>
                                <td>Cabungcag, Chilla Jean</td>
                                <td>20 yrs. old</td>
                                <td>Badian</td>
                            </tr>
                            <tr>
                                <td>Villahermosa, Jericho James</td>
                                <td>20 yrs. old</td>
                                <td>Dalaguete</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection