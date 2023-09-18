<?php

/** @var \Laravel\Lumen\Routing\Router $router */

//TEST
$router->post('/test','Controller@test');
//END TEST

//Events List
$router->post('/common-events/get-all/{user_role}/{user_id}', 'CommonEventsController@getMyAllEvents');

//Notifications
$router->get('/notifications/actions/get-my-notifications/{id}','NotificationsController@getMyNotifications');
$router->get('/notifications/actions/get-all-my-notifications/{id}','NotificationsController@getAllMyNotifications');
$router->post('/notifications/actions/create-notifications','NotificationsController@createNotification');
$router->post('/notifications/actions/mark-all-as-read-notifications/{id}','NotificationsController@markAllAsSeen');
$router->post('/notifications/actions/mark-one-as-read-notifications/{id}','NotificationsController@markOneAsSeen');
$router->post('/notifications/actions/delete-one-notification/{id}','NotificationsController@deleteOneNotification');
$router->post('/notifications/actions/delete-all-notifications/{id}','NotificationsController@deleteAllNotifications');
$router->post('/notifications/actions/delete-requests','NotificationsController@deleteRequests');

//Doctor
$router->post('/new-trainer-doctor-meeting', 'MeetingController@newTrainerDoctorMeeting');
$router->post('/new-client-doctor-meeting', 'MeetingController@newClientDoctorMeeting');
$router->post('/new-trainer-client-meeting', 'MeetingController@newTrainerClientMeeting');

$router->get('/trainer-client-meeting-trainer/{id}', 'MeetingController@getTrainerClientMeetingsTrainer');
$router->get('/trainer-client-meeting-client/{id}', 'MeetingController@getTrainerClientMeetingsClient');

$router->get('/doctor-meetings/my-meetings/{user_role}/{user_id}','MeetingController@getMyDoctorMeetings');

$router->post('/doctor-meeting-accept/{id}', 'MeetingController@doctorMeetingAccept');
$router->post('/doctor-meeting-reject/{id}', 'MeetingController@doctorMeetingReject');
$router->post('/doctor-meeting-finish/{id}', 'MeetingController@doctorMeetingFinish');

$router->get('/doctor-meetings/home/{doctor_id}','MeetingController@getDoctorDoctorMeeting');
$router->get('/doctor-meetings/pending/{doctor_id}','MeetingController@getDoctorPendingDoctorMeeting');
$router->get('/doctor-meetings/approved/{doctor_id}','MeetingController@getDoctorApprovedDoctorMeeting');
$router->get('/doctor-meetings/history/{doctor_id}','MeetingController@getDoctorMeetingsHistory');

$router->post('/chats/new-chat', 'ChatsController@createChat');
$router->post('/chats/get-chat', 'ChatsController@getChatData');
$router->post('/chats/edit-chat', 'ChatsController@editChat');
$router->post('/chats/delete-chat', 'ChatsController@deleteChat');
$router->post('/chats/add-remove-chat-client', 'ChatsController@addOrRemoveClient');

$router->post('/chats/chat-notifications/new-message', 'ChatsController@sendMessageNotification');
$router->get('/chats/trainer-my-chats/{owner_id}', 'ChatsController@getTrainerMyChats');
$router->get('/chats/client-my-chats/{user_id}', 'ChatsController@getClientMyChats');

$router->post('/todos/actions/get', 'TODOController@getTodos');
$router->post('/todos/actions/add', 'TODOController@addTodo');
$router->post('/todos/actions/complete', 'TODOController@completeTodo');
$router->post('/todos/actions/delete', 'TODOController@deleteTodo');

//meetings
$router->group(['middleware' => 'gateway:api'], function () use ($router) {

});

$router->post('/new-client-therapy-hold', [ 'middleware' => 'auth.check:meeting,hold_user', 'uses' => 'Therapy_Meetings_Controller@holdUser']);
$router->post('/new-client-therapy-meeting', [ 'middleware' => 'auth.check:meeting,add', 'uses' => 'Therapy_Meetings_Controller@add']);
$router->post('/reserved-times', [ 'middleware' => 'auth.check:meeting,reserved_time', 'uses' => 'Therapy_Meetings_Controller@reserved_time']);
$router->get('/my-meetings', [ 'middleware' => 'auth.check:meeting,my_meetings', 'uses' => 'Therapy_Meetings_Controller@my_meetings']);

$router->post('/voice-call/actions/invoke', 'NotificationsController@callInvoker');
$router->post('/voice-call/actions/rtcToken', 'RtcTokenGenerator@Generate');
