@extends('layouts.ProtectedPagesTemplateContent')

@section('content')
<meta http-equiv="Expires" content="Tue, 01 Jan 1995 12:12:12 GMT">
<meta http-equiv="Pragma" content="no-cache">
<div class="park-container"></div>
<div class="clearfix"></div>
<div class="park-page-content text-center" ng-controller="TimerCtrl">
    <div class="text-park-head">Park</div>
 
    <div id="timerwrap">
        <div id="timerbox" class="hasCountdown">
         <timer interval="1000" autostart="true" />
            <div class="col-md-4">
                 <span class="countdown_amount"><%hhours%></span>
                <br><span>Hours</span>
            </div>
            <div class="col-md-4">
                <span class="countdown_amount"><%mminutes%></span>
                <br><span>Minutes</span>
            </div>
            <div class="col-md-4">
                <span class="countdown_amount font-red"><%sseconds%></span>
                <br><span>Seconds</span>
            </div>
            <div class="clearfix"></div>	
        </div>
    </div>
    <p class="text-muted text-center text-park">
        Please press Chat to unpark.<br />
        You can view any other page without unparking.
    </p>
</div>
@stop

@section('script')  
    <script src="<?php echo URL::asset('js/angular/app/park.js') ?>"></script>
@stop
