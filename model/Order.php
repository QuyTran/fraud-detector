<?php
namespace Model;
use Everyman\Neo4j\Exception;
use Everyman\Neo4j\Node;

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
        try {
            $session = $this->getOrCreateNode($this->data[static::SESSION_ID], static::SESSION_ID);
            $ip = $this->getOrCreateNode($this->data[static::IP], static::IP);
            $email = $this->getOrCreateNode($this->data[static::EMAIL], static::EMAIL);
            $phone = $this->getOrCreateNode($this->data[static::PHONE], static::PHONE);
            $orderNum = $this->getOrCreateNode($this->data[static::ORDER_NUMBER], static::ORDER_NUMBER);

            if (
                isset($this->data[static::STATUS]) &&
                ($this->data[static::STATUS] == static::REJECT || $this->data[static::STATUS] == static::SUCCESS)
            ) {
                /**
                 * @var Node $orderNum
                 */
                $currentStatus = $orderNum->getProperty(static::STATUS);
                if (empty($currentStatus)) {
                    $this->setRelationship($ip, $orderNum, static::HAS);
                    $this->setRelationship($email, $orderNum, static::HAS);
                    $this->setRelationship($phone, $orderNum, static::HAS);
                    $this->setRelationship($session, $orderNum, static::HAS);
                    $orderNum->setProperty(static::STATUS, $this->data[static::STATUS])->save();
                }
            }

            $data = $this->getFraudRingList();
            return json_encode($data, true);
        } catch (Exception $e) {
            echo json_encode($e);
        }
    }

    /**
     * get the fraud ring
     * @return \Everyman\Neo4j\Query\ResultSet
     */
    public function getFraudRingList()
    {
        $result = array();

        if (empty($this->data)) {
            return $result;
        }

        $clone = $this->data;
        unset($clone[static::STATUS]);
        unset($clone[static::ORDER_NUMBER]);

        $string =  'MATCH (identifiers)-[]->(orders)
                    WITH  count(orders) as size,
                        identifiers.`type` as type,
                        orders.`status` as status,
                        identifiers.`name` as name
                    WHERE size > 1 and status = "reject" and name in ["' . implode('","', $clone) . '"]
                    RETURN type, size
                    ORDER BY size DESC';
        echo $string;die;
        $data = $this->query($string);

        foreach ($data as $value) {
            $result[] = iterator_to_array($value);
        }

        return $result;
    }
}
