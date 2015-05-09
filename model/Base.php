<?php
namespace Model;
use Everyman\Neo4j\Client;
use Everyman\Neo4j\Index\NodeIndex;

/**
 * Class Base
 * @package Model
 */
abstract class Base
{
    /**
     * @var Client|null
     */
    protected $client = null;

    /**
     * @var array
     */
    protected $data = array();

    /**
     * construct the Base
     */
    public function __construct($data = array())
    {
        $this->client = new Client();
        $this->actors = new NodeIndex($this->client, 'actors');
        $this->data = $data;
    }

    /**
     * make a new node
     * @param array $input
     * @return int
     * @throws \Everyman\Neo4j\Exception
     */
    public function makeNode($input = array())
    {
        $node = $this->client->makeNode()->setProperty('name', $input['name'])->save();
        return $node->getId();
    }

    /**
     * set relationship of two nodes
     * @param $nodeA
     * @param $nodeB
     * @param $name
     * @return mixed
     */
    public function setRelationship($nodeA, $nodeB, $name)
    {
        $date = date('G:i:s d-m-Y');
        return $nodeA->relateTo($nodeB, $name)
            ->setProperty('date', $date)
            ->save();
    }
}
