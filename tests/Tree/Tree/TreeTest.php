<?php
/*
 * This file is part of Tree.
 *
 * (c) 2013 Nicolò Martini
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Tree\Test\Tree;

use Tree\Node\Node;
use Tree\Tree\Tree;

/**
 * Unit tests for class FirstClass
 */
class TreeTest extends \PHPUnit_Framework_TestCase
{
	private $basicTree;

    public function setUp()
    {
		$this->basicTree = new Tree();

		$a = new Node('a');
		$c = new Node('c');
		$a->addChild($c);
		$a->addChild(new Node('d'));

		$b = new Node('b');
		$b->addChild(new Node('e'));
		$b->addChild(new Node('f'));

		$this->basicTree->addNode($a);
		$this->basicTree->addNode($b);

		$this->basicTree->addNode(new Node('g'));

		$h = new Node('h');
		$this->basicTree->addNode($h, $c);
	}

	public function testAddSimpleNode()
	{
		$tree = new Tree();

		$tree->addNode(new Node());
		$tree->addNode(new Node());
		$tree->addNode((new Node())->setId(10));

		$nodes = $tree->getNodes();

		$this->assertCount(3, $nodes);
		$this->assertEquals(10, $nodes[10]->getId());
	}

	public function testRootSettings()
	{
		$tree = new Tree();
		$this->assertInstanceOf(Node::class, $tree->getTreeRoot());

		$root = new Node();
		$tree2 = new Tree($root);
		$this->assertEquals($root, $tree2->getTreeRoot());
		$this->assertEquals(0, $tree2->getTreeRoot()->getId());

		$root2 = new Node();
		$root2->setId(5);
		$tree3 = new Tree($root2);
		$this->assertEquals($root2, $tree3->getTreeRoot());
		$this->assertEquals(0, $tree3->getTreeRoot()->getId());
	}

	public function testRemoveNode()
	{
		$tree = new Tree();

		$node1 = new Node();
		$node2 = (new Node())->setId(10);

		$tree->addNode($node1);
		$tree->addNode($node2);
		$this->assertCount(2, $tree->getNodes());
		$this->assertNotEmpty($tree->getNodes()[10]);

		$removed = $tree->removeNode($node2);

		$this->assertNotFalse($removed);

		$this->assertCount(1, $tree->getNodes());
		$this->assertFalse(isset($tree->getNodes()[10]));
	}

	public function testRemoveBranch()
	{
		$tree = new Tree();

		$a = new Node('a');
		$c = new Node('c');
		$a->addChild($c);
		$a->addChild($d = new Node('d'));

		$b = new Node('b');
		$b->addChild($e = new Node('e'));
		$b->addChild($f = new Node('f'));

		$tree->addNode($a);
		$tree->addNode($b);

		$tree->addNode($g = new Node('g'));

		$h = new Node('h');
		$tree->addNode($h, $c);

		$tree->removeNode($a);

		$this->assertEquals([$b, $e, $f, $g], $tree->getNodesSorted());
	}


	public function testSorted()
	{
		$tree = new Tree();

		$a = new Node('a');
		$c = new Node('c');
		$a->addChild($c);
		$a->addChild($d = new Node('d'));

		$b = new Node('b');
		$b->addChild($e = new Node('e'));
		$b->addChild($f = new Node('f'));

		$tree->addNode($a);
		$tree->addNode($b);

		$tree->addNode($g = new Node('g'));

		$h = new Node('h');
		$tree->addNode($h, $c);

		$this->assertEquals([$a, $c, $h, $d, $b, $e, $f, $g], $tree->getNodesSorted());
	}

	public function testBasicArray()
	{
		$tree = new Tree();

		$a = new Node('a');
		$c = new Node('c');
		$a->addChild($c);
		$a->addChild($d = new Node('d'));

		$b = new Node('b');
		$b->addChild($e = new Node('e'));
		$b->addChild($f = new Node('f'));

		$tree->addNode($a);
		$tree->addNode($b);

		$tree->addNode($g = new Node('g'));

		$h = new Node('h');
		$tree->addNode($h, $c);

		$this->assertEquals([
			1 => $a,
			2 => $c,
			3 => $d,
			4 => $b,
			5 => $e,
			6 => $f,
			7 => $g,
			8 => $h
		], $tree->getNodes());
	}

}