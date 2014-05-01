<?php

class TM_Core_Model_Module_MessageLogger
{
    protected $_messages = array(
        'errors'  => array(),
        'notices' => array(),
        'success' => array()
    );

    /**
     * @param string $type
     * @param mixed $error array or string with error message
     * <pre>
     *  message required
     *  trace   optional
     * </pre>
     */
    public function addError($type, $error)
    {
        $this->_messages['errors'][$type][] = $error;
    }

    public function getErrors()
    {
        return $this->_messages['errors'];
    }

    /**
     * @param string $type
     * @param string $notice
     */
    public function addNotice($type, $notice)
    {
        $this->_messages['notices'][$type][] = $notice;
    }

    public function getNotices()
    {
        return $this->_messages['notices'];
    }

    /**
     * @param string $type
     * @param string $notice
     */
    public function addSuccess($type, $success)
    {
        $this->_messages['success'][$type][] = $notice;
    }

    public function getSuccess()
    {
        return $this->_messages['success'];
    }
}
