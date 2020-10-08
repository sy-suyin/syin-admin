<?php
/**
 * 测试方法 - 断言
 *
 * @version 1.0.0
 */

//----------------------------------------------------------------------

namespace syin;

// 断言
class Assert{

	/**
	 * 断言a和b是否相等，相等则测试用例通过
	 * 
	 * @param string $msg 测试失败时打印的信息
	 */
	public function assertEqual($a, $b, $msg = ''){

	}

	/**
	 * 断言a和b是否相等，不相等则测试用例通过
	 */
	public function assertNotEqual($a, $b, $msg = ''){

	}

	
	/**
	 * 断言x是否True，是True则测试用例通过。
	 */
	public function assertTrue($x, $msg = ''){

	}
	
	/**
	 * 断言x是否False，是False则测试用例通过
	 */
	public function assertFalse($x, $msg = ''){

	}
	
	/**
	 * 断言a是否是b，是则测试用例通过
	 */
	public function assertIs($a, $b, $msg = ''){

	}

	/**
	 * 断言a是否是b，不是则测试用例通过
	 */
	public function assertNotIs($a, $b, $msg = ''){

	}

	/**
	 * 断言a是否在b中，在b中则测试用例通过
	 */
	public function assertIn($a, $b, $msg = ''){

	}

	
	/**
	 * 断言a是否在b中，不在b中则测试用例通过
	 */
	public function assertNotIn($a, $b, $msg = ''){

	}

	
	/**
	 * 断言a是是b的一个实例，是则测试用例通过
	 */
	public function assertIsInstance($a, $b, $msg = ''){

	}
	
	/**
	 * 断言a是是b的一个实例，不是则测试用例通过
	 */
	public function assertNotIsInstance($a, $b, $msg = ''){

	}
}