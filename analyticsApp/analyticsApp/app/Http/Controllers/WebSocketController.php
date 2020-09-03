<?php

namespace App\Http\Controllers;

use App\Helpers\WebSocketHelper;
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;

class WebSocketController extends Controller implements MessageComponentInterface
{
    /**
     * When a new connection is opened it will be passed to this method
     * @param  ConnectionInterface $conn The socket/connection that just connected to your application
     * @throws \Exception
     */
    function onOpen(ConnectionInterface $conn)
    {
        echo "connection opened \n";
    }

    /**
     * This is called before or after a socket is closed (depends on how it's closed).  SendMessage to $conn will not result in an error if it has already been closed.
     * @param  ConnectionInterface $conn The socket/connection that is closing/closed
     * @throws \Exception
     */
    function onClose(ConnectionInterface $conn)
    {
        echo "connection closed \n";
    }

    /**
     * If there is an error with one of the sockets, or somewhere in the application where an Exception is thrown,
     * the Exception is sent back down the stack, handled by the Server and bubbled back up the application through this method
     * @param  ConnectionInterface $conn
     * @param  \Exception $e
     * @throws \Exception
     */
    function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo 'error occured: ' . $e->getMessage() . "\n";
    }

    /**
     * Triggered when a client sends data through the socket
     * @param  \Ratchet\ConnectionInterface $from The socket/connection that sent the message to your application
     * @param  string $msg The message received
     * @throws \Exception
     */
    function onMessage(ConnectionInterface $from, $msg)
    {
        $data = json_decode($msg, true);
        $action = $data['action'];

        echo "message: '$msg' \n";

        $functionMapping = [
            'connect' => static function ($data) {
                WebSocketHelper::connected($data);
            },
            'disconnect' => static function ($data) {
                WebSocketHelper::disconnected($data);
            },
            'language' => static function ($data) {
                WebSocketHelper::setLanguage($data);
            },
            'age' => static function ($data) {
                WebSocketHelper::setAge($data);
            },
            'productClick' => static function ($data) {
                WebSocketHelper::productClicked($data);
            },
            'rate' => static function ($data) {
                WebSocketHelper::setRating($data);
            },
            'viewTime' => static function ($data) {
                WebSocketHelper::setProductViewTime($data);
            },

        ];

        $this->runOverFunctionMappingArray($functionMapping, $action, $data);

        if ($action !== 'productClick') {
            WebSocketHelper::registerEvent($data);
        }
    }

    /**
     * @param array $functionMapping
     * @param $action
     * @param array $data
     */
    private function runOverFunctionMappingArray(array $functionMapping, $action, array $data): void
    {
        foreach ($functionMapping as $k => $v) {
            if ($k === $action) {
                $v($data);
                return;
            }
        }
        abort(404);
    }
}
