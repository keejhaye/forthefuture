function ChatController($scope, $socket, focus, $timeout, $filter, $interval, moment, $http, transformRequestAsFormPost, $document, Statistics, $cacheFactory, $mdDialog, $mdToast)
{
    $scope.itemList = [];
    $scope.SelectActions = {};
    $scope.services = {}
    $scope.currentChatID = undefined
    $scope.current_subscriber_name = 'x'
    $scope.current_persona_name = 'x'
    $scope.conversations = {}
    $scope.cnvCount = 0
    $scope.filteredConversations = {}
    $scope.dateNowBase = moment()
    $scope.dateNow = moment()
    $scope.statistics = Statistics
    $scope.timeouts = {}
    $scope.serviceReloadPage = false

    // $scope.tickTime = function () {
    //     $scope.dateNow = $scope.dateNow.subtract(1, 's')
    // }
    $scope.socketStatus = 'disconnected'
    $scope.isReady = false
    $scope.formData = undefined
    $scope.invalidUploads = 0
    $scope.file = []
    $scope.intervals = {}
    $scope.timerTimeouts = {}
    $scope.timerTimeoutsCaller = {}
    // $interval($scope.tickTime, 1000)

    $document.ready(function () {
        $scope.isReady = true;
        $scope.base_url = base_url;
        $scope.csrf_token = csrf_token;
        $scope.role_id = role_id;
        $scope.user_id = user_id;
        $scope.user_services = JSON.parse(user_services);
        $scope.formData = new FormData();
    })

    $socket.on('connect', function () {
        console.log('chat socket successfully connected!');
        $scope.socketStatus = 'connected'
        initialize();
    })

    function initialize(){
        if($scope.isReady){
            $socket.emit('user_data', { id : $scope.user_id, services : $scope.user_services, role_id : $scope.role_id })
            keyCheck();
            console.log($scope.user_services);
        }
        else{
            console.log('document not yet ready. restarting operation..')
            $timeout(function(){
                initialize()
            },1000)
        }
    }

    function keyCheck(){
        document.addEventListener('keydown', function(e){
            if(e.keyCode === 9){
                e.preventDefault()
            }
        })
    }

    $socket.on('disconnect', function () {
        console.log('Chat disconnected to server');
        $scope.socketStatus = 'disconnected'
    })

    $socket.on('error', function (reason) {
        console.error('Unable to connect Socket.IO', reason);
    });

    $socket.on('new_message', function (data) {
        console.log('SOCKET: new_message')
        // DETERMINE IF THERE IS AN ACTIVE/PENDING CONV. IF NOT, SET THE NEW CONV TO ACTIVE
        // CHECK FIRST IF CONVERSATION OBJECT IS DEFINED BEFORE PROCEEDING TO PROCESS
        if($scope.conversations[data.conversation_id] != undefined){
            message_ids = Object.keys($scope.conversations[data.conversation_id].messages)
            last_message_id = message_ids[message_ids.length - 1]

            new_date = adjust_date_using_timezone(data.service_id, 'now')
            data.date = new_date.date
            if ($scope.conversations[data.conversation_id].messages[last_message_id].type == 'outbound') {
                $scope.conversations[data.conversation_id].assigned_latest = new_date.date
            }

            $scope.conversations[data.conversation_id].latest_inbound_id = data.id
            $scope.conversations[data.conversation_id].sending_message = false
            
            $scope.conversations[data.conversation_id].messages[data.id] = {
                id: data.id,
                content: data.message,
                status: 'assigned',
                type: 'inbound',
                date: new_date.date,
                attachments : data.attachments,
                additional_info : data.additional_info}
            
            $scope.conversations[data.conversation_id].status = 'pending'
            $scope.conversations[data.conversation_id].last_message_id = data.id
            $scope.filterConversations('pending', false, false)
        }
    })

    $socket.on('new_conversation', function (data){
        console.log('SOCKET: new_conversation! ID:' + data.conversation_id)
        var new_cnv_socket_time = moment().format('YYYY-MM-DD HH:mm:ss')
        data.status = 'pending'
        timeout = 1000

        // DETERMINE IF THERE IS AN ACTIVE/PENDING CONV. IF NOT, SET THE NEW CONV TO ACTIVE
        if ($scope.timeouts[data.conversation_id] != undefined)
            timeout = 1000

        $timeout(function () {
            if($scope.conversations[data.conversation_id] == undefined)
                $scope.conversations[data.conversation_id] = data

            $scope.conversations[data.conversation_id].class = ''
            $scope.conversations[data.conversation_id].sbarClass = ''
            createTimer(new_cnv_socket_time, data)

            $scope.conversations[data.conversation_id].type = 'inbound'
            $scope.conversations[data.conversation_id].hidden = false

            new_date = adjust_date_using_timezone(data.service_id, 'now')
            $scope.conversations[data.conversation_id].offset = new_date.offset
            $scope.conversations[data.conversation_id].assigned_latest = new_date.date
            $scope.conversations[data.conversation_id].assigned_start = new_date.date
            $scope.conversations[data.conversation_id].date = new_date.date
            $scope.conversations[data.conversation_id].subscriber = data.subscriber_profile != undefined ? JSON.parse(data.subscriber_profile) : []
            $scope.conversations[data.conversation_id].persona = data.persona_profile != undefined ? JSON.parse(data.persona_profile) : []
            $scope.conversations[data.conversation_id].persona_name = data.persona_name
            $scope.conversations[data.conversation_id].subscriber_name = data.subscriber_name

            if (!$scope.conversations[data.conversation_id].messages)
                $scope.conversations[data.conversation_id].messages = {}

            angular.forEach($scope.conversations[data.conversation_id].messages, function (message) {
                message_date = adjust_date_using_timezone(data.service_id, message.date)
                message.date = message_date.date
            })

            if (data.message_id != undefined && $scope.conversations[data.conversation_id].messages[data.message_id] == undefined) {
                $scope.conversations[data.conversation_id].messages[data.message_id] = {id: data.message_id, content: data.message, status: 'pending', type: 'inbound', date: new_date.date}
                $scope.conversations[data.conversation_id].last_message_id = data.message_id
            }

            filtered = $scope.filterConversations('pending', false, true)
        }, timeout)
    })
        
    $socket.on('initial_conversations', function (conversations) {
        console.log("SOCKET: INITIAL CONVERSATIONS")
        var initial_socket_time = moment().format('YYYY-MM-DD HH:mm:ss')
        
        angular.forEach(conversations, function (conversation) {
            conversation['conversation_id'] = conversation.id
            conversation.hidden = false
            messagesIds = Object.keys(conversation.messages)
            last_message_id = messagesIds[messagesIds.length - 1]

            if ($scope.services[conversation.service_id].allow_multiple_reply != true && conversation.last_message_type == 'outbound')
                conversation.status = 'assigned'

            //conversation still exist due to socket reconnection
            if($scope.conversations[conversation.id] != undefined){
                $timeout.cancel($scope.timeouts[conversation.id])
                $timeout.cancel($scope.timerTimeouts[conversation.id])
                $timeout.cancel($scope.timerTimeoutsCaller[conversation.id])
                $timeout.cancel($scope.intervals[conversation.id])

                $scope.conversations[conversation.id].time_limit = conversation.unmap_start
                $scope.conversations[conversation.id].unmap_start = conversation.unmap_start
                $scope.conversations[conversation.id].wait_start = conversation.wait_start
            }
            else{
                $scope.conversations[conversation.id] = conversation
            }

            createTimer(initial_socket_time, conversation)
            
            angular.forEach($scope.conversations[conversation.id].messages, function (message) {
                message_date = adjust_date_using_timezone(conversation.service_id, message.date)
                message.date = message_date.date
            })

            new_date = adjust_date_using_timezone(conversation.service_id, conversation.assigned_latest)
            $scope.conversations[conversation.id].offset = new_date.offset
            $scope.conversations[conversation.id].date = new_date.date
            $scope.conversations[conversation.id].assigned_latest = new_date.date
            $scope.conversations[conversation.id].assigned_start = new_date.date
            $scope.conversations[conversation.id].subscriber = conversation.subscriber_profile != undefined ? JSON.parse(conversation.subscriber_profile) : []
            $scope.conversations[conversation.id].persona = conversation.persona_profile != undefined ? JSON.parse(conversation.persona_profile) : []
            $scope.conversations[conversation.id].class = ''
            $scope.conversations[conversation.id].sbarClass = ''
        })

        filtered = $scope.filterConversations('pending', false, true)
    })

    function createTimer(initial_time, data){
        var unmap_start = moment(data.unmap_start).format('YYYY-MM-DD HH:mm:ss')
        var unmap_diff = moment(initial_time).diff(moment(unmap_start))

        var initial_unmap_diff = data.time_limit - unmap_diff

        if (!isNaN(initial_unmap_diff)) {
            console.log('valid')
            var unmap_start = moment(data.unmap_start).format('YYYY-MM-DD HH:mm:ss')
            var unmap_diff = moment(initial_time).diff(moment(unmap_start))

            var initial_unmap_diff = data.time_limit - unmap_diff
            var time_limit = initial_unmap_diff / 1000; 
        } else {
            console.log('invalid date-->CREATE NEW DATE INSTANCE')
            var new_cnv_socket_time = moment().format('YYYY-MM-DD HH:mm:ss')
            var unmap_start = moment(moment()).format('YYYY-MM-DD HH:mm:ss')
            var unmap_diff = moment(new_cnv_socket_time).diff(moment(unmap_start))

            var initial_unmap_diff = data.time_limit - unmap_diff
            var time_limit = initial_unmap_diff / 1000; 
        }
        
        ///will delete existing interval if there is
        if($scope.timerTimeoutsCaller[data.conversation_id] != undefined && $scope.intervals[data.conversation_id] != undefined){
            killTimers(data.conversation_id)
        }

        //will create new interval
        $scope.timerTimeouts[data.conversation_id] = function(){
            if($scope.conversations[data.conversation_id] != undefined){
                // tmpFormat = tmpTime.subtract(1,'s')
                time_limit = time_limit - 1
                $scope.conversations[data.conversation_id]['cntdown'] = time_limit
                $scope.intervals[data.conversation_id] = $timeout(function(){
                    $scope.$apply($scope.timerTimeouts[data.conversation_id])}, 1000)

                if($scope.socketStatus == 'disconnected' && time_limit == 0){
                    //remove conversation object from conversation list if time reached 0
                    //to avoid the timer to display negative value
                    $scope.conversations[data.conversation_id].isActive = false
                    $scope.conversations[data.conversation_id].class = ''

                    sendingDone(data.conversation_id)
                    filtered = $scope.filterConversations('pending', false, true)
                }
            }
        }

        $scope.timerTimeoutsCaller[data.conversation_id] = $timeout(function(){
            $scope.$apply($scope.timerTimeouts[data.conversation_id]) }, 1000)
    }

    function killTimers(conversation_id){
        $timeout.cancel($scope.timerTimeoutsCaller[conversation_id])
        $timeout.cancel($scope.intervals[conversation_id])
        $timeout.cancel($scope.timeouts[conversation_id])
        delete $scope.intervals[conversation_id]
        delete $scope.timerTimeoutsCaller[conversation_id]
        delete $scope.timerTimeouts[conversation_id]
        delete $scope.timeouts[conversation_id]
    }

    $socket.on('initial_conversations_metadata', function (conversations_metadata) {
        console.log('initial_conversations_metadata')
        timeout = 1000

        if ($scope.timeouts[conversations_metadata.conversation_id] != undefined)
            timeout = 1000

        $timeout(function () {
            angular.forEach(conversations_metadata, function (metadata) {
                $scope.conversations[metadata.conversation_id]['conversation_notes'] = metadata.conversation_notes
                $scope.conversations[metadata.conversation_id].persona_name = metadata.persona_name
                $scope.conversations[metadata.conversation_id].subscriber_name = metadata.subscriber_name
                $scope.conversations[metadata.conversation_id].subscriber = metadata.subscriber_profile != undefined ? JSON.parse(metadata.subscriber_profile) : []
                $scope.conversations[metadata.conversation_id].persona = metadata.persona_profile != undefined ? JSON.parse(metadata.persona_profile) : []  
            })
        }, timeout)
    })

    $socket.on('services_with_rules', function (services) {
        $scope.services = services
        var service_keys = Object.keys(services)
        angular.forEach($scope.services, function (service) {
            var selected = {'label': 'Canned', 'message': ''};
            service.canned_messages.splice(0, 0, selected)

            $scope.SelectActions[service.sid] = []
            if(service.allow_blacklist){
                $scope.SelectActions[service.sid].push('Blacklist')
            }
        })
    })

    $socket.on('unmap_conversation', function (data) {
        if($scope.serviceReloadPage){
            console.log('calling reload')
            location.reload()
        }

        console.log('UNMAP CONVERSATION->' + data.conversation_id)
        timeout_delay = 500

        if($scope.conversations[data.conversation_id]){
            if($scope.conversations[data.conversation_id].sending_onprocess !== true || $scope.conversations[data.conversation_id].sending_onprocess == undefined){
                timeout_delay = $scope.conversations[data.conversation_id].uploading == true ? 5000 : 500

                if(data.from == 'unmap' && ($scope.conversations[data.conversation_id].uploading == false || $scope.conversations[data.conversation_id].uploading == undefined))
                    $scope.conversations[data.conversation_id].class = "animated slideOutUp"

                $scope.conversations[data.conversation_id].sending_message = true
                console.log("currentChatID after UNMAP: " + $scope.currentChatID)
            }
            else if(data.from == 'unmap'){
                //just remove the connversation object to avoid negative counter
                $scope.conversations[data.conversation_id].class = "animated slideOutUp"
            }

            $scope.timeouts[data.conversation_id] =
                $timeout(function () {
                    $scope.$apply(function(){
                        killTimers(data.conversation_id)
                        delete $scope.conversations[data.conversation_id]
                        delete $scope.filteredConversations[data.conversation_id]

                        filtered = $scope.filterConversations('pending', false, true)
                        $scope.cnvCount = $scope.filteredConversations.length
                    });
                }, timeout_delay)
        }
    })

    //------------------ END SOCKET ---------------------------

    $scope.minimizeConversationBox = function (conversation_id) {
        if($scope.socketStatus == 'disconnected'){
            delete $scope.conversations[conversation_id]
            delete $scope.filteredConversations[conversation_id]
            killTimers(conversation_id)
            filtered = $scope.filterConversations('pending', false, true)
        }
        else{
            $scope.conversations[conversation_id].class = "animated zoomOut"
            $scope.currentChatID = undefined
            $socket.emit('user_unmap_conversation', conversation_id)
        }
    }

    $scope.filterConversations = function (status, hidden, focus_longest_waiting) {
        pending = toArray($scope.conversations)
        filtered = $filter('filter')(pending, {status: status, hidden: hidden})
        filtered = $filter('orderBy')(filtered, 'wait_start')

        if (focus_longest_waiting && filtered.length > 0 || (filtered.length == 1)) {
            $scope.conversations[filtered[0].conversation_id].isActive = true
            $scope.currentChatID = filtered[0].conversation_id
            focus('textarea-' + filtered[0].conversation_id)
        }
        else{
            $scope.currentChatID = undefined
        }

        if($scope.conversations[$scope.currentChatID] != undefined){
            $scope.current_subscriber_name = $scope.conversations[$scope.currentChatID].subscriber_name
            $scope.current_persona_name = $scope.conversations[$scope.currentChatID].persona_name
        }

        $scope.filteredConversations = filtered
        $scope.cnvCount = $scope.filteredConversations.length
    }

    $scope.activateChatBox = function (id, textarea_id)
    {
        if ($scope.conversations[id].status === 'pending')
        {
            $scope.conversations[$scope.currentChatID].isActive = false
            $scope.conversations[id].isActive = true
            $scope.conversations[id].hidden = false
            $scope.conversations[id].class = ''
            
            $timeout(function () {
                $scope.currentChatID = id
                focus('textarea-' + id)
            }, 300)
        }
    }

    $scope.flagMessage = function (message, conversation_id) {
        $http({
            method: "POST",
            url: $scope.base_url + '/panel/chat/flag_message',
            transformRequest: transformRequestAsFormPost,
            data: {
                _token: $scope.csrf_token,
                message_id: message.id,
                message_user_id: message.user_id,
                conversation_id: conversation_id,
                path: "",
                expiry: 0
            },
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
        })
        .then(function successCallback(response) {
            if(response.data.action != undefined){
                $scope.conversations[conversation_id].messages[message.id].flagged = true
                $scope.conversations[conversation_id].messages[message.id].flagger_id = $scope.user_id

                $mdToast.show( 
                    $mdToast.simple()
                    .toastClass('toast-message-flagged')
                    .textContent('Message has been flagged.')
                    .position('bottom left')
                    .hideDelay(1000)
                );
            }
        })
    }

    $scope.inputKeyPress = function (e, conversation_id, index, message_id)
    {
        if (e.keyCode === 13) //USER PRESSED 'ENTER'
        {
            e.preventDefault()
            //Check length of the message.
            if($scope.conversations[conversation_id].outbound_message === undefined || $scope.conversations[conversation_id].outbound_message.length < $scope.services[$scope.conversations[conversation_id].service_id].min_char){
                $mdDialog.show(
                    $mdDialog.alert()
                    .clickOutsideToClose(true)
                    .textContent('Message length must be greater than ' + $scope.services[$scope.conversations[conversation_id].service_id].min_char)
                    .ariaLabel('Message length')
                    .ok('Ok!')
                ).then(function() {
                   focus('textarea-'+conversation_id)
                })
            }
            else if ($scope.conversations[conversation_id].outbound_message === undefined || $scope.conversations[conversation_id].outbound_message.length > $scope.services[$scope.conversations[conversation_id].service_id].max_char){
                $mdDialog.show(
                    $mdDialog.alert()
                    .clickOutsideToClose(true)
                    .textContent('Message can only have a maximum of ' + $scope.services[$scope.conversations[conversation_id].service_id].max_char + ' Characters')
                    .ariaLabel('Message max character')
                    .ok('Ok!')
                ).then(function() {
                   focus('textarea-'+conversation_id)
                })
            }
            else{
                $scope.conversations[conversation_id].sending_message = true
                send_reply(conversation_id)
            } 
        }
    }

    $scope.inputIsPunctuation = function(e){
        // IF USER PRESSED PERIOD(.) OR COMMA(,)
        if(e.keyCode === 190 || e.keyCode === 188){
            $scope.conversations[$scope.currentChatID].outbound_message = $scope.conversations[$scope.currentChatID].outbound_message + " "
        }
    }

    function send_reply(conversation_id) {
        if($scope.socketStatus == 'connected'){
            message_ids = Object.keys($scope.conversations[conversation_id].messages)

            details = {
                subscriber_last_message_id: message_ids[message_ids.length - 1],
                conversation_id: conversation_id,
            }

            $scope.formData.append('_token', $scope.csrf_token)
            $scope.formData.append('message', $scope.conversations[conversation_id].outbound_message)
            // $scope.formData.append('additional_info', $scope.conversations[conversation_id].messages[last_message_id].additional_info)
            $scope.formData.append('expiry', 0)
            $scope.formData.append('details', JSON.stringify(details))

            if($scope.uploading == true){
              uploadLoader()
            }

            //send data to server to remove timeut of CONVERSATION
            $socket.emit('remove_timeout', conversation_id);
            $scope.conversations[conversation_id].sending_onprocess = true
            $timeout.cancel($scope.timerTimeoutsCaller[conversation_id])
            $timeout.cancel($scope.intervals[conversation_id])

            $http({
                method: "POST",
                url: $scope.base_url + '/panel/chat/reply',
                // transformRequest: transformRequestAsFormPost,
                transformRequest: angular.identity,
                data : $scope.formData,
                headers: {
                    'Content-Type': undefined
                },
            })
            .then(function successCallback(response) {
                outbound_date = adjust_date_using_timezone($scope.conversations[conversation_id].service_id, response.data.date)
                
                $scope.conversations[response.data.conversation_id].messages[response.data.id] = {
                    id: response.data.id,
                    content: response.data.content,
                    attachments: response.data.attachments,
                    date: outbound_date.date,
                    type: 'outbound',
                    user_id: $scope.user_id
                }

                $scope.conversations[response.data.conversation_id].sending_onprocess = false
                $scope.conversations[response.data.conversation_id].sending_message = false
                $scope.conversations[response.data.conversation_id].outbound_message = ''
                $scope.conversations[response.data.conversation_id].last_message_id = response.data.id
                $scope.conversations[response.data.conversation_id].last_message_date = outbound_date.date
                
                // this is a test, remove later:
                // $scope.services[$scope.conversations[conversation_id].service_id].allow_multiple_reply = true

                if ($scope.services[$scope.conversations[conversation_id].service_id].allow_multiple_reply != true) {
                    delay = 5000

                    if($scope.conversations[response.data.conversation_id].uploading == false || $scope.conversations[response.data.conversation_id].uploading == undefined){
                        $scope.conversations[response.data.conversation_id].class = "animated zoomOut"
                        delay = 500
                    }
                    
                    $timeout(function () {
                        angular.element(document.getElementById('cbox-'+response.data.conversation_id)).remove()
                        angular.element(document.getElementById('sbar-'+response.data.conversation_id)).remove()
                        $scope.conversations[response.data.conversation_id].isActive = false
                        $scope.conversations[response.data.conversation_id].status = 'handled'
                        $scope.conversations[response.data.conversation_id].class = ''

                        filtered = $scope.filterConversations('pending', false, true)
                        sendingDone(conversation_id)
                    }, delay)
                }else
                    focus('textarea-' + response.data.conversation_id)

                $scope.formData = new FormData()
                $scope.file = []
                $scope.invalidUploads = 0

                $mdToast.show( 
                    $mdToast.simple()
                    .toastClass('toast-message-sent')
                    .textContent('Message sent.')
                    .position('bottom left')
                    .hideDelay(1000)
                );

                if($scope.serviceReloadPage){
                    $socket.emit('user_unmap_conversation', response.data.conversation_id)
                    location.reload();
                }

                angular.element(document.getElementById('subscriberNote-' + $scope.currentChatID)).val("");
                angular.element(document.getElementById('personaNote-' + $scope.currentChatID)).val("");
            },
            function errorCallback(response) {
                console.log('errorCallback')
                console.log(response)
            })
        }
        else{
            $scope.conversations[conversation_id].sending_message = false
            console.log('socket is not connected please try again later...')

            $mdDialog.show(
                $mdDialog.alert()
                .clickOutsideToClose(true)
                .textContent('Your status is "Disconnected". Cannot send message as of now')
                .ariaLabel('Disconnected socket')
                .ok('Ok!')
            );
        }

    }

    function sendingDone(conversation_id){
        if($scope.serviceReloadPage) location.reload();

        //remove conversation object from $scope.conversations
        $scope.conversations[conversation_id].class = "animated zoomOut"
        killTimers(conversation_id)
        delete $scope.timeouts[conversation_id]
        delete $scope.conversations[conversation_id]
        delete $scope.filteredConversations[conversation_id]
    }

    function toArray(obj, addKey) {
        if (!angular.isObject(obj))
            return obj
        if (addKey === false) {
            return Object.keys(obj).map(function (key) {
                return obj[key]
            })
        } else {
            return Object.keys(obj).map(function (key) {
                var value = obj[key]
                return angular.isObject(value) ?
                        Object.defineProperty(value, '$key', {enumerable: false, value: key}) :
                        {$key: key, $value: value}
            })
        }
    }

    function adjust_date_using_timezone(service_id, date) {
        if (date == 'now') {
            date = new moment().format('YYYY-MM-DD HH:mm:ss')
        }
        offset = $scope.services[service_id].timezone - 8;
        new_date = new moment(date).add(offset, 'hours').format('YYYY-MM-DD HH:mm:ss')
        return {date: new_date, offset: offset}
    }

    //------------------ KEY SHORTCUTS ---------------------------

    $scope.chatKeyShortcuts = function () {
        $document.on("keydown", function (event) {

            // WHEN KEY PRESSED IS SHIFT + {ANOTHER KEY}
            // if (event.shiftKey) {
            //     switch (event.keyCode) {
            //         case 83:  // shift + s
            //                 event.preventDefault();
            //             focus("subscriberNote-" + $scope.currentChatID);
            //             break;
            //         case 80: // shift + p
            //             event.preventDefault();
            //             focus("personaNote-" + $scope.currentChatID);
            //             break;
            //         case 65: // shift + a
            //             event.preventDefault();
            //             focus("textarea-" + $scope.currentChatID);
            //             break;
            //         case 66: // shift + b
            //             console.log("shift b was pressed");
            //             // document.getElementById('imConvoAction-'+$scope.currentChatID).blur()
            //             // focus('imConvoAction-'+$scope.currentChatID)  
            //             break;
            //         case 67: // shift + c
            //             event.preventDefault();
            //             document.getElementById('imConvoCanned-' + $scope.currentChatID).blur()
            //             focus('imConvoCanned-' + $scope.currentChatID)
            //             break;
            //         case 68: // shift + d
            //             console.log("shift d was pressed");
            //             break;
            //     }
            // }

            //  CTRL + SHIFT +M
            if ((event.ctrlKey || event.metaKey) && event.shiftKey && event.keyCode === 77) {
                event.preventDefault()
                angular.element(document.getElementById("imChatClose-" + $scope.currentChatID)).triggerHandler('click')
            }

            // CTRL + LEFT ARROW KEY || CTRL + RIGHT ARROW KEY || SHIFT + TAB
            if (((event.ctrlKey || event.metaKey) && event.keyCode === 37) || ((event.ctrlKey || event.metaKey) && event.keyCode === 39) || (event.shiftKey && event.keyCode === 9)) {

                var chatBoxes = document.getElementsByClassName("chatActivator");
                var activeKey = undefined;
                var boxToActivate = undefined;

                angular.forEach(chatBoxes, function (box, key) {
                    var activeChatBox = angular.element(box).parent().parent().hasClass("imActive");

                    if (activeChatBox) {
                        activeKey = key
                        boxToActivate = chatBoxes[activeKey + 1]
                    }
                });

                if (event.keyCode === 9 || event.keyCode === 39) { // TAB & RIGHT ARROW KEY
                    if (--chatBoxes.length == activeKey)
                        angular.element(chatBoxes[0]).triggerHandler('click')
                    else
                        angular.element(boxToActivate).triggerHandler('click')
                } else if (event.keyCode === 37) { // LEFT
                    if (activeKey == 0)
                        angular.element(chatBoxes[--chatBoxes.length]).triggerHandler('click')
                    else
                        angular.element(chatBoxes[activeKey - 1]).triggerHandler('click')
                }
            }
        });
    }

    $scope.appendCannedMessage = function(message,cid){
      // angular.element(document.getElementById('textarea-'+$scope.currentChatID)).val(message)
      $scope.conversations[cid].outbound_message = message
      focus('textarea-' + cid)
    }

   //------------------ SUBSCRIBER/PERSONA NOTES ---------------------------

    $scope.addConversationNote = function(e, note, type, cid){
        if(e.keyCode === 13){
            if(type == 'subscriber'){
                note = angular.element(document.getElementById('subscriberNote-' + $scope.currentChatID)).val();
            }else{
                note = angular.element(document.getElementById('personaNote-' + $scope.currentChatID)).val();
            }

            $http({
              method : "POST",
              url : $scope.base_url + '/panel/chat/add_note',
              transformRequest : transformRequestAsFormPost,
              data : {
                _token : $scope.csrf_token, 
                type: type,
                comment: note,
                cid : $scope.currentChatID,
                expiry : 0,
            },
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
        })
        .then(function successCallback(response) {
            if($scope.conversations[cid] != undefined){
                $scope.conversations[cid].conversation_notes[response.data.note.id] = response.data.note

                $mdToast.show( 
                    $mdToast.simple()
                    .toastClass('toast-note-added')
                    .textContent('Note added.')
                    .position('bottom right')
                    .hideDelay(1000)
                    );

                if(type == 'subscriber'){
                   $scope.snote = null;
                   angular.element(document.getElementById('subscriberNote-' + $scope.currentChatID)).val('');

               }else{
                   $scope.pnote = null;
                   angular.element(document.getElementById('personaNote-' + $scope.currentChatID)).val('');
               }
           }
           else{
            $mdToast.show( 
                $mdToast.simple()
                .textContent('Previous note has been saved!')
                .position('bottom right')
                .hideDelay(2000)
                );
            }
        })

        }
    }

    $scope.deleteConversationNote = function(e, note){
        li = angular.element(e.currentTarget).parent().parent().parent()

        $http({
            method: "POST",
            url : $scope.base_url + '/panel/chat/delete_note',
            transformRequest : transformRequestAsFormPost,
            data : {
                _token : $scope.csrf_token,
                note_id: note.id,
                cid: $scope.currentChatID,
                expiry : 0,
            },
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            }
        })
        .then(function successCallback(response){
            console.log(response)
            if(response.data.status = "ok"){
                li.remove()
                $mdToast.show( 
                    $mdToast.simple()
                    .toastClass('toast-note-deleted')
                    .textContent('Note deleted.')
                    .position('bottom right')
                    .hideDelay(1000)
                );
            }
            else{
                $mdToast.show( 
                    $mdToast.simple()
                    .textContent('Note was not deleted. Please try again later.')
                    .position('bottom right')
                    .hideDelay(1002)
                );
            }
        })
    }

    $scope.changedValue = function (item) {
        details = {
            subscriber: $scope.conversations[$scope.currentChatID].subscriber_id,
            conversation_id: $scope.currentChatID
        }
        if (item === 'Blacklist') {
            var dialog = $mdDialog.confirm()
                    .htmlContent("Are you sure you want to block this subscriber?")
                    .ariaLabel('blacklist-alert')
                    .ok('Yes')
                    .cancel('No')
    
            $mdDialog.show(dialog)
                .then(function(){
                    //ok
                    $socket.emit('remove_timeout', details.conversation_id)
                    $scope.conversations[details.conversation_id].class = 'animated zoomOut'
                    delete $scope.conversations[details.conversation_id]
                    delete $scope.filteredConversations[details.conversation_id]
                    killTimers(details.conversation_id)
                    $scope.filterConversations('pending', false, true)
                    blacklist(details); 
                },
                function(){
                    //cancel
                });
        }
    }

    $scope.discard = function(mid){
         details = {
            subscriber: $scope.conversations[$scope.currentChatID].subscriber_id,
            conversation_id: $scope.currentChatID,
            service_id:  $scope.conversations[$scope.currentChatID].service_id,
            message_id:  mid,
        }
        discard_conversation(details);               
    }

    function discard_conversation(details){
        $http({
            method: "POST",
            url: $scope.base_url + '/panel/chat/discard',
            transformRequest: transformRequestAsFormPost,
            data: {
                details: JSON.stringify(details)
            },
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
        })
        .then(function successCallback(response) {
            $mdToast.show( 
                $mdToast.simple()
                .toastClass('toast-message-discard')
                .textContent('Message has been discarded.')
                .position('bottom left')
                .hideDelay(1000)
            );

            $scope.conversations[details.conversation_id].class = "animated slideOutUp"
            killTimers(details.conversation_id)
            $timeout(function(){
                delete $scope.timeouts[details.conversation_id]
                delete $scope.conversations[details.conversation_id]
                delete $scope.filteredConversations[details.conversation_id]
                filtered = $scope.filterConversations('pending', false, true)
            }, 500)
        },function errorCallback(response) {
            console.log('errorCallback')
            console.log(response)
        })
    }

    function blacklist(details) {
        $http({
            method: "POST",
            url: $scope.base_url + '/panel/chat/blacklist',
            transformRequest: transformRequestAsFormPost,
            data: {
                details: JSON.stringify(details)
            },
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
        })
        .then(function successCallback(response) {
            if (response === 0) {

            }
        },function errorCallback(response) {
            console.log('errorCallback')
            console.log(response)
        })
    }

    $socket.on('unmap_blacklisted_conversation', function(cid){
        if($scope.conversations[cid] != undefined && $scope.filteredConversations[cid] != undefined){
            $scope.conversations[cid]['blacklisted'] = true
            $scope.filteredConversations[cid]['blacklisted'] = true
        }

        killTimers(cid)
        
        if($scope.currentChatID == cid){
            var dialog = $mdDialog.alert()
                    .htmlContent("<strong>Subscriber "+ $scope.conversations[cid].subscriber_name + " has been blacklisted</strong>. Current conversation has been already handled.")
                    .ariaLabel('blacklisted-subscriber')
                    .escapeToClose(true)
                    .ok('ok')
    
            $mdDialog.show(dialog)
                .finally(function() {
                    console.log('finally')
                    $scope.conversations[cid].class = 'animated zoomOut'
                    delete $scope.conversations[cid]
                    delete $scope.filteredConversations[cid]
                    filtered = $scope.filterConversations('pending', false, true)
                });
        }
        else{
            if($scope.conversations[cid] != undefined){
                var subscriber_name = $scope.conversations[cid].subscriber_name
                $scope.conversations[cid].class = 'animated zoomOut'
                $scope.conversations[cid].sbarClass = 'animated zoomOut'
                delete $scope.conversations[cid]
                delete $scope.filteredConversations[cid]

                $mdToast.show( 
                    $mdToast.simple()
                        .toastClass('toast-blacklisted')
                        .textContent('Subcriber '+ subscriber_name + ' has been blacklisted. Conversation has been closed.')
                        .position('bottom right')
                        .hideDelay(4000)
                    );
            }
        }
    })

    $scope.triggerImageUpload = function(e){
        e.preventDefault();
        document.getElementById('upload-'+$scope.currentChatID).click()
    }

    $scope.initUpload = function(attachments, cid){
        var lengthCnt = attachments.length
        if($scope.formData == undefined) $scope.formData = new FormData()

        for(var i=0; i < attachments.length; i++){
            var _URL = window.URL || window.webkitURL;
            var image = new Image()

            image.src = _URL.createObjectURL(attachments[i]);
            image.lengthCnt = lengthCnt
            image.ctr = 0
            $scope.file.push(attachments[i])

            image.onload = function () {
              $scope.file[image.ctr].width = this.width
              $scope.file[image.ctr].height = this.height
              image.lengthCnt--

            // console.log($scope.file[image.ctr])
            imgOnloadCallback($scope.file[image.ctr], cid)

            if(image.lengthCnt == 0 && $scope.invalidUploads > 0){
                if($scope.invalidUploads == lengthCnt) alert("Cannot upload image. Image must be less than 800x600 and 70kb only")
                    else if($scope.invalidUploads == 1 && lengthCnt > 1) alert("Cannot upload 1 of the images. Image must be less than 800x600 and 70kb")
                        else if($scope.invalidUploads > 1 && lengthCnt > 1) alert("Cannot upload " + $scope.invalidUploads + " of the images. Images must be less than 800x600 and 70kb")
                    }

                if(image.lengthCnt == 0 && $scope.invalidUploads == 0){
                    $scope.conversations[cid].uploading = true
                    uploadLoader()
                    send_reply(cid);
                }

                image.ctr++
            }
        }
    }

    function imgOnloadCallback(image, cid){
        var isValid = {type: false, dimension: true, size: false}
        var imgTypes = ["jpeg", "jpg", "png", "gif"]
        var maxSize = 70000 // 70kb

        isValid.type = isValid.type || imgTypes.indexOf(image.name.substr(image.name.lastIndexOf('.')+1))
        isValid.size = isValid.size || (image.size <= maxSize)
        isValid.dimension = (image.width <= 800 && image.height <= 600) ? true : false
      
        if(isValid.type && isValid.size && isValid.dimension){
            $scope.formData.append('images[]', image)
        }
        else
            $scope.invalidUploads++
    }

    function uploadLoader(){
        $timeout(function(){
        // console.log(angular.element(document.getElementsByClassName("uploading")))
            var dom = angular.element(document.getElementsByClassName("uploading"))
            if(dom){
                $interval(function(){dom.html("uploading")}, 300)
                $interval(function(){dom.html("uploading.")}, 600)
                $interval(function(){dom.html("uploading..")}, 900)
                $interval(function(){dom.html("uploading...")}, 1200)
            }
        }, 1000)
    }

    $scope.showSelectedText = function(direction) {
        var content = $scope.getSelectionText();
      
        if(direction == "inbound" && content.indexOf('NOTE') == -1){         
            if($scope.getSelectionText().length !== 0){
                $mdToast.show( 
                    $mdToast.simple()
                    .toastClass('toast-note-copied')
                    .textContent('Copied to SUBSCRIBER note input')
                    .position('bottom left')
                    .hideDelay(1000)
                );

                var subscriberInitialNote = angular.element(document.getElementById('subscriberNote-' + $scope.currentChatID)).val();
                if(subscriberInitialNote.length == 0){
                    angular.element(document.getElementById('subscriberNote-' + $scope.currentChatID)).val($scope.getSelectionText());  
                    $scope.snote = angular.element(document.getElementById('subscriberNote-' + $scope.currentChatID)).val();
                }
                else{
                    var currentValue =  angular.element(document.getElementById('subscriberNote-' + $scope.currentChatID)).val();
                    angular.element(document.getElementById('subscriberNote-' + $scope.currentChatID)).val(currentValue + ' ' + $scope.getSelectionText());  
                    $scope.snote = angular.element(document.getElementById('subscriberNote-' + $scope.currentChatID)).val();    
                }

                focus("subscriberNote-" + $scope.currentChatID);
            }
        }
        
        var content = $scope.getSelectionText();
        if(direction == "outbound" || content.indexOf('NOTE') >= 0) {
            if($scope.getSelectionText().length !== 0){
                $mdToast.show( 
                    $mdToast.simple()
                        .toastClass('toast-note-copied')
                        .textContent('Copied to PERSONA note input')
                        .position('bottom left')
                        .hideDelay(1000)
                    );

                var personaInitialNote = angular.element(document.getElementById('personaNote-' + $scope.currentChatID)).val();
                if(personaInitialNote.length == 0){
                    angular.element(document.getElementById('personaNote-' + $scope.currentChatID)).val($scope.getSelectionText());   
                    $scope.pnote = angular.element(document.getElementById('personaNote-' + $scope.currentChatID)).val();        
                }
                else{
                    var currentValue =  angular.element(document.getElementById('personaNote-' + $scope.currentChatID)).val();
                    angular.element(document.getElementById('personaNote-' + $scope.currentChatID)).val(currentValue + ' ' + $scope.getSelectionText()); 
                    //$scope.pnote = angular.element(document.getElementById('personaNote-' + $scope.currentChatID)).val();
                    $scope.pnote = angular.element(document.getElementById('personaNote-' + $scope.currentChatID)).val(); 
                }  
                
                focus("personaNote-" + $scope.currentChatID);
            }   
        }
    };
    
    $scope.getSelectionText = function() {
        var text = "";
        if (window.getSelection) {
            text = window.getSelection().toString();
        } else if (document.selection && document.selection.type != "Control") {
            text = document.selection.createRange().text;
        }

        return text;
    };

    $scope.transferNote = function(e, note, transferTo){
        note.new_type = transferTo
        note.cid = $scope.currentChatID
        $socket.emit('update_note_type', note)
        $scope.conversations[$scope.currentChatID].conversation_notes[note.id].type = transferTo
    }

    $socket.on('unassigned_service', function(){
        if($scope.currentChatID != undefined){
            $scope.serviceReloadPage = true
            $mdToast.show( 
                $mdToast.simple()
                    .toastClass('toast-service-notif')
                    .textContent('A service has been unassigned to you. Page will reload after handling conversation or after unmap')
                    .position('top right')
                    .hideDelay(20000)
                );
        }
    })

    $socket.on('new_service_notif_onchat', function(message){
        if($scope.currentChatID == undefined){
            message = 'A new service has been assigned. Page will reload shortly'
        }
        else{
            $scope.serviceReloadPage = true
            message = 'A new service has been assigned. Page will reload after handling current conversation'
        }

        toast = $mdToast.show( 
            $mdToast.simple()
                .toastClass('toast-service-notif')
                .textContent(message)
                .position('top right')
                .hideDelay(20000)
            )

        if($scope.currentChatID == undefined){
            toast.then(function(){
                location.reload()
            })
        }
    })

} // end of chat controller