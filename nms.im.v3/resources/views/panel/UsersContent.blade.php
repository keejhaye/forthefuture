
@extends('layouts.ProtectedPagesTemplateContent')
@section('content')

<div>
    <div class="users-container" ng-controller="usersController">
        <div class="content-header">
            <div class="pull-left">
                <h4 class="header-title"><strong>Users</strong></h4>
            </div>

            <div class="pull-right bulletins">
                <button class="pull-right btn btn-custom" ng-click="resetForm(frmUsers, true)">Create New User</button>
                <form class="navbar-form navbar-right">
                    <div class="input-group" id="adv-search">
                        <div class="input-group-btn">
                            <form class="form-horizontal" role="form" >
                                <div class="form-group">
                                    <label class="control-label">Username:</label>
                                    <input type="text" id="service" name="service"  ng-model="username" ng-change="searchUsers(username, username)" ng-pattern="/^[a-zA-Z0-9._-]+$/" ng-trim="false" class="form-control" placeholder="Username">
                                </div>
                                <div class="form-group">
                                <label for="role">User Role:</label>
                                    <select class="form-control" id="role" name="role" ng-model="role_id" ng-change="searchUsers(role_id, role_id)">
                                    <option value="" disabled selected>Role</option>
                                        <option value="all">All</option>
                                        <option value="3">Admin</option>
                                        <option value="4">Manager</option>
                                        <option value="5">Operator-FT</option>
                                        <option value="6">Customer</option>
                                        <option value="7">Operator-FL</option>           
                                        <option value="8">Team Leader</option>
                                    </select>
                            </div>

                            <div class="form-group">
                                    <select class="form-control" id="status" name="status" ng-model="search.status">
                                    <option value="" disabled selected>Status</option>
                                        <option value="">All</option>
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                    </select>
                            </div>
                            </form>


                        </div>
                    </div>
                </form>


                <div class="clearfix"></div>
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="row" >
            <div class="col-md-3">
                <h2 class="side-header">Users List</h2>
                <div class="bulletin-list">

                    <ul ng-show="users.length <= 0">
                        <li style="text-align:center;">Loading users!</li>
                    </ul>
                    <ul>
                        <li dir-paginate="user in users|filter:search|itemsPerPage:itemsPerPage" total-items="total_count" >
                          
                           <a ng-if="user.status == 'inactive'" href="#" ng-click="toggle('edit', user.id)"><i class="fa fa-dot-circle-o" aria-hidden="true" style="color:red"><%user.username%>  | <%user.firstname%> <%user.lastname%> </i> </a>
                           <a ng-if="user.status == 'active'" href="#" ng-click="toggle('edit', user.id)"><i class="fa fa-dot-circle-o" aria-hidden="true"><%user.username%>  | <%user.firstname%> <%user.lastname%> </i> </a>
                        </li>
                    </ul>
                    <dir-pagination-controls
                        max-size="3"
                        direction-links="true"
                        boundary-links="true" 
                        on-page-change="getData(newPageNumber)" >
                    </dir-pagination-controls>
                </div>
            </div>

            <div class="col-md-9">
                <h2 class="side-header">User Information</h2>
                <div class="bulletin-info">
                    <form name="frmUsers" class="form-horizontal" novalidate="" ng-disabled="">
                        <fieldset>
                            <legend>Company Profile</legend>
                            <div class="form-group margin-bottom5">
                                <label class="col-md-2 control-label">Username</label>
                                <div class="col-md-6">
                                    <input type="text" id="username" name="username" class="form-control" ng-model="user.username" ng-required="true" value="<%username%>">
                                    <span class="help-inline" 
                                          ng-show="frmUsers.username.$invalid && frmUsers.username.$touched">Username field is required</span>
                                </div>
                            </div>
                            <div class="form-group margin-bottom5">
                                <label class="col-md-2 control-label">Password</label>
                                
                                <div class="col-md-6" ng-show="id == null">
                                    <input type="password" id="password" name="password" ng-model="user.password" class="form-control" ng-required="true" value="">
                                <span class="help-inline" 
                                          ng-show="frmUsers.password.$invalid && frmUsers.password.$touched">Password field is required</span>
                                </div>
                                
                                <div class="col-md-6" ng-show="id != null">
                                    <input type="password" id="password" name="password" ng-model="user.password" class="form-control" ng-required="true" value="UNCHANGED">
                                <span class="help-inline" 
                                          ng-show="frmUsers.password.$invalid && frmUsers.password.$touched">Password field is required</span>
                                </div>
                            </div>
                            <div class="form-group margin-bottom5">
                                <label class="col-md-2 control-label"> Confirm Password</label>
                                <div class="col-md-6">
                                    <input type="password" id="password_confirm" name="password_confirm" ng-model='user.password_confirm' class="form-control" value="<%password%>">
    
                                </div>   
                            </div>
                            <div class="form-group margin-bottom5">
                                <label class="col-md-2 control-label">Status</label>
                                <div class="col-md-2">
                                    <select id="status" ng-model="user.status" ng-required="true" name="status" class="form-control">
                                        <option value="active" ng-selected="true">active</option>
                                        <option value="inactive">inactive</option>
                                        <option value="hidden">delete</option>
                                    </select>
                                </div>
                            </div>
                        </fieldset>

                        <fieldset>
                            <legend>Personal Infomation</legend>
                            <div class="form-group margin-bottom5">
                                <label class="col-md-2 control-label">First Name</label>
                                <div class="col-md-6">
                                    <input type="text" id="firstname" name="firstname" class="form-control" ng-model="user.firstname" ng-required ="true" value="<%firstname%>">
                                </div>
                            </div>
                            <div class="form-group margin-bottom5">
                                <label class="col-md-2 control-label">Last Name</label>
                                <div class="col-md-6">
                                    <input type="text" id="lastname" name="lastname" class="form-control" ng-model="user.lastname" ng-required ="true" value="<%lastname%>">
                                </div>
                            </div>
                            <div class="form-group margin-bottom5">
                                <label class="col-md-2 control-label"> Email Address:</label>
                                <div class="col-md-6">
                                    <input type="text" id="email" name="email" class="form-control" ng-model="user.email" ng-required ="true" value="<%email%>">
                                </div>
                            </div>

                            <div class="form-group margin-bottom5">
                                <label class="col-md-2 control-label">Group</label>
                                <div ng-hide="user.role_id == 2" class="col-md-2">
                                    <select id="role_id" name="role_id" class="form-control" ng-model="user.role_id" ng-required="true" value="<%role_id%>">
                                        <option value="3">admin</option>
                                        <option value="4">manager</option>
                                        <option value="6">customer</option>
                                        <option value="5">operator-ft</option>
                                        <option value="7">operator-fl</option>
                                    </select>
                                </div>
                                 <div ng-hide="user.role_id != 2" class="col-md-2">Super</div>
                            </div>
                        </fieldset>
                        <div class="form-group margin-bottom5">
                            <button type="submit" ng-click="save(id)" class="btn btn-danger">Save</button>
                        </div>
                    </form>
                    <style type="text/css">
                        #assignService .modal-body {
                            overflow-y: auto;
                            max-height: 320px;
                            min-height: 320px;
                        }
                        .checkbox-lg input[type=checkbox] {
                            height:24px;
                            width:24px;
                            margin-top: -2px;
                            margin-left:-33px;
                        }
                        .padding10 {
                            padding-left: 10%;
                        }
                        .users-tabs {
                            margin-bottom: 120px
                        }
                        .service-list-container {
                            overflow-y: auto;
                            min-height: 160px;
                        }
                    </style>
                    <div class="users-tabs row" ng-show="showServices">
                        <div class="col-xs-12" ng-init="tab=1">
                            <ul class="nav nav-tabs" role="tablist">
                                <li role="presentation" class="active"><a href="#services" ng-click="tab = 1" aria-controls="services" role="tab" data-toggle="tab">Services</a></li>
                        
                            </ul>
                            <div class="tab-content">
                                <div role="tabpanel" class="tab-pane active" id="services" ng-show="tab == 1">
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <p class="text-left">
                                                <button class="btn btn-primary" data-toggle="modal" data-target="#assignService" ng-click="getUnassignedServices(id)">Assign Service</button>
                                            </p>
                                            <div class="well">
                                                <h4 class="text-center">Assigned Services</h4>
                                                <input type="text" class="input form-control" ng-model="searchUserService" placeholder="Type to search service">
                                            </div>
                                            <div class="service-list-container col-xs-12">
                                                <ul class="list-unstyled padding10">
                                                    <li dir-paginate="services in userServices|filter:searchUserService|itemsPerPage:5" pagination-id="userservicelist"> 
                                                        <label class="checkbox checkbox-lg">
                                                            <input type="checkbox" name="assignedService" ng-model="services.selected" value="<%services.service_id%>" ng-change="selectedList()"> <%services.name%> 
                                                        </label>
                                                    </li>
                                                </ul>
                                            </div>
                                            <dir-pagination-controls
                                                pagination-id="userservicelist"
                                                max-size="3"
                                                direction-links="true"
                                                boundary-links="true" >
                                            </dir-pagination-controls>
                                        </div>
                                        <div class="col-xs-12">
                                            <span class="pull-left"><a href="" ng-click="toggleAssigned(true)">Select All</a> | <a href="" ng-click="toggleAssigned(false)">Deselect All</a></span>
                                            <button type="button" class="btn btn-primary pull-right" ng-show="serviceSelected.length > 0" data-toggle="modal" data-target="#deleteAssignedServices">Delete Service</button>
                                        </div>
                                    </div>
                                </div>
                                <!-- Modal -->
                                <div class="modal fade" id="assignService" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                  <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                      <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <h4 class="modal-title" id="myModalLabel">Assign Service</h4>
                                        <h4 class="text-center">Unassigned Services List</h4>
                                        <input type="text" class="input form-control" ng-model="searchService" placeholder="Type to search service">
                                      </div>
                                      <div class="modal-body">
                                        <ul class="list-unstyled padding10" ng-repeat="unservices in visibleServices = (unassignedServices|filter:searchService)">
                                            <li> 
                                                <label class="checkbox checkbox-lg">
                                                    <input type="checkbox" ng-model="unservices.selected" value="<%unservices.id%>" ng-change="selectedListUnassigned()"> <%unservices.name%>
                                                </label>
                                            </li>
                                        </ul> 
                                      </div>
                                      <div class="modal-footer">
                                        <span class="pull-left"><a href="" ng-click="toggleUnassigned(true)">Select All</a> | <a href="" ng-click="toggleUnassigned(false)">Deselect All</a></span>
                                        <button type="button" class="btn btn-primary pull-right" data-dismiss="modal" ng-click="saveServicesToUser(id)">Save</button>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                                <div class="modal fade" tabindex="-1" role="dialog" id="deleteAssignedServices">
                                  <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                      <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <h4 class="modal-title">Delete User Service</h4>
                                      </div>
                                      <div class="modal-body">
                                        <p>Are you sure you want to delete?</p>
                                      </div>
                                      <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                        <button type="button" class="btn btn-primary" data-dismiss="modal" ng-click="deleteAssignedServices(id)">Yes</button>
                                      </div>
                                    </div><!-- /.modal-content -->
                                  </div><!-- /.modal-dialog -->
                                </div><!-- /.modal -->
                                <div role="tabpanel" class="tab-pane active" id="google" ng-show="tab == 2">testing</div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> 

</div>
<!-- Scripts -->
  {{-- <script src="http://ajax.googleapis.com/ajax/libs/angularjs/1.3.14/angular.min.js"></script> --}}
    <script src="<?php echo URL::asset('js/angular/angular.min.js') ?>"></script>
    <script src="<?php echo URL::asset('js/jquery-2.1.1.min.js') ?>"></script>
    <script src="<?php echo URL::asset('js/bootstrap.min.js') ?>"></script>
    <script src="<?php echo URL::asset('js/toastr.js') ?> "></script>
              
    <script src="<?php echo URL::asset('js/color-picker.min.js') ?>"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo URL::asset('css/color-picker.min.css') ?>" />
    <script src="<?php echo URL::asset('js/angular-toastr.tpls.js')?>"></script>
    <link rel="stylesheet" href="<?php echo URL::asset('css/angular-toastr.css')?>" />
    <!-- AngularJS Application Scripts -->
    <script src="<?php echo URL::asset('js/angular/app/app.js') ?>"></script>
    <script src="<?php echo URL::asset('js/angular/directives/dirPagination.js') ?>"></script>
    <script src="<?php echo URL::asset('js/angular/factory/transformRequestAsFormPost.js') ?>"></script>


    <script src="<?php echo URL::asset('js/angular/controllers/users.js') ?>"></script>
    <script src="<?php echo URL::asset('js/angular/app/users.js') ?>"></script>
    <script src="<?php echo URL::asset('js/angular-toastr.tpls.js')?>"></script>
    <link rel="stylesheet" href="<?php echo URL::asset('css/angular-toastr.css')?>" />
    </html>
    @stop