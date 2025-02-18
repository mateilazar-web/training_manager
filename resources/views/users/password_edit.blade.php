@extends('layout')

@section('content')


@if ($errors->any())
<div class="alert alert-danger">
    <strong>Whoops!</strong> There were some problems with your input.<br><br>
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Change Password</h2>
        </div>
        <div class="pull-right">
            <a class="btn btn-primary" href="{{ route('users.show', $user->id) }}"> Back</a>
        </div>
    </div>
</div>


<form method="POST" action="{{ route('users.change_password', $user->id) }}">
    @csrf

    <div class="row">

        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Old Password</strong>
                <input type="password" name="old_password" id="old_password" class="form-control">
            </div>
        </div>

        <div>
            <strong>New Password</strong>
            <input type="password" name="new_password" id="new_password" class="form-control">
        </div>
        <div>
            <strong>Confirm New Password</strong>
            <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="form-control">
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
            <button type="submit" class="btn btn-primary">Change Password</button>
        </div>

    </div>

</form>

@endsection