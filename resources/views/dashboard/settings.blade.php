@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="form-group">
                <label for="token">API Token:</label>
                <input class="form-control" type="text" name="token" value="{{ $user->api_token }}" class="form-group">
            </div>
            <div class="form-group clearfix">
                <a href="{{ route('settings.token.refresh') }}" class="pull-right btn btn-lg btn-success">Refresh</a>
            </div>
            <form action="{{ route('settings.update') }}" method="post">
                <div class="form-group">
                    <label for="ms_usd">How much MS is equal to 1 USD ?</label>
                    <div class="clearfix">
                        <div class="col-xs-11 nopadding-left">
                            <input class="form-control" type="number" name="ms_usd" value="{{ $user->ms_usd }}">
                        </div>
                        <div class="col-xs-1 nopadding-right nopadding-left">
                            <input class="btn btn-primary" type="submit" value="Save">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection