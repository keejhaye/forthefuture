
@extends('layouts.ProtectedPagesTemplateContent')
@section('content')

<div>
    <div class="users-container" ng-controller="flaggedController">
        <div class="content-header">
            <div class="pull-left">
                <h4 class="header-title"><strong>Flagged Messages</strong></h4>
            </div>
            <div class="pull-right bulletins">
                <form class="navbar-form navbar-right">
                    <div class="input-group" id="adv-search">
                        <div class="input-group-btn">
                            <form class="form-horizontal" role="form" >
                                <div class="form-group">
                                    <label class="control-label">Keyword:</label>
                                    <input type="text" id="keyword" name="keyword" ng-model="search" class="form-control" placeholder="Keyword">
                                </div>                              
                            </form>
                        </div>
                    </div>
                </form>
                <div class="clearfix"></div>
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="row">
            <div class="col-md-3">
                <h2 class="side-header">Messages</h2>

                <div class="bulletin-list">
                    <ul>
                        <li dir-paginate="flag in flagged|filter:search|itemsPerPage:10" >

                            <a href="#" ng-click="toggle('edit', flag.flagged_id, flag.conversation_id, flag.message_id)"><i class="fa fa-dot-circle-o" aria-hidden="true"></i><%flag.bound_time%><%flag.username%></br> [<%flag.message_id%>]<%flag.message%> </a>
                            <div ng-show="flag.status == 'approved'" style='float:right'><img src="<?php echo URL::asset('img/mail-success.png') ?>"/></div>
                             <div ng-show="flag.status == 'rejected'" style='float:right'><img src="<?php echo URL::asset('img/mail-fail.png') ?>"/></div>
                             <div ng-show="flag.status == 'pending'" style='float:right'><img src="<?php echo URL::asset('img/question.png') ?>"/></div>
                        </li>
                    </ul>
                    <dir-pagination-controls
                        max-size="3"
                        direction-links="true"
                        boundary-links="true" >
                    </dir-pagination-controls>

                </div>
            </div>
            <div>
                <h2 class="side-header">Message History</h2>
                <div class="col-md-6 no-padding">
           <div class="logs">
           <ul class="no-padding" dir-paginate="message in messages|itemsPerPage:5" pagination-id="flagged_history_logs">
            <li ng-if="message.direction=='outbound'"class="persona">
                <%message.message%>
                <div title="Jun 03, 2016 5:13 PM" class="date"><%message.persona%></div>
                <p class="m-id">[operator:<%message.operator%>]</p>
                <div ng-show="message.id === id" style='float:left' class="imFlagchat">&#9873;</div>
            </li>
            <li ng-if="message.direction === 'inbound'"class="subscriber">
                 <%message.message%>
                <div title="Jun 03, 2016 5:13 PM" class="date">3<%message.subscriber%></div>
             </li>
           </ul>
           <div id="log_pagination">
             <dir-pagination-controls
              max-size="3"
              pagination-id="flagged_history_logs"
              direction-links="true"
              boundary-links="true" >
              </dir-pagination-controls>
           </div>
           </div>  
                <div class="bulletin-info">
                    <form name="frmPersonas" class="form-horizontal" novalidate="">
                        <fieldset>
                            <div class="form-group margin-bottom5">
                                <label class="col-md-2 control-label">Message ID</label>
                                <div class="col-md-6">
                                    <input type="text" id="id" name="id" class="form-control" ng-model="flag.message_id" ng-required="true" value="<%id%>">
                                
                                </div>
                            </div>
                              <div class="form-group margin-bottom5">
                                <label class="col-md-2 control-label">Outbound by</label>
                                <div class="col-md-6">
                                    <input type="text" id="username" name="username" class="form-control" ng-model="flag.outbound_operator" ng-required="true" value="<%outbound_operator%>">
                                </div>
                            </div>
                             <div class="form-group margin-bottom5">
                                <label class="col-md-2 control-label">Flagged by</label>
                                <div class="col-md-6">
                                    <input type="text" id="name" name="name" class="form-control" ng-model="flag.operator" ng-required="true" value="<%operator%>">
                                </div>
                            </div>
                            
                             <div class="form-group margin-bottom5">
                                <label class="col-md-2 control-label">Moderated by</label>
                                <div class="col-md-6">
                                    <input type="text" id="name" name="name" class="form-control" ng-model="flag.moderated_by" ng-required="true" value="<%moderated_by%>">
                                
                                </div>
                            </div>
                            <div class="form-group margin-bottom5">
                                <label class="col-md-2 control-label">Date Flagged</label>
                                <div class="col-md-6">
                                    <input type="text" id="name" name="name" class="form-control" ng-model="flag.date_flagged" ng-required="true" value="<%date_flagged%>">
                                   
                                </div>
                            </div>
                             <div ng-show="flag.date_moderated != null"class="form-group margin-bottom5">
                                <label class="col-md-2 control-label">Date Moderated</label>
                                <div class="col-md-6">
                                    <input type="text" id="date_moderated" name="date_moderated" class="form-control" ng-model="flag.date_moderated" ng-required="true" value="<%date_moderated%>">
                                </div>
                            </div>
                        
                            <div class="form-group margin-bottom5">
                                <label class="col-md-2 control-label">Comments</label>
                                <div class="col-md-6">
                                    <textarea name="comments" ng-model="flag.comments" id="comments" class="form-control"><%comments%></textarea>
                                </div>
                            </div>
                            <div class="form-group margin-bottom5">
                                <label class="col-md-2 control-label">Status</label>
                                <div class="col-md-6">
                                   <label ng-show="flag.status == 'approved'">Approved</label>
                                    <label ng-show="flag.status == 'rejected'">Rejected</label>
                                     <label ng-show="flag.status == 'pending'">Pending</label>
                            </div>
                            <div class="form-group margin-bottom5">
                            <button type="submit" ng-click="save(flag.flagged_id, 'approved')" class="btn btn-success">Approve</button>

                            <button type="submit" ng-click="save(flag.flagged_id, 'rejected')" class="btn btn-danger">Reject</button>
                        </div>
                        </fieldset>
                        
                    </form>

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
    
    <script src="<?php echo URL::asset('js/angular/controllers/flagged.js') ?>"></script>
    <script src="<?php echo URL::asset('js/angular/app/flagged.js') ?>"></script>
        <script src="<?php echo URL::asset('js/angular-toastr.tpls.js')?>"></script>
    <link rel="stylesheet" href="<?php echo URL::asset('css/angular-toastr.css')?>" />

</html>
@stop