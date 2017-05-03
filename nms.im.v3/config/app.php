<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Application Environment
    |--------------------------------------------------------------------------
    |
    | This value determines the "environment" your application is currently
    | running in. This may determine how you prefer to configure various
    | services your application utilizes. Set this in your ".env" file.
    |
    */

    'env' => env('APP_ENV', 'production'),

    /*
    |--------------------------------------------------------------------------
    | Application Debug Mode
    |--------------------------------------------------------------------------
    |
    | When your application is in debug mode, detailed error messages with
    | stack traces will be shown on every error that occurs within your
    | application. If disabled, a simple generic error page is shown.
    |
    */

    'debug' => env('APP_DEBUG', true),

    /*
    |--------------------------------------------------------------------------
    | Application URL
    |--------------------------------------------------------------------------
    |
    | This URL is used by the console to properly generate URLs when using
    | the Artisan command line tool. You should set this to the root of
    | your application so that it is used when running Artisan tasks.
    |
    */

    'url' => env('APP_URL', 'http://localhost'),

    /*
    |--------------------------------------------------------------------------
    | Application Timezone
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default timezone for your application, which
    | will be used by the PHP date and date-time functions. We have gone
    | ahead and set this to a sensible default for you out of the box.
    |
    */

    'timezone' => 'UTC',

    /*
    |--------------------------------------------------------------------------
    | Application Locale Configuration
    |--------------------------------------------------------------------------
    |
    | The application locale determines the default locale that will be used
    | by the translation service provider. You are free to set this value
    | to any of the locales which will be supported by the application.
    |
    */

    'locale' => 'en',

    /*
    |--------------------------------------------------------------------------
    | Application Fallback Locale
    |--------------------------------------------------------------------------
    |
    | The fallback locale determines the locale to use when the current one
    | is not available. You may change the value to correspond to any of
    | the language folders that are provided through your application.
    |
    */

    'fallback_locale' => 'en',

    /*
    |--------------------------------------------------------------------------
    | Encryption Key
    |--------------------------------------------------------------------------
    |
    | This key is used by the Illuminate encrypter service and should be set
    | to a random, 32 character string, otherwise these encrypted strings
    | will not be safe. Please do this before deploying an application!
    |
    */

    'key' => env('APP_KEY'),

    'cipher' => 'AES-256-CBC',

    /*
    |--------------------------------------------------------------------------
    | Logging Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure the log settings for your application. Out of
    | the box, Laravel uses the Monolog PHP logging library. This gives
    | you a variety of powerful log handlers / formatters to utilize.
    |
    | Available Settings: "single", "daily", "syslog", "errorlog"
    |
    */

    'log' => env('APP_LOG', 'single'),

    /*
    |--------------------------------------------------------------------------
    | Autoloaded Service Providers
    |--------------------------------------------------------------------------
    |
    | The service providers listed here will be automatically loaded on the
    | request to your application. Feel free to add your own services to
    | this array to grant expanded functionality to your applications.
    |
    */

    'providers' => [

        /*
         * Laravel Framework Service Providers...
         */
         Ixudra\Curl\CurlServiceProvider::class,
        Illuminate\Auth\AuthServiceProvider::class,
        Illuminate\Broadcasting\BroadcastServiceProvider::class,
        Illuminate\Bus\BusServiceProvider::class,
        Illuminate\Cache\CacheServiceProvider::class,
        Illuminate\Foundation\Providers\ConsoleSupportServiceProvider::class,
        Illuminate\Cookie\CookieServiceProvider::class,
        Illuminate\Database\DatabaseServiceProvider::class,
        Illuminate\Encryption\EncryptionServiceProvider::class,
        Illuminate\Filesystem\FilesystemServiceProvider::class,
        Illuminate\Foundation\Providers\FoundationServiceProvider::class,
        Illuminate\Hashing\HashServiceProvider::class,
        Illuminate\Mail\MailServiceProvider::class,
        Illuminate\Pagination\PaginationServiceProvider::class,
        Illuminate\Pipeline\PipelineServiceProvider::class,
        Illuminate\Queue\QueueServiceProvider::class,
        Illuminate\Redis\RedisServiceProvider::class,
        Illuminate\Auth\Passwords\PasswordResetServiceProvider::class,
        Illuminate\Session\SessionServiceProvider::class,
        Illuminate\Translation\TranslationServiceProvider::class,
        Illuminate\Validation\ValidationServiceProvider::class,
        Illuminate\View\ViewServiceProvider::class,
         Collective\Html\HtmlServiceProvider::class,


        /*
         * Application Service Providers...
         */
        App\Providers\AppServiceProvider::class,
        App\Providers\AuthServiceProvider::class,
        App\Providers\EventServiceProvider::class,
        App\Providers\RouteServiceProvider::class,
        App\Providers\HelperServiceProvider::class,
        

        /*
         * Custom Service Providers...
         */
        'Clockwork\Support\Laravel\ClockworkServiceProvider'


    ],

    /*
    |--------------------------------------------------------------------------
    | Class Aliases
    |--------------------------------------------------------------------------
    |
    | This array of class aliases will be registered when this application
    | is started. However, feel free to register as many as you wish as
    | the aliases are "lazy" loaded so they don't hinder performance.
    |
    */
   
    'aliases' => [
        'App' => Illuminate\Support\Facades\App::class,
        'Artisan' => Illuminate\Support\Facades\Artisan::class,
        'Auth' => Illuminate\Support\Facades\Auth::class,
        'Blade' => Illuminate\Support\Facades\Blade::class,
        'Cache' => Illuminate\Support\Facades\Cache::class,
        'Config' => Illuminate\Support\Facades\Config::class,
        'Cookie' => Illuminate\Support\Facades\Cookie::class,
        'Crypt' => Illuminate\Support\Facades\Crypt::class,
        'Curl'  => Ixudra\Curl\Facades\Curl::class,
        'DB' => Illuminate\Support\Facades\DB::class,
        'Eloquent' => Illuminate\Database\Eloquent\Model::class,
        'Event' => Illuminate\Support\Facades\Event::class,
        'File' => Illuminate\Support\Facades\File::class,
        'Gate' => Illuminate\Support\Facades\Gate::class,
        'Hash' => Illuminate\Support\Facades\Hash::class,
        'Lang' => Illuminate\Support\Facades\Lang::class,
        'Log' => Illuminate\Support\Facades\Log::class,
        'Mail' => Illuminate\Support\Facades\Mail::class,
        'Password' => Illuminate\Support\Facades\Password::class,
        'Queue' => Illuminate\Support\Facades\Queue::class,
        'Redirect' => Illuminate\Support\Facades\Redirect::class,
        'Redis' => Illuminate\Support\Facades\Redis::class,
        'Request' => Illuminate\Support\Facades\Request::class,
        'Response' => Illuminate\Support\Facades\Response::class,
        'Route' => Illuminate\Support\Facades\Route::class,
        'Schema' => Illuminate\Support\Facades\Schema::class,
        'Session' => Illuminate\Support\Facades\Session::class,
        'Storage' => Illuminate\Support\Facades\Storage::class,
        'URL' => Illuminate\Support\Facades\URL::class,
        'Validator' => Illuminate\Support\Facades\Validator::class,
        'View' => Illuminate\Support\Facades\View::class,
          'Form' => Collective\Html\FormFacade::class,

        /*
         * Libraries
         */
        'Outbound' => \App\Libraries\Outbound::class,
        'Inbound' => \App\Libraries\Inbound::class,
        'OutboundQueue' => \App\Libraries\OutboundQueue::class,
        'InboundQueue' => \App\Libraries\InboundQueue::class,
        'InboundExecution' => \App\Libraries\InboundExecution::class,
        'Benchmark' => \App\Libraries\Benchmark::class,
        'Piczelle' => \App\Libraries\Piczelle\Piczelle::class,

        /*
         * Helpers
         */
        'LogHelper' => App\Helpers\LogHelper::class,
        'UserHelper' => App\Helpers\UserHelper::class,
        'DateTimeHelper' => App\Helpers\DateTimeHelper::class,
        'ApiHelper' => \App\Helpers\ApiHelper::class,
        'TracesHelper' => \App\Helpers\TracesHelper::class,
        'UtilHelper' => \App\Helpers\UtilHelper::class,
         'DropdownHelper' => App\Helpers\DropdownHelper::class,
        'DateTimeHelper' => App\Helpers\DateTimeHelper::class,
        'SwitchDBOTF' => App\Helpers\SwitchDBOTF::class,

        /*
         * Models
         */
        'DoctrineLockTracking'  => App\Models\DoctrineLockTracking::class,
        'RptConversationDuration'  => App\Models\RptConversationDuration::class,
        'RptMessageInterval'  => App\Models\RptMessageInterval::class,
        'RptMessages'  => App\Models\RptMessages::class,
        'RptOperatorResponseLogs'  => App\Models\RptOperatorResponseLogs::class,
        'RptSubscriberBilling'  => App\Models\RptSubscriberBilling::class,
        'RptUserConversationLogs'  => App\Models\RptUserConversationLogs::class,
        'RptUserLogs'  => App\Models\RptUserLogs::class,
        'RptUserSystemLogs'  => App\Models\RptUserSystemLogs::class,
        'TblActions'  => App\Models\TblActions::class,
        'TblActionsQueue'  => App\Models\TblActionsQueue::class,
        'TblAggregatorResponses'  => App\Models\TblAggregatorResponses::class,
        'TblAutoDiscard'  => App\Models\TblAutoDiscard::class,
        'TblAutoremindedMessages'  => App\Models\TblAutoremindedMessages::class,
        'TblAutoreminders'  => App\Models\TblAutoreminders::class,
        'TblAutoremindersQueue'  => App\Models\TblAutoremindersQueue::class,
        'TblAutoresponseQueue'  => App\Models\TblAutoresponseQueue::class,
        'TblAutoresponses'  => App\Models\TblAutoresponses::class,
        'TblAutorespondedMessages'  => App\Models\TblAutorespondedMessages::class,
        'TblBlacklist'  => App\Models\TblBlacklist::class,
        'TblBulletinService'  => App\Models\TblBulletinService::class,
        'TblBulletinUser'  => App\Models\TblBulletinUser::class,
        'TblBulletins'  => App\Models\TblBulletins::class,
        'TblCannedMessages'  => App\Models\TblCannedMessages::class,
        'TblConditions'  => App\Models\TblConditions::class,
        'TblConversationCommentUser'  => App\Models\TblConversationCommentUser::class,
        'TblConversationComments'  => App\Models\TblConversationComments::class,
        'TblConversationDuration'  => App\Models\TblConversationDuration::class,
        'TblConversationLogs'  => App\Models\TblConversationLogs::class,
        'TblConversationMessagesLog'  => App\Models\TblConversationMessagesLog::class,
        'TblConversationNotes'  => App\Models\TblConversationNotes::class,
        'TblConversations'  => App\Models\TblConversations::class,
        'TblDelayedMessages'  => App\Models\TblDelayedMessages::class,
        'TblDiscardedConversations'  => App\Models\TblDiscardedConversations::class,
        'TblFlaggedMessageDeductions'  => App\Models\TblFlaggedMessageDeductions::class,
        'TblFlaggedMessages'  => App\Models\TblFlaggedMessages::class,
        'TblFlaggedMessagesStatistic'  => App\Models\TblFlaggedMessagesStatistic::class,
        'TblGroups'  => App\Models\TblGroups::class,
        'TblIgnoredConversationLogs'  => App\Models\TblIgnoredConversationLogs::class,
        'TblInboundMessageAttachment'  => App\Models\TblInboundMessageAttachment::class,
        'TblLibraries'  => App\Models\TblLibraries::class,
        'TblLibrariesServices'  => App\Models\TblLibrariesServices::class,
        'TblLibraryMessages'  => App\Models\TblLibraryMessages::class,
        'TblLoggedInUsers'  => App\Models\TblLoggedInUsers::class,
        'TblMessageInterval'  => App\Models\TblMessageInterval::class,
        'TblMessages'  => App\Models\TblMessages::class,
        'TblMessagesQueue'  => App\Models\TblMessagesQueue::class,
        'TblOperatorResponseLogs'  => App\Models\TblOperatorResponseLogs::class,
        'TblOutboundMessageAttachments'  => App\Models\TblOutboundMessageAttachments::class,
        'TblOutboundMessageAttachmentsMod'  => App\Models\TblOutboundMessageAttachmentsMod::class,
        'TblOutboundQueue'  => App\Models\TblOutboundQueue::class,
        'TblPendingConversations'  => App\Models\TblPendingConversations::class,
        'TblPendingSubscriberBilling'  => App\Models\TblPendingSubscriberBilling::class,
        'TblPersonaFiles'  => App\Models\TblPersonaFiles::class,
        'TblPersonas'  => App\Models\TblPersonas::class,
        'TblProductionApiLogs'  => App\Models\TblProductionApiLogs::class,
        'TblRoles'  => App\Models\TblRoles::class,
        'TblRules'  => App\Models\TblRules::class,
        'TblServiceEmailNotification'  => App\Models\TblServiceEmailNotification::class,
        'TblServiceGroup'  => App\Models\TblServiceGroup::class,
        'TblServiceSubscriberLimit'  => App\Models\TblServiceSubscriberLimit::class,
        'TblServices'  => App\Models\TblServices::class,
        'TblSettings'  => App\Models\TblSettings::class,
        'TblStatistics'  => App\Models\TblStatistics::class,
        'TblStatisticsRealtime'  => App\Models\TblStatisticsRealtime::class,
        'TblSubscriberBilling'  => App\Models\TblSubscriberBilling::class,
        'TblSubscriberMessageLimit'  => App\Models\TblSubscriberMessageLimit::class,
        'TblSubscriberPersonaLimit'  => App\Models\TblSubscriberPersonaLimit::class,
        'TblSubscribers'  => App\Models\TblSubscribers::class,
        'TblTimedOutConversations'  => App\Models\TblTimedOutConversations::class,
        'TblUserActivities'  => App\Models\TblUserActivities::class,
        'TblUserConversationLogs'  => App\Models\TblUserConversationLogs::class,
        'TblUserLogs'  => App\Models\TblUserLogs::class,
        'TblUserService'  => App\Models\TblUserService::class,
        'TblUserSystemLogs'  => App\Models\TblUserSystemLogs::class,
        'TblUsers'  => App\Models\TblUsers::class,
        'TblWhitelist'  => App\Models\TblWhitelist::class,
        'UtlInboundRequestLimiter'  => App\Models\UtlInboundRequestLimiter::class,
        'UtlInboundRequests'  => App\Models\UtlInboundRequests::class,
        'UtlMigration'  => App\Models\UtlMigration::class,
        'UtlMigrationV1Status'  => App\Models\UtlMigrationV1Status::class,
        'UtlOperatorCounter'  => App\Models\UtlOperatorCounter::class,
        'UtlOutboundReceiver'  => App\Models\UtlOutboundReceiver::class,
        'UtlReportLock'  => App\Models\UtlReportLock::class,
        'UtlReportLogs'  => App\Models\UtlReportLogs::class,
        'UtlReportsTable'  => App\Models\UtlReportsTable::class,
        'UtlRequestLimiter'  => App\Models\UtlRequestLimiter::class,
        'UtlRoutine'  => App\Models\UtlRoutine::class,
        'UtlStatistics'  => App\Models\UtlStatistics::class,
        'UtlTestMessages'  => App\Models\UtlTestMessages::class,
        'TblMessagesToDiscard' => App\Models\TblMessagesToDiscard::class,
    ],

];
