@extends('layouts.ProtectedPagesTemplateContent')

@section('css')
<link rel = "stylesheet" href = "{{url('/')}}/css/pre_loader.css"/>
<link rel = "stylesheet" href = "{{url('/')}}/css/chat_new.css"/>
<style type="text/css">
    .container-custom{height: calc(100% - 40px);}
</style>
@stop

@section('content')
<script type="text/javascript">
    var csrf_token = "{{csrf_token()}}"
</script>

<div class="full-container" ng-controller='ChatController'>
    <div class="row">
        <!--Chat Sidebar-->
        <div id="imChatSidebar" class="col-1" ng-if="cnvCount > 0">
            <div class="imClientList">
                <!-- <input type="text" ng-model='operator_id' /><button ng-click='initChat()'>OK</button> -->
                <ul class="imChatsibar1">
                    <!-- <li ng-repeat="(id,conversation) in conversations | toArray:true | orderBy:['-status', '-waiting_time'] | limitTo:4" ng-click="activateChatBox(conversation.conversation_id, id)" class="<%(conversation.conversation_id === currentChatID) ? 'activeSubscriber' : ''%>" > -->
                    <!-- <li ng-repeat="(id,conversation) in filteredConversations | limitTo:4" ng-click="activateChatBox(conversation.conversation_id, id)" class="<%(conversation.conversation_id === currentChatID) ? 'activeSubscriber' : ''%>" ng-style="$first ? { 'background-color': '#<%services[conversation.service_id].color_theme%>' } : {}"> -->
                    <li id="sbar-<%conversation.id%>" ng-repeat="(id,conversation) in filteredConversations | limitTo:4"
                    class="<%(conversation.conversation_id === currentChatID) ? 'activeSubscriber' : ''%>" 
                    ng-style="$first ? { 'background-color': '#<%services[conversation.service_id].color_theme%>' } : {}"
                    ng-class="conversation.sbarClass">
                        <span ng-if="conversation.conversation_id === currentChatID" class="fa fa-caret-left" ng-style="$first ? { 'color': '#<%services[conversation.service_id].color_theme%>' } : {}"></span>
                        <a>
                            <div class="imClient">
                                <div class="imClientAvatar conversation-summary-avatar conversation-summary-avatar-group" data-number-of-thumbnails="2">
                                  <div class="conversation-summary-avatar-group-member-wrapper">
                                    <!-- <img ng-src="<%conversation.subscriber.avatar%>" alt="N/A" data-user-initial="S" /> -->
                                    <!-- sidebarxxx -->
                                    <ng-letter-avatar dynamic="true" data="<%conversation.subscriber_name%>" charCount="1" fontWeight="300" avatarcustombgcolor="#<% services[conversation.service_id].color_theme %>"></ng-letter-avatar>
                                    </div>
                                    <div class="conversation-summary-avatar-group-member-wrapper">
                                        <img class="conversation-summary-avatar-group-member avatar--should-fall-back-to-initial" ng-src="<%base_url%>/api/image/thumb?type=thumb&width=33&height=33&url=<%conversation.persona.avatar%>" alt="N/A" data-user-initial="P" />
                                    </div>
                                </div>
                                <div class="imClientStatus">
                                    <span class="status <%conversation.hidden ? 'online minimized' : ''%>"></span>
                                    <span class="status <%conversation.status==='pending' ? 'online' : ''%>"></span>
                                </div>
                                <div class="imClientName">S:<%conversation.subscriber_name%></div>
                                <div class="imClientName">P:<%conversation.persona_name%></div>
                                <!-- <div class="imClientName" ng-hide>Start: <span am-time-ago="conversation.assigned_start | amAdd : '8' : 'hours' | amParse:'YYYY.MM.DD HH:mm:ss'"></span></div> -->
                                <!-- <div class="imClientName" ng-if="conversation.status === 'pending' && conversation.messages[conversation.last_message_id].type == 'inbound'"><%conversation.waiting_time = (dateNow | amDifference : conversation.assigned_latest : 'full' | amDateFormat : 'mm:ss' )%></div> -->
                                <div class="sidebar-countdown-holder">
                                    <div class="imClientName" ng-model='conversation.cntdown' ng-if="conversation.status === 'pending' && conversation.messages[conversation.last_message_id].type == 'inbound'"><%conversation.cntdown%></div>
                                </div>
                                <div class="imClientName" ng-if="conversation.status === 'handled'">handled: <%conversation.idle_time = (dateNow | amDifference : conversation.last_message_date : 'full' | amDateFormat : 'mm:ss' )%></div>
                                <div class="imClientService"><% services[conversation.service_id].name %></div>
                                <div class="imClientService"><%conversation.conversation_id%></div>
                            </div>
                        </a>
                    </li>
                </ul>
            <ul class="imChatsibar2">
                <li id="sbar-<%conversation.id%>" ng-repeat="(id,conversation) in filteredConversations | limitTo:4:4">
                    <a>
                        <div class="imClient">
                            <div class="imClientAvatar conversation-summary-avatar conversation-summary-avatar-group" data-number-of-thumbnails="2">
                                <div class="conversation-summary-avatar-group-member-wrapper">
                                    <!-- <img ng-src="<%conversation.subscriber.avatar%>" alt="N/A" data-user-initial="S" /> -->
                                    <ng-letter-avatar data="<%conversations.subscriber_name%>" charCount="1" fontWeight="300" avatarcustombgcolor="#<% services[conversation.service_id].color_theme %>"></ng-letter-avatar>
                                </div>
                                <div class="conversation-summary-avatar-group-member-wrapper">
                                    <img class="conversation-summary-avatar-group-member avatar--should-fall-back-to-initial" ng-src="<%base_url%>/api/image/thumb?type=thumb&width=33&height=33&url=<%conversation.persona.avatar%>" alt="N/A" data-user-initial="P" />
                                </div>
                            </div>
                            <div class="imClientStatus">
                                <span class="status <%conversation.hidden ? 'online minimized' : ''%>"></span>
                                <span class="status <%conversation.status==='pending' ? 'online' : ''%>"></span>
                            </div>
                            <div class="imClientName">S:<%conversation.subscriber_name%></div>
                            <div class="imClientName">P:<%conversation.persona_name%></div>
                            <!-- <div class="imClientName" ng-hide>Start: <span am-time-ago="conversation.assigned_start | amAdd : '8' : 'hours' | amParse:'YYYY.MM.DD HH:mm:ss'"></span></div> -->
                            <!-- <div class="imClientName" ng-if="conversation.status === 'pending' && conversation.messages[conversation.last_message_id].type == 'inbound'"><%conversation.waiting_time = (dateNow | amDifference : conversation.assigned_latest : 'full' | amDateFormat : 'mm:ss' )%></div> -->
                            <div class="sidebar-countdown-holder">
                                <div class="imClientName" ng-if="conversation.status === 'pending' && conversation.messages[conversation.last_message_id].type == 'inbound'" ng-model="conversations[id].cntdown"><%conversation.cntdown%></div>
                            </div>
                            <div class="imClientName" ng-if="conversation.status === 'handled'">handled: <%conversation.idle_time = (dateNow | amDifference : conversation.last_message_date : 'full' | amDateFormat : 'mm:ss' )%></div>
                            <div class="imClientService"><% services[conversation.service_id].name %></div>
                            <div class="imClientService"><%conversation.conversation_id%></div>
                        </div>
                    </a>
                </li>
            </ul>
        </div>
    </div>

<!--Global Container-->
<!-- <div class='waiting' ng-if="!conversations[currentChatID].isActive"> -->
<div class='waiting' ng-if="!cnvCount > 0">
    <md-progress-circular style="position: relative; margin: auto;" md-mode="indeterminate" md-diameter="30"></md-progress-circular>
        <!-- <ul class="spinner">
            <li></li>
            <li></li>
            <li></li>
            <li></li>
        </ul> -->
    </div>
    <!-- <div id="imGlobalContainer" class="col-5" ng-if="conversations[currentChatID].isActive"> -->
    <div id="imGlobalContainer" class="col-5" ng-if="cnvCount > 0">
        <div class="imProfileRow">
            <div class="col-3 imPersonaProfileContainer" >
                <div class="col-3-5 imProfileContainer">
                    <div class="imPunterProfile">
                        <div class="imProfileAvatar">
                            <ng-letter-avatar ng-if="conversations[currentChatID].subscriber.avatar == null" dynamic="true" data="<%conversations[currentChatID].subscriber_name%>" charCount="1" fontWeight="300" avatarcustombgcolor="#<% services[conversations[currentChatID].service_id].color_theme %>"></ng-letter-avatar>
                            <img ng-if="conversations[currentChatID].subscriber.avatar != null" ng-src="<%base_url%>/api/image/thumb?type=thumb&width=88&height=88&url=<%conversations[currentChatID].subscriber.avatar%>" alt="N/A" />
                            <div class="img-meta">
                                <div class="imProfileNickname"><%conversations[currentChatID].subscriber_name%></div>
                                <div class="imProfileCode"><%conversations[currentChatID].subscriber.code%></div>
                                <span>Subscriber</span>
                            </div>
                        </div>
                        <div class="imProfileContent">
                            <div class="imTimer imServiceColor-uk" style="background-color:#<%services[conversations[currentChatID].service_id].color_theme%>;color:#<%services[conversations[currentChatID].service_id].color_theme%>;"><% conversations[currentChatID].cntdown %></div>
                            <!-- <div class="imTimer imServiceColor-uk"><% conversations[currentChatID].cntdown %></div> -->
                                <!-- <div class="imProfileInformation">

                            </div> -->
                        </div>
                        <div class="subscriber-meta">
                            <section>
                                <div><span>Height</span> : <br><%conversations[currentChatID].subscriber.height%></div>
                                <div><span>Weight</span> : <br><%conversations[currentChatID].subscriber.weight%></div>
                            </section>
                            <section>
                                <div><span>Age</span> : <br><%conversations[currentChatID].subscriber.age%></div>
                                <div><span>Location</span> : <br><%conversations[currentChatID].subscriber.location%></div>
                            </section>
                        </div>
                    </div>
                    <div class="imProfileAddional">
                        <h3>Additional Information</h3>
                        <ul>
                            <li ng-repeat="(key, value) in conversations[currentChatID].subscriber" ng-if="key != 'height' && key != 'weight' && key != 'age' && key != 'location'">
                                <%key%> : <span ng-bind-html="value | linky:'_blank'"><%value%></span>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="col-2-5 imPersonaNoteContainer" >
                    <h3>Subscriber Notes:</h3>
                    <div class="imPersonaNote">
                        <ul>
                            <li ng-repeat="note in conversations[currentChatID].conversation_notes" ng-if="note.type == 'subscriber'">
                                <div class="row">
                                    <div class="col-lg-2 normalize-margin normalize-padding micro-icon-holder">
                                        <span title="Delete punter note" style="color:#edf1f5; cursor:pointer" ng-click="deleteConversationNote($event, note, 'subscriber')" class="glyphicon glyphicon-remove-sign" aria-hidden="true"></span>
                                        <span style="color:#2196F3; cursor:pointer" title="Transfer note to persona" class="glyphicon glyphicon-menu-right" aria-hidden="true" ng-click="transferNote($event, note, 'persona')"></span>
                                    </div>
                                    <div class="col-lg-10 note-content normalize-margin normalize-padding">
                                        <%note.comment%>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="imAddNotes" >
                        <input ng-model="snote" ng-keypress="addConversationNote($event, snote, 'subscriber', currentChatID)" class="textarea" type="text" placeholder="Type here!" id="subscriberNote-<%currentChatID%>">
                    </div>
                </div>

            </div>
            <div class="col-3 imPunterProfileContainer" >
                <div class="col-3-5 imProfileContainer">
                    <div class="imPersonaProfile">
                        <div class="imProfileAvatar">
                            <img ng-if="conversations[currentChatID].persona.avatar != null" ng-src="<%base_url%>/api/image/thumb?type=thumb&width=88&height=88&url=<%conversations[currentChatID].persona.avatar%>" alt="N/A" />
                            <!-- <img ng-if="conversations[currentChatID].persona.avatar == null" ng-src="{{url('/')}}/img/no-img.png" alt="N/A" /> -->
                            <ng-letter-avatar dynamic="true" ng-if="conversations[currentChatID].persona.avatar == null" data="<%current_persona_name%>" charCount="1" fontWeight="300" avatarcustombgcolor="#<% services[conversation.service_id].color_theme %>"></ng-letter-avatar>

                            <div class="img-meta">
                                <div class="imProfileNickname"><%conversations[currentChatID].persona_name%></div>
                                <div class="imProfileCode"><%conversations[currentChatID].persona.code%></div>
                                <span>Persona</span>
                            </div>
                        </div>
                        <div class="imProfileContent">
                            <div class="imShortCode imServiceColor-uk">cid: <%conversations[currentChatID].conversation_id%> | cdid: <%conversations[currentChatID].cdid%> | pid: <%conversations[currentChatID].persona_id%> | sid: <%conversations[currentChatID].subscriber_id%></div>
                                <!-- <div class="imProfileInformation"> 
                                   
                            </div> -->
                        </div>
                        <div class="persona-meta">
                            <section>
                                <div><span>Firstname</span> : <br><%conversations[currentChatID].persona.first_name%></div>
                                <div><span>Lastname</span> : <br><%conversations[currentChatID].persona.last_name%></div>
                            </section>
                            <section>
                                <div><span>Age</span> : <br><%conversations[currentChatID].persona.age%></div>
                                <div><span>Location</span> : <br><%conversations[currentChatID].persona.location%></div>
                            </section>
                        </div>
                    </div>
                    <div class="imProfileAddional">
                        <h3>Additional Information</h3>
                        <ul>
                           <li ng-repeat="(key, value) in conversations[currentChatID].persona" ng-if="key != 'height' && key != 'weight' && key != 'age' && key != 'location' && key != 'first_name' && key != 'last_name'">
                            <%key%> : <span ng-bind-html="value | linky:'_blank'"><%value%></span>
                        </li>
                    </ul>

                </div>
            </div>
            <div class="col-2-5 imPunterNoteContainer" >
                <h3>Persona Notes:</h3>
                <div class="imPunterNote">
                    <ul>
                        <li ng-repeat="note in conversations[currentChatID].conversation_notes" ng-if="note.type == 'persona'">
                            <div class="row">
                                <div class="col-lg-2 normalize-margin normalize-padding micro-icon-holder">
                                    <span title="Transfer note to punter" style="color:#324054; cursor:pointer" title="Transfer note to persona" class="glyphicon glyphicon-menu-left" aria-hidden="true" ng-click="transferNote($event, note, 'subscriber')"></span>
                                    <span title="Delete persona note" style="color:#edf1f5; cursor:pointer" ng-click="deleteConversationNote($event, note, 'persona')" class="glyphicon glyphicon-remove-sign"></span>
                                </div>
                                <div class="col-lg-10 note-content normalize-margin normalize-padding">
                                    <%note.comment%>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="imAddNotes">
                  <input  ng-model="pnote" ng-keypress="addConversationNote($event, pnote, 'persona', currentChatID)" class="textarea" type="text" placeholder="Type here!" id="personaNote-<%currentChatID%>">
              </div>
          </div>
      </div>
  </div>
  <div class="imChatContainerRow">
    <div id="cbox-<%conversation.id%>" ng-repeat="(id,conversation) in filteredConversations | limitTo:4" ng-if="conversation.status == 'pending'"  ng-class="conversation.class" class="chat-box <%(conversation.conversation_id === currentChatID) ? 'imActive' : ''%> col-2">
        <div class="imChatBox <%(conversation.conversation_id === currentChatID) ? 'imActive' : ''%>" >
            <div class="imChatHead" style="background-color:#<% services[conversation.service_id].color_theme %>;">
                <span id="imChatClose-<%conversation.conversation_id%>" class="imChatClose" ng-click="minimizeConversationBox(conversation.conversation_id)">&times;</span>
                <span class="imChatBoxTimer"><% conversation.cntdown %></span>
                <!-- <span class="imServiceTime"><% conversation.assigned_start | amDateFormat : 'MMMM DD h:mm a'%></span> -->
                <h4 class="imServiceTime">
                    <% dateNow | amAdd : conversation.offset : 'hours' | amDateFormat : 'MMM DD h:mm a'%> <br>
                    <span class="imServiceName"><% conversation.service_name %> <% conversation.service_id %> C#:<% conversation.conversation_id %></span>        
                </h4>
                
            </div>
            <div class="first-msg-date"><%conversation.first_message_bound_time | amDateFormat:'MMM DD'%> (<%conversation.first_message_bound_time | ago%>)</div>
            <div class="imChatBody">
                <!-- <ul class="chatActivator" scroll-glue ng-click="activateChatBox(conversation.conversation_id, id)" > -->
                <ul class="chatActivator" scroll-glue >
                    <li ng-repeat="message in conversation.messages| toArray:true | orderBy:'date'" class="<%message.type == 'inbound' ? 'imChatPunter' : 'imChatPersona'%> imChatHistory">
                        <div class="imChatAvatar">
                            <ng-letter-avatar dynamic='true' ng-if="message.type === 'inbound'" data="<%conversation.subscriber_name%>" charCount="1" fontWeight="300" avatarcustombgcolor="#<% services[conversation.service_id].color_theme %>"></ng-letter-avatar>
                            <!-- <img ng-if="message.type === 'inbound'" ng-src="<%conversation.subscriber.avatar%>" alt="N/A" /> -->
                            <img ng-if="message.type === 'outbound'" ng-src="<%base_url%>/api/image/thumb?type=thumb&width=33&height=33&url=<%conversation.persona.avatar%>" alt="N/A" />
                            <div ng-if="message.type === 'outbound' && message.user_id != user_id && message.flagged != true" class="imFlagchat" ng-click="flagMessage(message, conversation.conversation_id)" >&#9873;</div>
                            <div class="discard-btn" ng-show="message.content.includes('DO NOT REPLY', 0) && message.status != 'discard'"><a ng-click="discard(message.id)" href=""><i class="glyphicon glyphicon-trash"></i></a></div>

                                      <!--       <strong ng-show="message.type=='inbound' && showme" ng-bind="scopied" class="copiedNotif"></strong>
                                      <strong ng-show="message.type=='outbound' && showme" ng-bind="pcopied" class="copiedNotif"></strong> -->
                                  </div>
                                  <div ng-mouseup="showSelectedText(message.type)" ng-mousedown="showme=true" class="imChatMessage" id="imChatMessage-<%conversation.conversation_id%>">
                                    <p class="<%message.status == 'discard' ? 'discarded' : ''%>" ng-bind-html="message.content" ng-if="message.attachments.length <= 0"><%message.content%></p>
                                    <p class="<%message.status == 'discard' ? 'discarded' : ''%>" ng-bind-html="message.content" ng-if="message.attachments.length > 0 && message.content != 'undefined'"><%message.content%></p>

                                    <img class="attachment" ng-if="message.attachments.length > 0" ng-repeat="attachment in message.attachments" ng-src="<%attachment.path + attachment.file%>"/>
                                    <!-- <span>(<%message.date | amAdd : '8' : 'hours' | amDateFormat : 'h:mm:ss a'%>)</span> -->
                                    <p class="message-time inbound-time content-sub" ng-if="message.type === 'inbound'"><%message.date | amDateFormat : 'h:mm a'%> <span ng-if="message.status === 'discard'">(discarded)</span></p>
                                    <p class="message-time outbound-time" ng-if="message.type === 'outbound'"><%message.date | amDateFormat : 'h:mm a'%> <span ng-if="message.user != null">- [ <%message.user%> ]</span></p>
                                    <!-- <span>(<%message.date%>)</span> -->


                                </div>
                            </li>

                        </ul>
                        <div class="imChatTextarea">
                          <div class="uploading" ng-if="conversation.conversation_id.uploading == true">uploading</div>
                          <div class="imChatSelection">
                            <div class="imSelector img">
                               <input ng-model="attachments" onchange="angular.element(this).scope().initUpload(this.files, angular.element(this).scope().conversation.conversation_id)" id="upload-<%conversation.conversation_id%>" type="file" style="visibility:hidden; position:fixed" multiple/>     
                               <a ng-click="triggerImageUpload($event)" style="cursor:pointer"><span class="glyphicon glyphicon-picture" aria-hidden="true"></span></a>
                           </div>
                           <div class="imSelector">
                               <label>
                                <select ng-model="selectedCanned" ng-change="appendCannedMessage(selectedCanned, conversation.conversation_id)" id="imConvoCanned-<%conversation.conversation_id%>" >
                                    <option ng-repeat="canned in services[conversation.service_id].canned_messages" value="<%canned.message%>"><%canned.label%></option>
                                </select>
                            </label>
                        </div>
                        <div class="imSelector">
                            <label>
                                <select id="imConvoAction-<%conversation.conversation_id%>"
                                    ng-model="selected" ng-options="action for action in SelectActions[conversation.service_id]"
                                    ng-change="changedValue(selected)">          
                                    <option value="">Select Action</option>

                                </select>

                            </label>
                        </div>
                        <div class="imChatCounter">
                            <%conversation.message_length = (services[conversation.service_id].max_char - conversation.outbound_message.length)%>
                        </div>
                    </div>
                    <span class="imChatMessageError" ng-if="conversation.outbound_message.length === undefined || conversation.outbound_message.length < services[conversation.service_id].min_char">You need to have atleast <%services[conversation.service_id].min_char%> Character</span>
                    <span class="imChatMessageError" ng-if="conversation.outbound_message.length > services[conversation.service_id].max_char">Message can only have a maximum of <%services[conversation.service_id].max_char%> Characters</span>
                    <textarea 
                    class="<%conversation.blacklisted ? 'blacklisted' : ''%>"
                    placeholder="Enter your message here"
                    maxlength="255" 
                    ng-disabled="conversation.sending_message" 
                    ng-model='conversation.outbound_message' 
                    ng-keypress="inputKeyPress($event, conversation.conversation_id, id, message.id)" 
                    ng-keyup="inputIsPunctuation($event)" 
                    id="textarea-<%conversation.conversation_id%>" ng-focus="(conversation.conversation_id === currentChatID)">
                </textarea>
            </div>
        </div>
    </div>
</div>
</div>
</div>
</div>
</div>
@stop

@section('script')	
<script type="text/javascript" src="{{url('/')}}/js/angular/controllers/chat.js"></script>
<script type="text/javascript" src="{{url('/')}}/js/angular/factory/focus.js"></script>
<script type="text/javascript" src="{{url('/')}}/js/angular/factory/toArray.js"></script>
<script type="text/javascript" src="{{url('/')}}/js/angular/factory/transformRequestAsFormPost.js"></script>
<script type="text/javascript" src="{{url('/')}}/js/angular/app/chat/main.js"></script>

<script type="text/javascript" src="{{url('/')}}/js/res/moment.min.js"></script>

<script type="text/javascript" src="{{url('/')}}/js/angular/directives/scrollglue.min.js"></script>
<script type="text/javascript" src="{{url('/')}}/js/angular/directives/angular-moment.min.js"></script>
<script type="text/javascript" src="{{url('/')}}/js/angular/filters/angularToArrayFilter.js"></script>
<script type="text/javascript" src="{{url('/')}}/js/angular/filters/angular-moment.js"></script>
<script type="text/javascript" src="{{url('/')}}/js/angular/directives/ngletteravatar.min.js"></script>
@stop

