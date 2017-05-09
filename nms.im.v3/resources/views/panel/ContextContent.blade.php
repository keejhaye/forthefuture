
@extends('layouts.ProtectedPagesTemplateContent')
@section('content')
<meta http-equiv="Pragma" content="no-cache">
<div>
    <div class="users-container" ng-controller="servicesController as service">
        <div class="content-header">
            <div class="pull-left">
                <h4 class="header-title"><strong>Services</strong></h4>
            </div>
            <div class="pull-right bulletins">
                <button class="pull-right btn btn-custom" ng-click="resetForm(frmServices, true)">Create New Service</button>
                <form class="navbar-form navbar-right">
                    <div class="input-group" id="adv-search">
                        <div class="input-group-btn">
                            <form class="form-horizontal" role="form">
                                <div class="form-group">
                                    <label class="control-label">Keyword:</label>
                                    <input type="text" id="keyword" ng-model="search" name="keyword" class="form-control" placeholder="Keyword">
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
                <h2 class="side-header">Service List</h2>
                <div class="bulletin-list">

                    <ul ng-show="services.length <= 0">
                        <li style="text-align:center;">Loading services!</li>
                    </ul>
                    <ul>
                     <!-- <li ng-repeat="service in services"> -->
                        <li dir-paginate="service in services|filter:search|itemsPerPage:itemsPerPage" total-items="total_count" pagination-id="service">
                            <a href="#" ng-click="toggle('edit', service.id)"><i class="fa fa-dot-circle-o" aria-hidden="true"></i> <%service.name%></a>
                        </li>

                    </ul>
                    <dir-pagination-controls
                        pagination-id="service"
                        max-size="3"
                        direction-links="true"
                        boundary-links="true" 
                        on-page-change="getData(newPageNumber)" >
                    </dir-pagination-controls>

                </div>
            </div>
            <div class="col-md-9">
                <h2 class="side-header">Service Information</h2>
                <div class="bulletin-info">
                    <form name="frmServices" class="form-horizontal" novalidate="" ng-submit="save(id)">
                        <div class="form-group margin-bottom5">
                            <label class="col-md-2 control-label">Name</label>
                            <div class="col-md-6">
                                <input type="text" id="name" name="name" class="form-control" ng-model="service.name" ng-required="true" value="<%name%>">
                                <span class="help-inline" 
                                      ng-show="frmServices.name.$invalid && frmServices.name.$touched">Name field is required</span>
                            </div>
                        </div>

                        <div class="form-group margin-bottom5">
                            <label class="col-md-2 control-label">Nickname</label>
                            <div class="col-md-6">
                                <input type="text" id="nickname" name="nickname" ng-model="service.nickname" class="form-control" value="<%nickname%>" ng-required="true">

                            </div>
                        </div>
                        <div class="form-group margin-bottom5">
                            <label class="col-md-2 control-label">Code</label>
                            <div class="col-md-6">
                                <input type="text" id="code" name="service_code" ng-model="service.code" class="form-control" value="<%code%>">
                            </div>
                        </div>
                        <div class="form-group margin-bottom5">
                            <label class="col-md-2 control-label">Color</label>
                            <div class="col-md-6">
                                <input color-picker color-picker-model="service.color_theme" name="color_theme" id="color_theme" ng-model="service.color_theme" value="<%color_theme%>"/>
                            </div>
                        </div>

                        <div class="form-group margin-bottom5">
                            <label class="col-md-2 control-label">Timezone</label>
                            <div class="col-md-2">
                              <select id="timezone" ng-model="service.timezone" name="timezone" class="form-control">
                                    <option value="-12.0">(GMT -12:00) Eniwetok, Kwajalein</option>
                                    <option value="-11.0">(GMT -11:00) Midway Island, Samoa</option>
                                    <option value="-10.0">(GMT -10:00) Hawaii</option>
                                    <option value="-9.0">(GMT -9:00) Alaska</option>
                                    <option value="-8.0">(GMT -8:00) Pacific Time (US &amp; Canada)</option>
                                    <option value="-7.0">(GMT -7:00) Mountain Time (US &amp; Canada)</option>
                                    <option value="-6.0">(GMT -6:00) Central Time (US &amp; Canada), Mexico City</option>
                                    <option value="-5.0">(GMT -5:00) Eastern Time (US &amp; Canada), Bogota, Lima</option>
                                    <option value="-4.0">(GMT -4:00) Atlantic Time (Canada), Caracas, La Paz</option>
                                    <option value="-3.0">(GMT -3:00) Brazil, Buenos Aires, Georgetown</option>
                                    <option value="-2.0">(GMT -2:00) Mid-Atlantic</option>
                                    <option value="-1.0">(GMT -1:00 hour) Azores, Cape Verde Islands</option>
                                    <option value="0.0">(GMT) Western Europe Time, London, Lisbon, Casablanca</option>
                                    <option value="1.0">(GMT +1:00 hour) Brussels, Copenhagen, Madrid, Paris</option>
                                    <option value="2.0">(GMT +2:00) Kaliningrad, South Africa</option>
                                    <option value="3.0">(GMT +3:00) Baghdad, Riyadh, Moscow, St. Petersburg</option>
                                    <option value="4.0">(GMT +4:00) Abu Dhabi, Muscat, Baku, Tbilisi</option>
                                    <option value="5.0">(GMT +5:00) Ekaterinburg, Islamabad, Karachi, Tashkent</option>
                                    <option value="6.0">(GMT +6:00) Almaty, Dhaka, Colombo</option>
                                    <option value="7.0">(GMT +7:00) Bangkok, Hanoi, Jakarta</option>
                                    <option value="8.0">(GMT +8:00) Beijing, Perth, Singapore, Hong Kong</option>
                                    <option value="9.0">(GMT +9:00) Tokyo, Seoul, Osaka, Sapporo, Yakutsk</option>
                                    <option value="10.0">(GMT +10:00) Eastern Australia, Guam, Vladivostok</option>
                                    <option value="11.0" selected="selected">(GMT +11:00) Magadan, Solomon Islands, New Caledonia</option>
                                    <option value="12.0">(GMT +12:00) Auckland, Wellington, Fiji, Kamchatka</option>
                                </select>
                            </div>
                        </div>


                        <div class="form-group margin-bottom5">
                            <label class="col-md-2 control-label">Status</label>
                            <div class="col-md-2">
                                <select id="status" name="status" ng-model="service.status" class="form-control">
                                    <option value="active" selected="selected">active</option>
                                    <option value="inactive">inactive</option>

                                </select>
                            </div>
                        </div>

                        <div class="form-group margin-bottom5">
                            <label class="col-md-2 control-label">Allow Auto-end</label>
                            <div class="col-md-2">
                                <select id="allow_auto_end_conversation" name="allow_auto_end_conversation" ng-model="service.auto_end_conversation" class="form-control">
                                    <option value="0" selected="selected">No</option>
                                    <option value="1">Yes</option>

                                </select>
                            </div>
                        </div>

                        <div class="form-group margin-bottom5">
                            <label class="col-md-2 control-label">Allow Blacklist</label>
                            <div class="col-md-2">
                                <select id="allow_blacklist" name="allow_blacklist" ng-model="service.allow_blacklist" class="form-control">
                                    <option value="0" selected="selected">No</option>
                                    <option value="1">Yes</option>

                                </select>
                            </div>
                        </div>

                        <div class="form-group margin-bottom5">
                            <label class="col-md-2 control-label">Allow Multiple Reply</label>
                            <div class="col-md-2">
                                <select id="allow_multiple_reply" name="allow_multiple_reply" ng-model="service.allow_multiple_reply" class="form-control">
                                    <option value="0" selected="selected">No</option>
                                    <option value="1">Yes</option>

                                </select>
                            </div>
                        </div>

                        <div class="form-group margin-bottom5">
                            <label class="col-md-2 control-label">Allow Attach Image</label>
                            <div class="col-md-2">
                                <select id="allow_attach_image" name="allow_attach_image" ng-model="service.attach_image" class="form-control">
                                    <option value="0" selected="selected">No</option>
                                    <option value="1">Yes</option>

                                </select>
                            </div>
                        </div>

                        <div class="form-group margin-bottom5">
                            <label class="col-md-2 control-label">Allow URL</label>
                            <div class="col-md-2">
                                <select id="allow_url" name="allow_url" ng-model="service.allow_url" class="form-control">
                                    <option value="0" selected="selected">No</option>
                                    <option value="1">Yes</option>

                                </select>
                            </div>
                        </div>

                        <div class="form-group margin-bottom5">
                            <label class="col-md-2 control-label">Min Character Limit</label>
                            <div class="col-md-6">
                                <input type="text" id="min_char" name="min_char" ng-model="service.min_char" class="form-control" value="<%min_char%>">
                            </div>
                        </div>

                        <div class="form-group margin-bottom5">
                            <label class="col-md-2 control-label">Max Character Limit</label>
                            <div class="col-md-6">
                                <input type="text" id="max_char" name="max_char" ng-model="service.max_char" class="form-control" value="<%max_char%>">
                            </div>
                        </div>

                        <div class="form-group margin-bottom5">
                            <label class="col-md-2 control-label">Set Timer</label>
                            <div class="col-md-6">
                                <input type="text" id="reply_timer" name="reply_timer" ng-model="service.reply_timer" class="form-control" value="<%reply_timer%>">
                            </div>
                        </div>

                        <div class="form-group margin-bottom5">
                            <label class="col-md-2 control-label">Aggregator Username</label>
                            <div class="col-md-6">
                                <input type="text" id="aggregator_username" name="aggregator_username" ng-model="service.aggregator_username" class="form-control" value="<%aggregator_username%>">
                            </div>
                        </div>

                        <div class="form-group margin-bottom5">
                            <label class="col-md-2 control-label">Aggregator Password</label>
                            <div class="col-md-6">
                                <input type="text" id="aggregator_password" name="aggregator_password" ng-model="service.aggregator_password" class="form-control" value="<%aggregator_password%>">
                            </div>
                        </div>

                        <div class="form-group margin-bottom5">
                            <label class="col-md-2 control-label">Aggregator URL</label>
                            <div class="col-md-6">
                                <input type="text" id="aggregator_url" name="aggregator_url" ng-model="service.aggregator_url" class="form-control" value="<%aggregator_url%>">
                            </div>
                        </div>

                        <div class="form-group margin-bottom5">
                            <label class="col-md-2 control-label">Listener Username</label>
                            <div class="col-md-6">
                                <input type="text" id="listener_username" name="listener_username" ng-model="service.listener_username" class="form-control" value="<%listener_username%>">
                            </div>
                        </div>

                        <div class="form-group margin-bottom5">
                            <label class="col-md-2 control-label">Listener Password</label>
                            <div class="col-md-6">
                                <input type="text" id="listener_password" name="listener_password" ng-model="service.listener_password" class="form-control" value="<%listener_password%>">
                            </div>
                        </div>
                        <div class="form-group margin-bottom5">
                            <label class="col-md-2 control-label">Email Inactivity</label>
                            <div class="col-md-2">
                                <select id="email_inactivity" name="email_inactivity" ng-model="service.email_inactivity" class="form-control">
                                    <option value="1" selected="selected">enabled</option>
                                    <option value="0">disabled</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group margin-bottom5">
                            <label class="col-md-2 control-label">Create New Message</label>
                            <div class="col-md-2">
                                <select id="create_new_message" name="create_new_message" class="form-control">
                                    <option value="enabled" selected="selected">Yes</option>
                                    <option value="disabled">No</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group margin-bottom5">
                            <label class="col-md-2 control-label">Has Membership</label>
                            <div class="col-md-2">
                                <select id="has_membership" name="has_membership" ng-model="service.has_membership" class="form-control">
                                    <option value="0" selected="selected">No</option>
                                    <option value="1">Yes</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group margin-bottom5">
                            <label class="col-md-2 control-label">Multiplier</label>
                            <div class="col-md-6">
                                <input type="text" id="multiplier" name="multiplier" ng-model="service.multiplier" class="form-control" value="<%multiplier%>">
                            </div>
                        </div>
                        <div class="form-group margin-bottom5">
                            <label class="col-md-2 control-label">Allow Anonymous</label>
                            <div class="col-md-2">
                                <select id="allow_anonymous" name="allow_anonymous" ng-model="service.allow_anonymous" class="form-control">
                                    <option value="0" selected="selected">No</option>
                                    <option value="1">Yes</option>
                                </select>
                            </div>
                        </div>


                        <div class="form-group margin-bottom5">
                            <label class="col-md-2 control-label">Description</label>
                            <div class="col-md-6">
                                <textarea name="description" id="description" ng-model="service.description" class="form-control" value="<%description%>"></textarea>
                            </div>
                        </div>
                        <div class="form-group margin-bottom5">
                            <label class="col-md-2 control-label">Enable Whitelist</label>
                            <div class="col-md-2">
                                <select id="enable_whitelist" name="enable_whitelist" class="form-control">
                                    <option value="0" selected="selected">No</option>
                                    <option value="1">Yes</option>

                                </select>
                            </div>
                        </div>
                            <div class="form-group margin-bottom5">
                                <label class="col-md-2 control-label">Whitelists</label>
                                <div class="col-md-6">
                                    <textarea class="form-control" id="whitelist" name="whitelist" ng-model="service.whitelist" ng-disabled="service.enable_whitelist == 0" ng-list="&#10;" ng-trim="false" rows="10"></textarea>
                                    <span class="text-muted">* One(1) IP address per line</span><br>
                            
                                    <!-- <span class="text-red" ng-show="service.hasError && (frmErrors.whitelist != null)">[[ frmErrors.whitelist[0] ]]</span> -->
                                    
                                </div>
                            </div>

                        <div class="form-group margin-bottom5">
                            <input type="submit" id="submit" class="btn btn-danger" value="save"/>
                        </div>
                    </form>
    <style type="text/css">
                        #assignUser .modal-body {
                            overflow-y: auto;
                            max-height: 320px;
                            min-height: 320px;
                            padding: 0px 50px;
                            position: relative;
                        }
                        .checkbox-lg input[type=checkbox] {
                            height:24px;
                            /*width:24px;*/
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

                    <!-- Tabs -->
                <div class="users-tabs"  ng-show="id != null">
                    <ul class="nav nav-tabs">
                        <li class="active"><a data-toggle="tab" href="#operator_service" aria-expanded="true">Operators</a></li>
                        <li><a data-toggle="tab" href="#canned_service" aria-expanded="false">Canned</a></li>
                        <li><a data-toggle="tab" href="#auto_reminder_service">Auto-reminder</a></li>
                        <li><a data-toggle="tab" href="#auto_responder_service">Auto-responder</a></li>
                        <li><a data-toggle="tab" href="#route_service">Route</a></li>
                        <li><a data-toggle="tab" href="#message_limit_service">Message Limit</a></li>
                        <li><a data-toggle="tab" href="#persona_limit_service">Persona Limit</a></li>
                        <!-- <li><a data-toggle="tab" href="#subscriber_Billing_service">Subscriber Billing</a></li> -->
                        <li><a data-toggle="tab" href="#auto_discard_service">Auto Discard</a></li>
                    </ul>
                    <div class="tab-content">
                     <div id="operator_service" class="tab-pane fade in active">
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <p class="text-left">
                                                <button class="btn btn-primary" data-toggle="modal" data-target="#assignUser" ng-click="getUnassignedUsers(id)">Assign User</button>
                                            </p>
                                            <div class="well">
                                                <h4 class="text-center">Assigned Users</h4>
                                                <input type="text" class="input form-control" ng-model="searchUserService" placeholder="Type to search user">
                                            </div>
                                            <div class="service-list-container col-xs-12">
                                                <ul class="list-unstyled padding10">
                                                    <li dir-paginate="users in userServices|filter:searchUserService|itemsPerPage:5" pagination-id="userservicelist">
                                                        <div class="checkbox">
                                                            <label class="checkbox checkbox-lg">
                                                                <input type="checkbox" name="assignedUsers" ng-model="users.selected" value="<%users.user_id%>" ng-change="selectedList()"> <%users.username%> 
                                                            </label>
                                                        </div>
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
                                            <button type="button" class="btn btn-primary pull-right" ng-show="userSelected.length > 0" data-toggle="modal" data-target="#deleteAssignedUsers">Delete Users</button>
                                        </div>
                                    </div>
                                </div>
                                        <div class="modal fade" id="assignUser" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                  <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                      <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <h4 class="modal-title" id="myModalLabel">Assign User</h4>
                                        <h4 class="text-center">Unassigned Users List</h4>
                                        <input type="text" class="input form-control" ng-model="searchUser.username" placeholder="Type to search user">
                                         <div class="form-group">
                                <label for="role">User Role:</label>
                                    <select class="form-control" id="role" name="role" ng-model="searchUser.role_id">
                                    <option value="" disabled selected>Role</option>
                                        <option value="">All</option>
                                        <option value="3">Admin</option>
                                        <option value="4">Manager</option>
                                        <option value="5">Operator-FT</option>
                                        <option value="6">Customer</option>
                                        <option value="7">Operator-FL</option>           
                                        <option value="8">Team Leader</option>
                                    </select>
                            </div>
                                      </div>
                                      <div class="modal-body">
                                        <ul class="list-unstyled padding10" ng-repeat="unuser in visibleItems = (unassignedUsers|filter:searchUser)">
                                            <li> 
                                                <label class="checkbox checkbox-lg">
                                                    <input type="checkbox" ng-model="unuser.selected" value="<%unuser.id%>" ng-change="selectedListUnassigned()"> <%unuser.username%>
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
                                               <div class="modal fade" tabindex="-1" role="dialog" id="deleteAssignedUsers">
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
                                        <button type="button" class="btn btn-primary" data-dismiss="modal" ng-click="deleteAssignedUsers(id)">Yes</button>
                                      </div>
                                    </div><!-- /.modal-content -->
                                  </div><!-- /.modal-dialog -->
                                </div><!-- /.modal -->
             
                        <div id="canned_service" class="tab-pane fade">
                            <div class="row">
                                <div class="col-lg-12">
                                    <a href="#" class="btn btn-default" data-toggle="modal" data-target="#addcannedmessage"  aria-hidden="true" title="Add Canned Message">Add Canned Message</a>
                                </div>
                                <div class="col-lg-12">
                                    <table class="table table-bordered table-condensed">
                                        <thead>
                                            <tr>
                                                <th>Label</th>
                                                <th>Message</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr ng-show="cannedMessages == 0">
                                                <td colspan="4">
                                                    No Canned Messages.
                                                </td>
                                            </tr>
                                            <tr dir-paginate="canned in cannedMessages|itemsPerPage:5" pagination-id="canned">
                                                <td><%canned.label%></td>
                                                <td><%canned.message%></td>
                                                <td>
                                                    <a href data-toggle="modal" data-target="#canned<%canned.id%>"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>
                                                    <div class="modal fade" id="canned<%canned.id%>" tabindex="-1" role="dialog">
                                                      <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                          <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                            <h4 class="modal-title">Delete Canned Message</h4>
                                                          </div>
                                                          <div class="modal-body">
                                                            <p>Are you sure you want to delete canned message?</p>
                                                          </div>
                                                          <div class="modal-footer">
                                                            <button type="button" ng-click="deleteCannedMessage(canned.id, id)" class="btn btn-primary" data-dismiss="modal">Yes</button>
                                                            <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                                                          </div>
                                                        </div><!-- /.modal-content -->
                                                      </div><!-- /.modal-dialog -->
                                                    </div><!-- /.modal -->
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="4">
                                                    <dir-pagination-controls
                                                    pagination-id="canned"
                                                    max-size="3"
                                                    direction-links="true"
                                                    boundary-links="true" >
                                                    </dir-pagination-controls>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            {{-- new canned msg modal --}}
                            <div class="modal fade canned" id="addcannedmessage" tabindex="-1" role="dialog" aria-labelledby="addcanned">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                            <h4 class="modal-title" id="addcanned">Add Canned Message</h4>
                                        </div>
                                            
                                        <form name="frmTabsCanned" class="form-horizontal" novalidate ng-submit="saveCannedMessage(id)">
                                            <div class="modal-body no-padding">
                                                <div class="col-lg-12">
                                                   <input type="text" id="" ng-model="canned.label" name="label" class="form-control" placeholder="Label" >
                                                   <textarea id="" ng-model="canned.message" name="message" class="form-control" placeholder="Message"></textarea> 
                                                </div>
                                                <div class="clearfix"></div>
                                                <div class="modal-footer custom-modal-footer">
                                                    <button type="button" class="btn btn-default close-modal" data-dismiss="modal">Close</button>
                                                    <input type="submit" class="btn btn-success" value="Save">
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div> 
                        </div>
                        <div id="auto_reminder_service" class="tab-pane fade">
                            <div class="row">
                                <div class="col-lg-12">
                                    <a href="#" class="btn btn-default" data-toggle="modal" data-target="#addreminder"  aria-hidden="true" title="Add Reminder">Add Reminder</a>
                                </div>
                                <div class="col-lg-12">
                                    <table class="table table-bordered table-condensed">
                                        <thead>
                                            <tr>
                                                <th>Condition (seconds)</th>
                                                <th>Library</th>
                                                <th>Schedule</th>
                                                <th>Timezone</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr ng-show="autoReminder == 0">
                                                <td colspan="4">
                                                    No Reminders.
                                                </td>
                                            </tr>
                                            <tr dir-paginate="reminder in autoReminder|itemsPerPage:5" pagination-id="reminder">
                                                <td><%reminder.idle_time%></td>
                                                <td><%reminder.library_id%></td>
                                                <td><%reminder.schedule%></td>
                                                <td><%reminder.timezone%></td>
                                                <td>
                                                    <a href data-toggle="modal" data-target="#reminder<%reminder.id%>"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>
                                                    <div class="modal fade" id="reminder<%reminder.id%>" tabindex="-1" role="dialog">
                                                      <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                          <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                            <h4 class="modal-title">Delete Auto Reminder</h4>
                                                          </div>
                                                          <div class="modal-body">
                                                            <p>Are you sure you want to delete Auto Reminder?</p>
                                                          </div>
                                                          <div class="modal-footer">
                                                            <button type="button" ng-click="deleteAutoReminder(reminder.id, id)" class="btn btn-primary" data-dismiss="modal">Yes</button>
                                                            <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                                                          </div>
                                                        </div><!-- /.modal-content -->
                                                      </div><!-- /.modal-dialog -->
                                                    </div><!-- /.modal -->
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="4">
                                                    <dir-pagination-controls
                                                    pagination-id="reminder"
                                                    max-size="3"
                                                    direction-links="true"
                                                    boundary-links="true" >
                                                    </dir-pagination-controls>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="modal fade reminder" id="addreminder" tabindex="-1" role="dialog" aria-labelledby="addreminder">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                <h4 class="modal-title" id="addreminder">Add Reminder</h4>
                                            </div>
                                            <form name="frmTabsReminder" class="form-horizontal" novalidate ng-submit="saveAutoReminder(id)">
                                                <div class="modal-body no-padding">
                                                    <div class="col-lg-12">
                                                        <div class="form-group">
                                                            <label class="col-sm-3 control-label" for="reminderLibrary">Library</label>
                                                            <div class="col-sm-9">
                                                                <select id="reminderLibrary" ng-model="reminder.library" name="library" class="form-control">
                                                                    <option ng-repeat="remlib in libraries" value="<%remlib.id%>"><%remlib.name%></option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="col-sm-3 control-label" for="reminderIdle">Idle Time</label>
                                                            <div class="col-sm-9">
                                                                <input type="text" id="reminderIdle" ng-model="reminder.idle" name="idle" class="form-control" placeholder="(seconds)">
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="col-sm-3 control-label" for="reminderSchedule">Schedule</label>
                                                            <div class="col-sm-9">
                                                                <input type="date"  id="reminderSchedule" ng-model="reminder.schedule" name="schedule" class="form-control" placeholder="Schedule">
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="col-sm-3 control-label" for="reminderTimezone">Time-zone</label>
                                                            <div class="col-sm-9">
                                                                <select id="reminderTimezone" ng-model="reminder.timezone" name="timezone" class="form-control">
                                                                    <option value="-12.0">(GMT -12:00) Eniwetok, Kwajalein</option>
                                                                    <option value="-11.0">(GMT -11:00) Midway Island, Samoa</option>
                                                                    <option value="-10.0">(GMT -10:00) Hawaii</option>
                                                                    <option value="-9.0">(GMT -9:00) Alaska</option>
                                                                    <option value="-8.0">(GMT -8:00) Pacific Time (US &amp; Canada)</option>
                                                                    <option value="-7.0">(GMT -7:00) Mountain Time (US &amp; Canada)</option>
                                                                    <option value="-6.0">(GMT -6:00) Central Time (US &amp; Canada), Mexico City</option>
                                                                    <option value="-5.0">(GMT -5:00) Eastern Time (US &amp; Canada), Bogota, Lima</option>
                                                                    <option value="-4.0">(GMT -4:00) Atlantic Time (Canada), Caracas, La Paz</option>
                                                                    <option value="-3.0">(GMT -3:00) Brazil, Buenos Aires, Georgetown</option>
                                                                    <option value="-2.0">(GMT -2:00) Mid-Atlantic</option>
                                                                    <option value="-1.0">(GMT -1:00 hour) Azores, Cape Verde Islands</option>
                                                                    <option value="0.0">(GMT) Western Europe Time, London, Lisbon, Casablanca</option>
                                                                    <option value="1.0">(GMT +1:00 hour) Brussels, Copenhagen, Madrid, Paris</option>
                                                                    <option value="2.0">(GMT +2:00) Kaliningrad, South Africa</option>
                                                                    <option value="3.0">(GMT +3:00) Baghdad, Riyadh, Moscow, St. Petersburg</option>
                                                                    <option value="4.0">(GMT +4:00) Abu Dhabi, Muscat, Baku, Tbilisi</option>
                                                                    <option value="5.0">(GMT +5:00) Ekaterinburg, Islamabad, Karachi, Tashkent</option>
                                                                    <option value="6.0">(GMT +6:00) Almaty, Dhaka, Colombo</option>
                                                                    <option value="7.0">(GMT +7:00) Bangkok, Hanoi, Jakarta</option>
                                                                    <option value="8.0">(GMT +8:00) Beijing, Perth, Singapore, Hong Kong</option>
                                                                    <option value="9.0">(GMT +9:00) Tokyo, Seoul, Osaka, Sapporo, Yakutsk</option>
                                                                    <option value="10.0">(GMT +10:00) Eastern Australia, Guam, Vladivostok</option>
                                                                    <option value="11.0" selected="selected">(GMT +11:00) Magadan, Solomon Islands, New Caledonia</option>
                                                                    <option value="12.0">(GMT +12:00) Auckland, Wellington, Fiji, Kamchatka</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="clearfix"></div>
                                                    <div class="modal-footer custom-modal-footer">
                                                        <button type="button" class="btn btn-default close-modal" data-dismiss="modal">Close</button>
                                                        <input type="submit" class="btn btn-success" value="Save">
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div> 
                            </div>
                        </div>
                        <div id="auto_responder_service" class="tab-pane fade">
                            <div class="row">
                                <div class="col-lg-12">
                                    <a href="#" class="btn btn-default" data-toggle="modal" data-target="#addrule"  aria-hidden="true" title="Add Rule">Add Rule</a>
                                </div>
                                <div class="col-lg-12">
                                    <table class="table table-bordered table-condensed">
                                        <thead>
                                            <tr>
                                                <th>Condition</th>
                                                <th>Keyword</th>
                                                <th>Persona</th>
                                                <th>Delay (seconds)</th>
                                                <th>Library Name</th>
                                                <th>Type</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr ng-show="responderRules == 0">
                                                <td colspan="4">
                                                    No Rule.
                                                </td>
                                            </tr>
                                            <tr dir-paginate="responrules in responderRules|itemsPerPage:4" pagination-id="rules">
                                                <td><%responrules.message_condition%></td>
                                                <td><%responrules.keyword%></td>
                                                <td><%responrules.persona%></td>
                                                <td><%responrules.delay%></td>
                                                <td><%responrules.library%></td>
                                                <td></td>
                                                <td>
                                                    <a href data-toggle="modal" data-target="#rule<%responrules.id%>"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>
                                                    <div class="modal fade" id="rule<%responrules.id%>" tabindex="-1" role="dialog">
                                                      <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                          <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                            <h4 class="modal-title">Delete Rule</h4>
                                                          </div>
                                                          <div class="modal-body">
                                                            <p>Are you sure you want to delete Rule?</p>
                                                          </div>
                                                          <div class="modal-footer">
                                                            <button type="button" ng-click="deleteRule(responrules.id, id)" class="btn btn-primary" data-dismiss="modal">Yes</button>
                                                            <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                                                          </div>
                                                        </div><!-- /.modal-content -->
                                                      </div><!-- /.modal-dialog -->
                                                    </div><!-- /.modal -->
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="4">
                                                    <dir-pagination-controls
                                                    pagination-id="rules"
                                                    max-size="3"
                                                    direction-links="true"
                                                    boundary-links="true" >
                                                    </dir-pagination-controls>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="modal fade rules" id="addrule" tabindex="-1" role="dialog" aria-labelledby="addrule">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                <h4 class="modal-title" id="addrule">Add Rule</h4>
                                            </div>
                                            <form name="frmTabsResponder" class="form-horizontal" novalidate ng-submit="saveRule(id)">
                                                <div class="modal-body no-padding">
                                                    <div class="col-lg-12">
                                                        <div class="form-group">
                                                            <label class="col-sm-3 control-label" for="responserCondition">Condition</label>
                                                            <div class="col-sm-9">
                                                                <select id="responserCondition" ng-model="rules.condition" name="condition" class="form-control">
                                                                    <option value="first message">first message</option>
                                                                    <option value="first message contains keyword">first message contains keyword</option>
                                                                    <option value="first message equals keyword">first message equals keyword</option>
                                                                    <option value="message contains">message contains</option>
                                                                    <option value="message equals keyword">message equals keyword</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="col-sm-3 control-label" for="responderKeyword">Keyword</label>
                                                            <div class="col-sm-9">
                                                                <input type="text" id="responderKeyword" ng-model="rules.keyword" ng-disabled="(rules.condition == 'first message')" name="keyword" class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="col-sm-3 control-label" for="responserPersona">Persona</label>
                                                            <div class="col-sm-9">
                                                                <select id="responserPersona" ng-model="rules.persona" name="persona" class="form-control">
                                                                    <option ng-repeat="persona in personas" value="<%persona.id%>"><%persona.name%></option>  
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="col-sm-3 control-label" for="responderDelay">Delay</label>
                                                            <div class="col-sm-9">
                                                                <input type="input" id="responderDelay" ng-model="rules.delay" name="delay" class="form-control" placeholder="(seconds)">
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="col-sm-3 control-label" for="responderLibraries">Libraries</label>
                                                            <div class="col-sm-9">
                                                                <select id="responderLibraries" ng-model="rules.library" name="libraries" class="form-control">
                                                                    <option ng-repeat="library in libraries" value="<%library.id%>"><%library.name%></option>  
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="clearfix"></div>
                                                    <div class="modal-footer custom-modal-footer">
                                                        <button type="button" class="btn btn-default close-modal" data-dismiss="modal">Close</button>
                                                        <input type="submit" class="btn btn-success" value="Save">
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div> 
                            </div>
                        </div>
                        <div id="route_service" class="tab-pane fade">
                            <div class="row">
                                <div class="col-lg-12">
                                    <form name="frmTabsRoute" class="form-horizontal" novalidate ng-submit="saveRoutes(id)">
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label" for="routeService">Sorting</label>
                                            <div class="col-sm-5">
                                                <select id="routeService" ng-model="routes.sorting" name="sorting" class="form-control">
                                                    <option value="longestwaiting" ng-selected="serviceRoutes.route=='longestwaiting'">Longest Waiting Message</option>
                                                    <option value="shortestwaiting" ng-selected="serviceRoutes.route=='shortestwaiting'">Shortest Waitng Message</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label" for="mappingService">Mapping</label>
                                            <div class="col-sm-5">
                                                <select id="mappingService" ng-model="routes.mapping" name="mapping" class="form-control">
                                                    <option value="default" ng-selected="serviceRoutes.mapping=='default'">Available Operator (Default)</option>
                                                    <option value="lastoperator" ng-selected="serviceRoutes.mapping=='lastoperator'">Last Operator Route</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-12">
                                                <input type="submit" class="btn btn-success pull-right" value="Save">
                                            </div>
                                        </div>
                                    </form>
                                </div>  
                            </div>
                        </div>
                        <div id="message_limit_service" class="tab-pane fade">
                            <div class="row">
                                <div class="col-lg-12">
                                    <a href="#" class="btn btn-default" data-toggle="modal" data-target="#addmessagelimit"  aria-hidden="true" title="Add Message Limit">Add Message Limit</a>
                                </div>
                                <div class="col-lg-12">
                                    <table class="table table-bordered table-condensed">
                                        <thead>
                                            <tr>
                                                <th>Limit</th>
                                                <th>Action</th>
                                                <th>Reset Period</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr ng-show="messageLimit == 0">
                                                <td colspan="4">
                                                    No message limit
                                                </td>
                                            </tr>
                                            <tr dir-paginate="msgLimit in messageLimit|itemsPerPage:4" pagination-id="messageLimit">
                                                <td><%msgLimit.limit_count%></td>
                                                <td><%msgLimit.limit_action%></td>
                                                <td><%msgLimit.reset_period%></td>
                                                <td>
                                                    <a href data-toggle="modal" data-target="#messageLimit<%msgLimit.id%>"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>
                                                    <div class="modal fade" id="messageLimit<%msgLimit.id%>" tabindex="-1" role="dialog">
                                                      <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                          <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                            <h4 class="modal-title">Delete Message Limit</h4>
                                                          </div>
                                                          <div class="modal-body">
                                                            <p>Are you sure you want to delete Message Limit?</p>
                                                          </div>
                                                          <div class="modal-footer">
                                                            <button type="button" ng-click="deleteMessageLimit(msgLimit.id, id)" class="btn btn-primary" data-dismiss="modal">Yes</button>
                                                            <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                                                          </div>
                                                        </div><!-- /.modal-content -->
                                                      </div><!-- /.modal-dialog -->
                                                    </div><!-- /.modal -->
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="4">
                                                    <dir-pagination-controls
                                                    pagination-id="messageLimit"
                                                    max-size="3"
                                                    direction-links="true"
                                                    boundary-links="true" >
                                                    </dir-pagination-controls>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="modal fade rules" id="addmessagelimit" tabindex="-1" role="dialog" aria-labelledby="addmessagelimit">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                <h4 class="modal-title" id="addrule">Add Message Limit</h4>
                                            </div>
                                            <form name="frmTabsMessageLimit" class="form-horizontal" novalidate ng-submit="saveMessageLimit(id)">
                                                <div class="modal-body no-padding">
                                                    <div class="col-lg-12">
                                                        <div class="form-group">
                                                            <label class="col-sm-3 control-label" for="limit">Message Limit</label>
                                                            <div class="col-sm-9">
                                                                <input type="text" id="limit" ng-model="messagelimit.limit" name="limit" class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="col-sm-3 control-label" for="action">Action</label>
                                                            <div class="col-sm-9">
                                                                <select id="action" ng-model="messagelimit.action" name="action" class="form-control">
                                                                    <option value="discard" selected>Discard</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="col-sm-3 control-label" for="resetperiod">Reset Period</label>
                                                            <div class="col-sm-9">
                                                                <select id="resetperiod" ng-model="messagelimit.reset" name="reset" class="form-control">
                                                                    <option value="1 day">1 day</option>
                                                                    <option value="1 month">1 month</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="clearfix"></div>
                                                    <div class="modal-footer custom-modal-footer">
                                                        <button type="button" class="btn btn-default close-modal" data-dismiss="modal">Close</button>
                                                        <input type="submit" class="btn btn-success" value="Save">
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div> 
                            </div>
                        </div>
                        <div id="persona_limit_service" class="tab-pane fade">
                            <div class="row">
                                <div class="col-lg-12">
                                    <a href="#" class="btn btn-default" data-toggle="modal" data-target="#addpersonalimit"  aria-hidden="true" title="Add Persona Limit">Add Persona Limit</a>
                                </div>
                                <div class="col-lg-12">
                                    <table class="table table-bordered table-condensed">
                                        <thead>
                                            <tr>
                                                <th>Limit</th>
                                                <th>Action</th>
                                                <th>Reset Period</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr ng-show="personaLimit == 0">
                                                <td colspan="4">
                                                    No Rule.
                                                </td>
                                            </tr>
                                            <tr dir-paginate="pernLimit in personaLimit|itemsPerPage:4" pagination-id="personaLimit">
                                                <td><%pernLimit.limit_count%></td>
                                                <td><%pernLimit.limit_action%></td>
                                                <td><%pernLimit.reset_period%></td>
                                                <td>
                                                    <a href data-toggle="modal" data-target="#personaLimit<%pernLimit.id%>"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>
                                                    <div class="modal fade" id="personaLimit<%pernLimit.id%>" tabindex="-1" role="dialog">
                                                      <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                          <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                            <h4 class="modal-title">Delete Persona Limit</h4>
                                                          </div>
                                                          <div class="modal-body">
                                                            <p>Are you sure you want to delete Persona Limit?</p>
                                                          </div>
                                                          <div class="modal-footer">
                                                            <button type="button" ng-click="deletePersonaLimit(pernLimit.id, id)" class="btn btn-primary" data-dismiss="modal">Yes</button>
                                                            <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                                                          </div>
                                                        </div><!-- /.modal-content -->
                                                      </div><!-- /.modal-dialog -->
                                                    </div><!-- /.modal -->
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="4">
                                                    <dir-pagination-controls
                                                    pagination-id="personaLimit"
                                                    max-size="3"
                                                    direction-links="true"
                                                    boundary-links="true" >
                                                    </dir-pagination-controls>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="modal fade rules" id="addpersonalimit" tabindex="-1" role="dialog" aria-labelledby="addmessagelimit">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                <h4 class="modal-title" id="addrule">Add Persona Limit</h4>
                                            </div>
                                            <form name="frmTabsPersonaLimit" class="form-horizontal" novalidate ng-submit="savePersonaLimit(id, 'persona')">
                                                <div class="modal-body no-padding">
                                                    <div class="col-lg-12">
                                                        <div class="form-group">
                                                            <label class="col-sm-3 control-label" for="limit">Persona Limit</label>
                                                            <div class="col-sm-9">
                                                                <input type="text" id="limit" ng-model="personalimit.limit" name="limit" class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="col-sm-3 control-label" for="action">Action</label>
                                                            <div class="col-sm-9">
                                                                <select id="action" ng-model="personalimit.action" name="action" class="form-control">
                                                                    <option value="discard" selected>Discard</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="col-sm-3 control-label" for="resetperiod">Reset Period</label>
                                                            <div class="col-sm-9">
                                                                <select id="resetperiod" ng-model="personalimit.reset" name="reset" class="form-control">
                                                                    <option value="1 day">1 day</option>
                                                                    <option value="1 month">1 month</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="clearfix"></div>
                                                    <div class="modal-footer custom-modal-footer">
                                                        <button type="button" class="btn btn-default close-modal" data-dismiss="modal">Close</button>
                                                        <input type="submit" class="btn btn-success" value="Save">
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div> 
                            </div>

                        </div>
                        <div id="auto_discard_service" class="tab-pane fade">
                            <div class="row">
                                <div class="col-lg-12">
                                    <a href="#" class="btn btn-default" data-toggle="modal" data-target="#addpersonalimit"  aria-hidden="true" title="Add Persona Limit">Add Persona Limit</a>
                                </div>
                                <div class="col-lg-12">
                                    <table class="table table-bordered table-condensed">
                                        <thead>
                                            <tr>
                                                <th>Start</th>
                                                <th>End</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr ng-show="personaLimit == 0">
                                                <td colspan="4">
                                                    No Rule.
                                                </td>
                                            </tr>
                                            <tr dir-paginate="pernLimit in personaLimit|itemsPerPage:4" pagination-id="personaLimit">
                                                <td><%pernLimit.limit_count%></td>
                                                <td><%pernLimit.limit_action%></td>
                                                <td><%pernLimit.reset_period%></td>
                                                <td>
                                                    <a href data-toggle="modal" data-target="#personaLimit<%pernLimit.id%>"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>
                                                    <div class="modal fade" id="personaLimit<%pernLimit.id%>" tabindex="-1" role="dialog">
                                                      <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                          <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                            <h4 class="modal-title">Delete Persona Limit</h4>
                                                          </div>
                                                          <div class="modal-body">
                                                            <p>Are you sure you want to delete Persona Limit?</p>
                                                          </div>
                                                          <div class="modal-footer">
                                                            <button type="button" ng-click="deletePersonaLimit(pernLimit.id, id)" class="btn btn-primary" data-dismiss="modal">Yes</button>
                                                            <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                                                          </div>
                                                        </div><!-- /.modal-content -->
                                                      </div><!-- /.modal-dialog -->
                                                    </div><!-- /.modal -->
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="4">
                                                    <dir-pagination-controls
                                                    pagination-id="personaLimit"
                                                    max-size="3"
                                                    direction-links="true"
                                                    boundary-links="true" >
                                                    </dir-pagination-controls>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="modal fade rules" id="addpersonalimit" tabindex="-1" role="dialog" aria-labelledby="addmessagelimit">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                <h4 class="modal-title" id="addrule">Add Persona Limit</h4>
                                            </div>
                                            <form name="frmTabsPersonaLimit" class="form-horizontal" novalidate ng-submit="savePersonaLimit(id, 'persona')">
                                                <div class="modal-body no-padding">
                                                    <div class="col-lg-12">
                                                        <div class="form-group">
                                                            <label class="col-sm-3 control-label" for="limit">Persona Limit</label>
                                                            <div class="col-sm-9">
                                                                <input type="text" id="limit" ng-model="personalimit.limit" name="limit" class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="col-sm-3 control-label" for="action">Action</label>
                                                            <div class="col-sm-9">
                                                                <select id="action" ng-model="personalimit.action" name="action" class="form-control">
                                                                    <option value="discard" selected>Discard</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="col-sm-3 control-label" for="resetperiod">Reset Period</label>
                                                            <div class="col-sm-9">
                                                                <select id="resetperiod" ng-model="personalimit.reset" name="reset" class="form-control">
                                                                    <option value="1 day">1 day</option>
                                                                    <option value="1 month">1 month</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="clearfix"></div>
                                                    <div class="modal-footer custom-modal-footer">
                                                        <button type="button" class="btn btn-default close-modal" data-dismiss="modal">Close</button>
                                                        <input type="submit" class="btn btn-success" value="Save">
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div> 
                            </div>
                    </div>
               
                    <!--Script-->
                    <script src="http://ajax.googleapis.com/ajax/libs/angularjs/1.3.14/angular.min.js"></script>
                    <script src="{{ URL::asset('js/jquery-2.1.1.min.js') }}"></script>
                    <script src="{{ URL::asset('js/bootstrap.min.js') }}"></script>
                    <script src="{{ URL::asset('js/toastr.js') }}"></script>
                    <link href="//netdna.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
              
                    <script src="{{ URL::asset('js/color-picker.min.js') }}"></script>
                    <link rel="stylesheet" type="text/css" href="{{ URL::asset('css/color-picker.min.css') }}" />
                    <script src="{{ URL::asset('js/angular-toastr.tpls.js')}}"></script>
                    <link rel="stylesheet" href="{{ URL::asset('css/angular-toastr.css')}}" />
                    <!-- AngularJS Application Scripts -->
                    <script src="{{ URL::asset('js/angular/angular.min.js')}}"></script>
                    <script src="{{ URL::asset('bower_components/angular-sanitize/angular-sanitize.min.js')}}"></script>
                    <script src="{{ URL::asset('bower_components/angular-ui-select/dist/select.js')}}"></script>
                    <script src="{{ URL::asset('js/angular/app/app.js') }}"></script>
                    <script src="{{ URL::asset('js/angular/directives/dirPagination.js') }}"></script>
                    <script src="{{ URL::asset('js/angular/factory/transformRequestAsFormPost.js') }}"></script>

                    <script src="{{ URL::asset('js/angular/controllers/services.js') }}"></script>
                    <script src="{{ URL::asset('js/angular/app/services.js') }}"></script>
                    
                    </html>
@stop