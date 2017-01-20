<?php

namespace PAO\Http\Session\Handler;

/**
 * NullSessionHandler.
 *
 * Can be used in unit testing or in a situations where persisted sessions are not desired.
 *
 * @author Drak <drak@zikula.org>
 */
class NullSessionHandler implements \SessionHandlerInterface
{
    /**
     * {@inheritdoc}
     */
    public function open($save_path, $name)
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function close()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function read($session_id)
    {
        return '';
    }

    /**
     * {@inheritdoc}
     */
    public function write($session_id, $session_data)
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function destroy($session_id)
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function gc($maxlifetime)
    {
        return true;
    }
}
