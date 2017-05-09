
@extends('layouts.ProtectedPagesTemplateContent')
@section('css')
<link rel="stylesheet" href="<?php echo URL::asset('css/dataTables.bootstrap.min.css')?>">
<link rel="stylesheet" href="<?php echo URL::asset('css/bootstrap-datetimepicker.min.css')?>">
@stop
@section('content')

<div ng-controller="subscribersController">
    <div class="users-container" >
                <div class="content-header">
            <div class="pull-left">
                <h4 class="header-title"><strong>Subscribers</strong></h4>
            </div>
                <form class="navbar-form navbar-right">
                    <div class="input-group" id="adv-search">
                        <div class="input-group-btn">
                            <form class="form-horizontal" role="form" >
                                <div class="form-group">
                                    <label class="control-label">Keyword:</label>
                                    <input type="text" id="keyword" name="keyword" ng-model="search.name" class="form-control" placeholder="Username">
                                </div>                              
                            </form>
                        </div>
                         <div class="form-group">
                                    <select class="form-control" id="status" name="status" ng-model="search.status">
                                    <option value="" disabled selected>Status</option>
                                        <option value="">All</option>
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                    </select>
                            </div>
                    </div>

                </form>
                <div class="clearfix"></div>
            </div>
             <div class="row" >
                <div class="col-md-3">
                <h2 class="side-header">Subscribers List</h2>

                     <div class="bulletin-list">

                        <ul ng-show="subscribers.length <= 0">
                            <li style="text-align:center;">Loading subscribers!</li>
                        </ul>

                        <ul>
                            <li dir-paginate="subscriber in subscribers|filter:search|itemsPerPage:itemsPerPage" total-items="total_count">
                             <a ng-if="subscriber.status == 'blocked'" href="#" ng-click="toggle('edit', subscriber.id)"><i class="fa fa-dot-circle-o" aria-hidden="true" style="color:#181518"><%subscriber.name%></i> </a>
                            <a ng-if="subscriber.status == 'inactive'" href="#" ng-click="toggle('edit', subscriber.id)"><i class="fa fa-dot-circle-o" aria-hidden="true" style="color:red"><%subscriber.name%> </i> </a>
                               <a ng-if="subscriber.status == 'active'" href="#" ng-click="toggle('edit', subscriber.id)"><i class="fa fa-dot-circle-o" aria-hidden="true"><%subscriber.name%></i> </a>
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
                        <h2 class="side-header">Subscriber Information</h2>
                        <div class="bulletin-info">
                            <form name="frmPersonas" class="form-horizontal" novalidate="">
                                <fieldset>
                                    <legend>Subscriber Profile</legend>
                                    <div class="form-group margin-bottom5">
                                <label class="col-md-2 control-label">Name</label>
                                <div class="col-md-6">
                                    <label class="col-md-2 control-label"><%subscriber.name%></label>
                                </div>
                            </div>

                            <div class="form-group margin-bottom5">
                                <label class="col-md-2 control-label">Status</label>
                                 <div class="col-md-6">
                                    <label class="col-md-2 control-label"><%subscriber.status%></label>
                                </div>
                            </div>
                            <div class="form-group margin-bottom5">
                                <label class="col-md-2 control-label">Profile</label>
                                <div class="col-md-6">
                                    <textarea name="profile" id="profile" class="form-control"><%subscriber.profile%></textarea>
                                </div>
                            </div>
                            <div class="form-group margin-bottom5">
                                <label class="col-md-2 control-label">Additional Information</label>
                                <div class="col-md-6">
                                    <textarea name="additional_info" id="additional_info" class="form-control"><%subscriber.additional_info%></textarea>
                                </div>
                            </div>
                        </fieldset>
                        <div class="form-group margin-bottom5">
                            <button type="submit" ng-click="save(id)" class="btn btn-danger">Save</button>
                            <button ng-show="id != null && subscriber.status != 'blocked'" type="submit" ng-click="addToBlacklist(id)" class="btn btn-primary">Add to Blacklist</button>
                        </div>
                        </form>

                        </div>
                    
                        <div class="users-tabs row" ng-show="id != null">
                            <div class="col-xs-12" ng-init="tab=1">
                                 <ul class="nav nav-tabs" role="tablist">
                                    <li role="presentation" class="active"><a href="#services" ng-click="tab = 1" aria-controls="services" role="tab" data-toggle="tab">Conversation History</a></li>
                        
                                </ul>
                                  <div class="tab-content">
                                        <div role="tabpanel" class="tab-pane active" id="services" ng-show="tab == 1">
                                            <div class="row">
                                                    <div class="col-lg-12">
                                                             
                                    
                                    <table class="table table-bordered table-condensed">
                                        <thead>
                                            <tr>
                                                <th>Subscriber</th>
                                                <th>Persona</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr ng-show="conversations == 0">
                                                <td colspan="4">
                                                    No Record
                                                </td>
                                            </tr>
                                            <tr dir-paginate="conversation in conversations|itemsPerPage:5" pagination-id="conversation">
                                                <td><%conversation.subscriber%></td>
                                                <td><%conversation.persona%></td>
                                                <td>
                                                    <a href="#" data-toggle="modal" data-target="#SubscriberMessageLogs" ng-click="getSubscriberConversationLogs(id)"><i data-toggle="tooltip" title="Logs" class="fa fa-comment" aria-hidden="true"></i></a>
                                                
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="4">
                                                    <dir-pagination-controls
                                                    pagination-id="conversation"
                                                    max-size="3"
                                                    direction-links="true"
                                                    boundary-links="true" >
                                                    </dir-pagination-controls>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <div class="modal fade logs" id="SubscriberMessageLogs" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                           <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                        <h4 class="modal-title" id="myModalLabel">Logs</h4>
                                                    </div>
                                                          <div class="modal-body no-padding" >
                                                                    <ul class="no-padding" dir-paginate="message in messages|itemsPerPage:5" pagination-id="subscriber_logs">
                                                                         <li ng-if="message.direction=='outbound'"class="persona">
                                                                            <%message.message%>
                                                                            <div title="Jun 03, 2016 5:13 PM" class="date"><%message.persona%></div>
                                                                            <p class="m-id">[mid:<%message.id%>]</p>
                                                                        </li>
                                                                        <li ng-if="message.direction === 'inbound'"class="subscriber">
                                                                             <%message.message%>
                                                                            <div title="Jun 03, 2016 5:13 PM" class="date">3<%message.subscriber%></div>
                                                                            <p class="m-id">[mid:<%message.id%>]</p>
                                                                        </li>
                                                                    </ul>
                                                                    <div id="log_pagination">
                                                                     <dir-pagination-controls
                                                                max-size="3"
                                                                pagination-id="subscriber_logs"
                                                                direction-links="true"
                                                                boundary-links="true" >
                                                            </dir-pagination-controls>
                                                            </div>
                                                        <div class="col-md-6 no-padding">
                                                            </div>
                                                            <div class="clearfix"></div>
     
                                                    </div>
                                                </div>
                                            </div>

                                    </div>
                                                    </div>
                                                </div>
                                        </div>
                                  </div>

                            </div>
                        </div>

                    </div>

             </div>

    </div>
</div>
                                            
<!-- Scripts -->

<script src="http://ajax.googleapis.com/ajax/libs/angularjs/1.3.14/angular.min.js"></script>
<script src="<?php echo URL::asset('js/angular/angular.min.js') ?>"></script>
<script src="<?php echo URL::asset('js/jquery-2.1.1.min.js') ?>"></script>
<script src="<?php echo URL::asset('js/bootstrap.min.js') ?>"></script>
<!-- AngularJS Application Scripts -->
<script src="<?php echo URL::asset('js/angular/app/app.js') ?>"></script>
<script src="<?php echo URL::asset('js/angular/directives/dirPagination.js') ?>"></script>
<script src="<?php echo URL::asset('js/angular/factory/transformRequestAsFormPost.js') ?>"></script>

<script src="<?php echo URL::asset('js/jquery.dataTables.min.js')?>"></script>
<script src="<?php echo URL::asset('js/dataTables.bootstrap.min.js')?>"></script>
<script src="<?php echo URL::asset('js/dataTables.responsive.min.js')?>"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.1/bootstrap3-typeahead.min.js"></script>  
<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.4.8/angular.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.4.8/angular-sanitize.js"></script>
<script src="http://demo.expertphp.in/js/jquery-ui.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js"></script>
    <script src="<?php echo URL::asset('js/toastr.js') ?> "></script>

<script src="<?php echo URL::asset('js/angular/controllers/subscribers.js') ?>"></script>
<script src="<?php echo URL::asset('js/angular/app/subscribers.js') ?>"></script>
    <script src="<?php echo URL::asset('js/angular-toastr.tpls.js')?>"></script>
    <link rel="stylesheet" href="<?php echo URL::asset('css/angular-toastr.css')?>" />


 

</html>
@stop