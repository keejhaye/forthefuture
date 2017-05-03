
<nav class="navbar navbar-custom navbar-default navbar-fixed-top" ng-controller="HeaderController">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <div class="dropdown" uib-dropdown on-toggle="toggled(open)">
                <a href="#" class="navbar-brand dropdown-toggle" uib-dropdown-toggle data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                    <img src="<?php echo URL::asset('img/imlogo.png')?>" alt="">
                </a>
                <ul class="dropdown-menu" uib-dropdown-menu>
                    @if(\Redis::sismember('role_permissions:'.session('user.role_id'), 'section_message_history'))
                    <li class="alt"><a href="{{ url('panel/history') }}">Conversation History</a></li>      
                    @endif

                    @if(\Redis::sismember('role_permissions:'.session('user.role_id'), 'section_online_users'))
                    <li><a href="{{ url('panel/online') }}">Online Users</a></li> 
                    @endif

                    @if(\Redis::sismember('role_permissions:'.session('user.role_id'), 'section_park'))
                    <li class="alt"><a href="{{ url('panel/park') }}">Park</a></li>
                    @endif

                       <!--  @if(\Redis::sismember('role_permissions:'.session('user.role_id'), 'section_bulletins'))
                        <li><a href="{{ url('panel/bulletins') }}">Bulletins</a></li>
                        @endif -->

                      <!--   @if(\Redis::sismember('role_permissions:'.session('user.role_id'), 'section_bulletins_archive'))
                        <li class="alt"><a href="{{ url('panel/bulletins_archive/load') }}" class="bulletinsArchive">Bulletins Archive</a></li>
                        @endif -->
                        
                        @if(\Redis::sismember('role_permissions:'.session('user.role_id'), 'section_users'))
                        <li><a href="{{ url('panel/users') }}">Users</a></li>      <li class="alt"><a href="{{ url('panel/context') }}">Services</a></li>
                        @endif
                        
                      <!--   @if(\Redis::sismember('role_permissions:'.session('user.role_id'), 'section_libraries'))
                        <li><a href="{{ url('panel/libraries') }}">Libraries</a></li>
                        @endif -->
                        
                        @if(\Redis::sismember('role_permissions:'.session('user.role_id'), 'section_personas'))
                        <li class="alt"><a href="{{ url('panel/personas') }}">Personas</a></li>
                        @endif

                        @if(\Redis::sismember('role_permissions:'.session('user.role_id'), 'section_subscribers'))
                        <li><a href="{{ url('panel/subscribers') }}">Subscribers</a></li>
                        @endif

                        @if(\Redis::sismember('role_permissions:'.session('user.role_id'), 'section_flagged_messages'))
                        <li class="alt"><a href="{{ url('panel/flagged_messages') }}">Flagged Messages</a></li>
                        @endif
                        
                     <!--    @if(\Redis::sismember('role_permissions:'.session('user.role_id'), 'section_flagged_messages_stats'))
                        <li><a href="{{ url('panel/flagged_messages/statistics') }}">Flagged Messages Stats</a></li>
                        @endif -->

                        @if(\Redis::sismember('role_permissions:'.session('user.role_id'), 'section_report'))
                        <li class="alt"><a href="{{ url('panel/reports') }}">Reports</a></li>
                        @endif

                        @if(\Redis::sismember('role_permissions:'.session('user.role_id'), 'section_profile'))
                        <li><a href="{{ url('panel/profile') }}">My Profile</a></li> 
                        @endif

                        @if(\Redis::sismember('role_permissions:'.session('user.role_id'), 'section_activity_log'))
                        <li class="alt"><a href="{{ url('panel/user_activity') }}">User Activity Log</a></li>
                        @endif

                        @if(\Redis::sismember('role_permissions:'.session('user.role_id'), 'section_blacklist'))
                        <li><a href="{{ url('panel/blacklist') }}">Blacklist</a></li>
                        @endif

                         @if(\Redis::sismember('role_permissions:'.session('user.role_id'), 'section_settings'))
                        <li><a href="{{ url('panel/settings') }}">Settings</a></li>
                        @endif

                        <li class="alt"><a href="{{ url('auth/logout') }}">Logout</a></li>
                    </ul>
                </div>
            </div>
            <div id="navbar" class="navbar-collapse collapse">
                <ul class="nav navbar-nav left-nav">
                    @if(\Redis::sismember('role_permissions:'.session('user.role_id'), 'section_park'))
                    <li><a class="park" href="{{ url('panel/park') }}">Park</a></li>
                    @endif
                    
                    @if(\Redis::sismember('role_permissions:'.session('user.role_id'), 'section_chat'))
                    <li><a class="chat" href="{{ url('panel/chat') }}">Chat</a></li>
                    @endif

                </ul>
                <ul class="nav navbar-nav operator-stat-holder">
                    <li class="status-connected <% status %> animated infinite pulse">
                        <div class="status-ripple-circular">
                            <span class="status-ripple"></span>
                        </div>
                    </li>

                    <li class="stats-user">
                        <span class="stats-title">Today</span>
                    </li>
                    <li class="current-stats">
                        <!-- <i class="fa fa-bar-chart-o" aria-hidden="true"></i> -->
                        <% statistics.outbound_count %>
                        
                    </li>
                    <li class="clock">
                        <i class="fa fa-clock-o" aria-hidden="true"></i> <% statistics.chat_time %>
                    </li>
                </ul>
                <ul class="nav navbar-nav navbar-right">

                    <li class="stats-user"><strong>Stats </strong> 
                        <span class="inbound" data-toggle="tooltip" data-placement="bottom" title="Inbound"><i class="fa fa-arrow-down" aria-hidden="true"></i> <% statistics.total_inbound %></span>  
                        <span class="outbound" data-toggle="tooltip" data-placement="bottom" title="Outbound"><i class="fa fa-arrow-up" aria-hidden="true"></i> <% statistics.total_outbound %></span>  
                        <span class="online" data-toggle="tooltip" data-placement="bottom" title="Online"><i class="fa fa-user" aria-hidden="true"></i> <% statistics.online_count %></span>  
                        <span class="unassigned" data-toggle="tooltip" data-placement="bottom" title="Un-Assigned"><i class="fa fa-comment-o" aria-hidden="true"></i> <% statistics.unassigned_conversations %></span> 
                        <span class="assigned" data-toggle="tooltip" data-placement="bottom" title="Assigned"><i class="fa fa-comment" aria-hidden="true"></i> <% statistics.assigned_conversations %></span></li>
                    </ul>
                </div><!--/.nav-collapse -->
            </div>
        </nav>