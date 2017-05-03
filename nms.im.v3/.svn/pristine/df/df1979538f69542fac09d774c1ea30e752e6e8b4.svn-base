@extends('layouts.ProtectedPagesTemplateContent')

@section('css')
<link rel="stylesheet" href="<?php echo URL::asset('css/dataTables.bootstrap.min.css')?>">
<link rel="stylesheet" href="<?php echo URL::asset('css/bootstrap-datetimepicker.min.css')?>">
<link href="http://demo.expertphp.in/css/jquery.ui.autocomplete.css" rel="stylesheet">
<style type="text/css">
    .loading {
        background-color: #ffffff;
        bottom: 0;
        left: 0;
        opacity: 0.82;
        position: fixed;
        right: 0;
        top: 0;
        z-index: 999;
    }
    .loading-img {
        left: 50%;
        position: fixed;
        top: 50%;
    }
</style>
@stop

@section('content')
    <div ng-controller="historyController">
        <div class="loading" ng-if="generating == true">
          <img src="../img/Preloader_11.gif" class="loading-img">
        </div>
        <div class="content-header">
            <div class="pull-left">
                <h4 class="header-title"><strong>Conversation History</strong></h4>
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="page-search-form">
            <form class="form-horizontal" role="form">
                <div class="col-md-4">
                  
        <div class="form-group margin-bottom5">
                    <label class="col-md-2 control-label">Service</label>
                    <div class="col-md-10">
                            <input type="text" id="service" ng-model="search.service" name="service" placeholder="Service Name" class="form-control">
                    </div>
                </div>
                <div class="form-group margin-bottom5">
                    <label class="col-md-2 control-label">User</label>
                    <div class="col-md-10">
                            <input type="text" ng-model="search.user" id="user" name="user" placeholder="User" class="form-control">
                    </div>
                </div>
                <div class="form-group margin-bottom5">
                    <label class="col-md-2 control-label">Status</label>
                    <div class="col-md-10">
                        <select id="status_filter" ng-model='search.status_filter' name="status_filter" class="form-control">
                            <option ng-repeat="(key, name) in status_filter" value="<%key%>"><%name%></option>
                        </select>
                    </div>
                </div>
                </div>

                <div class="col-md-4">
                   
                    <div class="form-group margin-bottom5">
                        <label class="col-md-3 control-label">Start Date</label>
                        <div class="col-md-9">
                            <div class="input-group date" id="datetimepicker6">
                                <input ng-model="start_date" id="sDate" class="form-control" type="text">
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group margin-bottom5">
                        <label class="col-md-3 control-label">End Date</label>
                        <div class="col-md-9">
                            <div class="input-group date" id="datetimepicker7">
                                <input ng-model="end_date" id="eDate" class="form-control" type="text">
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                                <div class="form-group margin-bottom5">
                        <label class="col-md-2 control-label">Timezone</label>
                        <div class="col-md-10">
                            <select ng-model="search.timezone" id="timezone" name="timezone" class="form-control" ng-change="setTimezone(search.timezone)">
                                <option ng-repeat="(key, name) in timezones" value="<%key%>"><%name%></option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12">
                            <button ng-click="fetchConversations()" class="btn btn-primary pull-right btn-block" id="Search"> <span>Search</span> <i class="fa fa-search"></i> </button>
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
            </form>
        </div>
        <table id="conversation-history-datatable" class="table table-striped table-bordered dt-responsive" cellspacing="0" width="100%">
            <thead>

                <tr>
                    <th class="text-center">CDID</th>
                    <th class="text-center">Service</th>
                    <th class="text-center">Subscriber</th>
                    <th class="text-center">Persona</th>
                    <th>Inbound Message</th>
                    <th>Outbound Message</th>
                    <th class="text-center">Operator</th>
                    <th>Status</th>
                   <!--  <th class="text-center">Actions</th> -->
                </tr>
            </thead>
           <tbody>  

                   <tr class="info" ng-if="fetchedConversations == 0">
                        <td class="text-center" colspan="8"><strong>NO DATA</strong></td>
                    </tr>
                    
                     <tr dir-paginate="row in fetchedConversations.result|itemsPerPage:20">
                        <td><%row.id%></td>
                        <td><%row.service%></td>
                        <td><%row.subscriber%></td>
                        <td><%row.persona%></td>
                        <td><p ng-bind-html='row.inbound'><%row.inbound%></p><small><b><%row.inbound_time | date:'medium' : dateTz %> <i>(<%row.inbound_time | timeAgo%>)</i></b></small></td>
                        <td><p ng-bind-html='row.outbound'><%row.outbound%></p><small><b><%row.outbound_time | date:'medium' : dateTz %> <i ng-hide="row.outbound === null">(<%row.outbound_time | timeAgo%>)</i></b><br/></small></td>
                        <td><%row.operator%></td>
                        <td><p ng-if="row.operator !== null"><%row.status%></p> <p ng-if="row.operator === null">pending</p><small ng-if="row.status === 'ended' && row.operator !== 'bot'"><p data-toggle="tooltip" title="duration from assigned time to time ended">s: <%timediff(row.assigned_time,row.time_ended) | songTime %></p>
                        <p data-toggle="tooltip" title="duration from time started to time ended">h: <%timediff(row.time_started,row.time_ended) | songTime%></p></small></td>
                         <td class="text-center">
                        <a href="#" data-toggle="modal" data-target="#traces"><i data-toggle="tooltip" title="Traces" class="fa fa-list-alt" aria-hidden="true" ng-click="getConversationTraces(row.id)"></i></a>
                        <a href="#" data-toggle="modal" data-target="#logs"><i data-toggle="tooltip" title="Logs" class="fa fa-comment" aria-hidden="true" ng-click="getConversationLogs(row.id, row.conversation_id)"></i></a>
                        <!-- <a href="#" data-toggle="modal" data-target="#assigned"><i data-toggle="tooltip" title="Assign Operator" class="fa fa-user" aria-hidden="true"></i></a> -->
                    </td>

                    </tr>
                     <tr class="info" ng-if="fetchedConversations == 0">
                        <td colspan="8"></strong></td>
                    </tr>
                     <tr>
                    <td colspan="9" id="pagination">
            <dir-pagination-controls
                max-size="3"
                direction-links="true"
                boundary-links="true" >
            </dir-pagination-controls>
            </td>
                    <tr/>
                </tbody>
        </table>
<div class="modal fade traces" id="traces" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document" >
    <div class="modal-content">
      <div class="modal-header" >
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Conversation Instance <%cdid%></h4>
      </div>
      <div class="modal-body">
        <table class="table modal-table">
                    <tbody>
                        <tr ng-repeat="item in Traces">
                            <td><%item.time%></td>
                            <td><%item.message%></td>
                        </tr>
                    </tbody>
                </table>
      </div>
      <div class="modal-footer custom-modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade logs" id="logs" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Conversation Instance <%cdid%></h4>
      </div>
      <div class="modal-body no-padding" >
                <div class="col-md-6 no-padding">
                    <ul class="no-padding" dir-paginate="log in logs|itemsPerPage:5">
                         <li ng-if="log.direction=='outbound'"class="persona">
                            <%log.message%>
                            <div title="Jun 03, 2016 5:13 PM" class="date">39 minutes ago,<%log.persona%></div>
                            <p class="m-id"><%log.operator%> [mid:<%log.message_id%>]</p>
                        </li>
                        <li ng-if="log.direction === 'inbound'"class="subscriber">
                             <%log.message%>
                            <div title="Jun 03, 2016 5:13 PM" class="date">39 minutes ago,<%log.subscriber%></div>
                            <p class="m-id">[mid:<%log.message_id%>]</p>
                        </li>
                    </ul>
                    <div id="log_pagination">
                     <dir-pagination-controls
                max-size="3"
                direction-links="true"
                boundary-links="true" >
            </dir-pagination-controls>
            </div>
                </div>
        <div class="col-md-6 no-padding">
            <div class="form-group">
            <form class="form-horizontal" role="form">
                            <div class="col-md-12 no-padding">
                                <div class="pop-comment-logs">
                                <h5 ng-if="comments == 0"class="text-center">No Comment</h5>
                                <ul ng-repeat = "comment in comments">
                                    <li><%comment.comment%></li>
                                </ul>
                                    <form class="add-comment">
                                        <div class="form-group">
                                            <label class="col-md-2 control-label">Message ID</label>
                                            <div class="col-md-12">
                                                    <input type="text" id="message_id" name="message_id" class="form-control" value="">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-2 control-label">Comment</label>
                                            <div class="col-md-12">
                                                    <textarea id="comment" name="comment" class="form-control" rows="2"></textarea>
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                    </form>
                                </div>
                            </div>
                             <div class="modal-footer custom-modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-success" ng-click="addComment(conversation_id)">Save changes</button>
      </div>
                    </form>
                    </div>
            </div>
            <div class="clearfix"></div>
     
    </div>
  </div>
</div>
</div>
<div class="modal fade assign" id="assigned" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Conversation Instance 1749305</h4>
      </div>
      <div class="modal-body">
        <table class="table modal-table">
                    <thead>
                        <tr>
                            <th>Online Operators</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Conversation is already assigned</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td>June 03 2016 (05:45:45 pm)</td>
                            <td>A new conversation instance started</td>
                        </tr>
                        <tr>
                            <td>June 03 2016 (05:45:45 pm)</td>
                            <td>A new message has arrived</td>
                        </tr>
                        <tr>
                            <td>June 03 2016 (05:45:47 pm)</td>
                            <td>Conversation is being mapped to an available operator</td>
                        </tr>
                    </tbody>
                </table>
      </div>
      <div class="modal-footer custom-modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-success">Save changes</button>
      </div>
    </div>
  </div>
</div>





<!-- Styles -->
@section('script')  
<script src="//cdnjs.cloudflare.com/ajax/libs/angular-moment/0.9.0/angular-moment.min.js"></script>

<script src="<?php echo URL::asset('js/angular/app/app.js') ?>"></script>
<script src="<?php echo URL::asset('js/angular/directives/dirPagination.js') ?>"></script>
<script src="<?php echo URL::asset('js/angular/factory/transformRequestAsFormPost.js') ?>"></script>

<script src="<?php echo URL::asset('js/angular/controllers/history.js') ?>"></script>
<script src="<?php echo URL::asset('js/angular/app/history.js') ?>"></script>
<script src="<?php echo URL::asset('js/jquery-2.1.1.min.js')?>"></script>
<script src="<?php echo URL::asset('js/moment.min.js')?>"></script>
<script src="<?php echo URL::asset('js/bootstrap.min.js')?>"></script>
<script src="<?php echo URL::asset('js/bootstrap-datetimepicker.min.js')?>"></script>
<script src="<?php echo URL::asset('js/jquery.dataTables.min.js')?>"></script>
<script src="<?php echo URL::asset('js/dataTables.bootstrap.min.js')?>"></script>
<script src="<?php echo URL::asset('js/dataTables.responsive.min.js')?>"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.1/bootstrap3-typeahead.min.js"></script>  
<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.4.8/angular.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.4.8/angular-sanitize.js"></script>
<script src="http://demo.expertphp.in/js/jquery-ui.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js"></script>

<!-- load angular-moment -->
<script src="//cdnjs.cloudflare.com/ajax/libs/angular-moment/0.9.0/angular-moment.min.js"></script>

<!-- load angular-moment -->





@stop
