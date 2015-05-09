<?php
namespace Model;
class Order extends Base
{
    /**
     * expected input keys
     */
    const SESSION_ID = 'session_id';
    const IP = 'ip';
    const EMAIL = 'email';
    const PHONE = 'phone';
    const STATUS = 'status';
    const ORDER_NUMBER = 'order_number';

    /*
     * status
     */
    const REJECT = 'reject';
    const SUCCESS = 'success';

    /**
     * relationship
     */

    const HAS = 'HAS';

    public function getCountConnection()
    {

    }

    /**
     * @see Base::getOutput()
     */
    public function getOutput()
    {
        $session = $this->getOrCreateNode($this->data[static::SESSION_ID]);
        $ip = $this->getOrCreateNode($this->data[static::IP]);
        $email = $this->getOrCreateNode($this->data[static::EMAIL]);
        $phone = $this->getOrCreateNode($this->data[static::PHONE]);
        $orderNum = $this->getOrCreateNode($this->data[static::ORDER_NUMBER]);

        if (
            isset($this->data[static::STATUS]) &&
            ($this->data[static::STATUS] == static::REJECT || $this->data[static::STATUS == static::SUCCESS])
        ) {
            // set relationship
            $this->setRelationship($session, $orderNum, static::HAS);
            $this->setRelationship($ip, $orderNum, static::HAS);
            $this->setRelationship($email, $orderNum, static::HAS);
            $this->setRelationship($phone, $orderNum, static::HAS);
            $orderNum->setProperty(static::STATUS, $this->data[static::STATUS])->save();
        }
    }
}
