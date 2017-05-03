
@extends('layouts.ProtectedPagesTemplateContent')
@section('content')

<div class="setting-container">
    <div class="content-header">
        <div class="pull-left">
            <h4 class="header-title"><strong>Settings</strong></h4>
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="row" ng-controller="settingsController">
        <div class="col-lg-12 col-md-12 col-sm-8 col-xs-9 setting-tab-container">
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 setting-tab-menu">
                <div class="list-group">
                    <a href="#" class="list-group-item active">Chat Settings</a>
                </div>
            </div>
            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9 setting-tab">
                    <!-- flight section -->
                    <div class="setting-tab-content active">
                        <h2 class="side-header">Chat Settings</h2>
                        <div class="setting-inner-content">
                            <form class="form-horizontal" method="POST" action="#">
                                <div class="form-group margin-bottom5">
                                    <label class="col-md-2 control-label">Maximum Conversation per Operator</label>
                                    <div class="col-md-6">
                                            <input type="text" id="max_operator_conversation" name="max_operator_conversation" ng-model="conversation.value" class="form-control" value="<%conversation.value%>">
                                    </div>
                                    <div class="form-group margin-bottom5">
                                     <md-button layout="column" class="md-fab md-primary save-settings md-raised" aria-label="Save" ng-click="saveSettings('max_conversation', conversation.value)">
                                                <md-icon class="fa fa-check fa-2x"></md-icon>
                                            </md-button>
                                </div>
                                </div>

                                 <div class="form-group margin-bottom5">
                                    <label class="col-md-2 control-label">Unmapping Timeout</label>
                                    <div class="col-md-6">
                                            <input type="text" id="wait_time_to_unmap_from_operator" name="wait_time_to_unmap_from_operator" ng-model="timeout.value" class="form-control" value="<%timeout.value%>">
                                            <small><i>in milliseconds</i></small>
                                    </div>
                                    <div class="form-group margin-bottom5">
                                     <md-button layout="column" class="md-fab md-primary save-settings md-raised" aria-label="Save" ng-click="saveSettings('unmap_time',timeout.value)">
                                                <md-icon class="fa fa-check fa-2x"></md-icon>
                                            </md-button>
                                </div>
                                </div>
                            
                                
                            </form>
                        </div>
                    </div>
              </div>    
</div> 



<!-- Scripts -->
<script src="http://ajax.googleapis.com/ajax/libs/angularjs/1.3.14/angular.min.js"></script>
<script src="<?php echo URL::asset('js/angular/angular.min.js') ?>"></script>
    <script src="<?php echo URL::asset('js/jquery-2.1.1.min.js') ?>"></script>
    <script src="<?php echo URL::asset('js/bootstrap.min.js') ?>"></script>
    <script src="<?php echo URL::asset('js/toastr.js') ?> "></script>

<!-- AngularJS Application Scripts -->
 <script src="<?php echo URL::asset('js/angular/app/app.js') ?>"></script>
    <script src="<?php echo URL::asset('js/angular/directives/dirPagination.js') ?>"></script>
    <script src="<?php echo URL::asset('js/angular/factory/transformRequestAsFormPost.js') ?>"></script>
      <script src="<?php echo URL::asset('js/angular-toastr.tpls.js')?>"></script>
    <link rel="stylesheet" href="<?php echo URL::asset('css/angular-toastr.css')?>" />
    
    <script src="<?php echo URL::asset('js/angular/controllers/settings.js') ?>"></script>
    <script src="<?php echo URL::asset('js/angular/app/settings.js') ?>"></script>

</html>
@stop