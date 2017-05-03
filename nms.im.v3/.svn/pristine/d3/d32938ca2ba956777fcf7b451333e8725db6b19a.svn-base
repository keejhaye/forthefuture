
@extends('layouts.ProtectedPagesTemplateContent')
@section('css')
<link rel = "stylesheet" href = "{{url('/')}}/css/pre_loader.css"/>
@stop
@section('content')
<div class="online-users-container" ng-controller="OnlineUsersController as users">

  <div class="content-header">
    <div class="pull-left">
      <h4 class="header-title"><strong>Online Users</strong> <span ng-hide="users == 0" ng-model="users" class="count img-circle"><%users.length%></span></h4>
    </div>
    <div class="clearfix"></div>
  </div>
  <div ng-show="users == 0" class='waiting'>
            <ul class="spinner">
                <li></li>
                <li></li>
                <li></li>
                <li></li>
            </ul>
        </div>
  <div ng-hide="users == 0" class="row" id="onlinecontent">
    <div class="col-md-10">
      <table id="conversation-history-datatable" class="table table-striped table-bordered dt-responsive" cellspacing="0" width="100%">
        <thead>
          <tr>
            <th class="text-center">Username</th>
            <th class="text-center">Full Name</th>
            <th class="text-center">Position</th>
            <th class="text-center">IP Address</th>
            <th class="text-center">Status</th>
            <th class="text-center">Action</th>
          </tr>
        </thead>

        <tbody>
          <tr dir-paginate="user in users|itemsPerPage:10" >
            <td class="text-center"><%user.username%></td>
            <td class="text-center"><%user.firstname%> <%user.lastname%></td>
            <td class="text-center"><%user.name%></td>
            <td class="text-center"><%user.url%></td>
            <td class="text-center"><%user.status%></td>
            <td class="text-center">
              <a ng-show="user.name === 'super'" href="#"><i data-toggle="tooltip" title="No Action" class="fa fa-circle" aria-hidden="true"></i></a>
              <a ng-show="user.name != 'super'" href="#" ng-click="kick(user.user_id)"><i data-toggle="tooltip" title="SignOff" class="fa fa-sign-out" aria-hidden="true"></i></a>
            </td>
          </tr>
         <tr>
                    <td colspan="9" id="pagination">
            <dir-pagination-controls
                max-size="3"
                direction-links="true"
                boundary-links="true" >
            </dir-pagination-controls>
            </td>
                    </tr>
                              
        
        </tbody>
      </table>
    </div>
    <div class="col-md-2">
      <h2 class="side-header">Available User Per Service</h2>
      <table id="conversation-history-datatable" class="table table-striped table-bordered dt-responsive" cellspacing="0" width="100%">
        <thead>
          <tr>
            <th class="text-center">Pool</th>
            <th class="text-center">Available</th>
          </tr>
        </thead>
        <tbody>
          <tr ng-repeat='service in services'>
            <td class="text-center"><%service.name%></td>
            <td class="text-center"><%service.assigned%></td>
          </tr>
        
        </tbody>
      </table>
    </div>
    <div class="clearfix"></div>

</div>
</div>

<link rel="stylesheet" href="<?php echo URL::asset('css/bootstrap.min.css')?>">
    <link rel="stylesheet" href="<?php echo URL::asset('css/dataTables.bootstrap.min.css')?>">
    <link rel="stylesheet" href="<?php echo URL::asset('css/responsive.bootstrap.min.css')?>">
    <link rel="stylesheet" href="<?php echo URL::asset('css/bootstrap-datetimepicker.min.css')?>">
<!-- Scripts -->
<script src="<?php echo URL::asset('js/jquery-2.1.1.min.js')?>"></script>
<script src="<?php echo URL::asset('js/moment.min.js')?>"></script>
<script src="<?php echo URL::asset('js/bootstrap.min.js')?>"></script>
<script src="<?php echo URL::asset('js/bootstrap-datetimepicker.min.js')?>"></script>
<script src="<?php echo URL::asset('js/jquery.dataTables.min.js')?>"></script>
<script src="<?php echo URL::asset('js/dataTables.bootstrap.min.js')?>"></script>
<script src="<?php echo URL::asset('js/dataTables.responsive.min.js')?>"></script>
<script src="http://ajax.googleapis.com/ajax/libs/angularjs/1.3.14/angular.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.1/bootstrap3-typeahead.min.js"></script>  

<!-- AngularJS Application Scripts -->
<script src="<?php echo URL::asset('js/angular/app/app.js') ?>"></script>
<script src="<?php echo URL::asset('js/angular/directives/dirPagination.js') ?>"></script>
<script src="<?php echo URL::asset('js/angular/factory/transformRequestAsFormPost.js') ?>"></script>

<script src="<?php echo URL::asset('js/angular/controllers/online_users.js') ?>"></script>
<script src="<?php echo URL::asset('js/angular/app/online_users.js') ?>"></script>
@stop


