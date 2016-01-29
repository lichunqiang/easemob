<?php
require 'bootstrap.php';

$user = $easemob->user;

//var_dump($user->all());
var_dump($user->register(['username' => 333, 'password' => 12313123, 'nickname' => 'test']));
//var_dump($user->one(1));
//var_dump($user->updateNickname(1, 'testUpdate'));
//var_dump($user->one(1));
//var_dump($user->remove(1));
//var_dump($user->resetPassword(1, '12313123'));


//friend test
//var_dump($user->register([
//    ['username' => 'user1', 'password' => 'user1'],
//    ['username' => 'user2', 'password' => 'user2'],
//]));

//var_dump($user->addFriend('user1', 'user2'));
//var_dump($user->friends('user1'));
//var_dump($user->removeFriend('user1', 'user2'));


//var_dump($user->blocks('user1'));
//var_dump($user->block('user1', ['user2']));
//var_dump($user->unblock('user1', 'user2'));

//var_dump($user->isOnline('user1'));

//var_dump($user->disable('user1'));
//var_dump($user->one('user1'));
//var_dump($user->enable('user1'));
//var_dump($user->one('user1'));

//var_dump($user->disconnect('user1'));


//var_dump($user->offlineMsgCount('user1'));

$message = $easemob->message;

//$text = new \light\Easemob\Message\Text(['msg' => 'hello']);
//$text->to = 'user1';
//var_dump($message->send($text));


//send image
//$file = $easemob->file;
//$result = $file->upload(__DIR__ . '/tests/Fixtures/girl.jpg');
//$image = new \light\Easemob\Message\Image([
//    'url' => $file->url($result['uuid']),
//    'filename' => 'girl.jpg',
//    'secret' => $result['share-secret'],
//    'size' => ['width' => 480, 'height' => 720]
//]);
//$image->to = 'user1';
//var_dump($message->send($image));


//cmd
//$cmd = new \light\Easemob\Message\Cmd(['action' => '1111']);
//$cmd->to = 'user1';
//var_dump($message->send($cmd));
