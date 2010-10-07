<?php

class SampleTest extends TestCase
{

	public function testTitle()
	{
		$a = new Sample;
		$a->title = 'foo';
		$this->assertEquals('foo', $a->title);
	}

}
