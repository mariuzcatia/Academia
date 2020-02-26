<?php
namespace Tuiter\Services;

require _DIR_ .'/../../vendor/autoload.php';


use MongoDB\Operation\Find;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Exchange\AMQPExchangeType;
use PhpAmqpLib\Message\AMQPMessage;
use Tuiter\Services\UserService;

class ReshardingMongo {

    private $oldCollections; 

    public function reshardingUsers($oldCollections){
        $this->oldCollections = $oldCollections;
        $connection = new AMQPStreamConnection('localhost', '5672', 'guest', 'guest');
        $channel = $connection->channel();

        $channel->queue_declare('users', false, true, false, false);

        foreach ($oldCollections as $value) {
            $data = $value->find([]);
            foreach ($data as $dataOut) {

                $messageBody = json_encode($dataOut);
                $message = new AMQPMessage($messageBody, array('content_type' => 'text/plain', 'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT));
                $channel->basic_publish($message, '', 'users');

            }
        }
        $channel->close();
        $connection->close();
    }

    public function reshardingUsers2($newCollections){
        foreach ($this->oldCollections as $value) {
            $value->deleteMany([]);
        }

        $us = new UserService($newCollections);
        $connection = new AMQPStreamConnection('localhost', '5672', 'guest', 'guest');
        $channel = $connection->channel();

        $channel->queue_declare('users', false, true, false, false);

        /**
         * @param \PhpAmqpLib\Message\AMQPMessage $message
         */
        $callback = function ($message) use ($us){
            echo "\n--------\n";
            $user = json_decode($message->body, true);
            $us->register($user['userId'], $user['name'], $user['password']);
            echo "\n--------\n";

            $message->delivery_info['channel']->basic_ack($message->delivery_info['delivery_tag']);

        };

        $channel->basic_consume('users', '', false, false, false, false, $callback);

      
        while ($channel ->is_consuming()) {
            $channel->wait();
        }
    }
}