<?php
/**
 * PHPUnit
 *
 * Copyright (c) 2010-2013, Sebastian Bergmann <sebastian@phpunit.de>.
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 *   * Redistributions of source code must retain the above copyright
 *     notice, this list of conditions and the following disclaimer.
 *
 *   * Redistributions in binary form must reproduce the above copyright
 *     notice, this list of conditions and the following disclaimer in
 *     the documentation and/or other materials provided with the
 *     distribution.
 *
 *   * Neither the name of Sebastian Bergmann nor the names of his
 *     contributors may be used to endorse or promote products derived
 *     from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @package    PHPUnit_Selenium
 * @author     Giorgio Sironi <info@giorgiosironi.com>
 * @copyright  2010-2013 Sebastian Bergmann <sebastian@phpunit.de>
 * @license    http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @link       http://www.phpunit.de/
 */

/**
 * Tests for PHPUnit_Extensions_SeleniumTestCase.
 *
 * @package    PHPUnit_Selenium
 * @author     Giorgio Sironi <info@giorgiosironi.com>
 * @copyright  2010-2013 Sebastian Bergmann <sebastian@phpunit.de>
 * @license    http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @link       http://www.phpunit.de/
 */
class Extensions_Selenium2TestCaseFailuresTest extends Tests_Selenium2TestCase_BaseTestCase
{
    public function testInexistentCommandCausesTheTestToFail()
    {
        $this->expectException(BadMethodCallException::class);
        $this->inexistentSessionCommand();
    }

    public function testExceptionsAreReThrownOnNotSuccessfulTests()
    {
        $this->expectException(DomainException::class);
        $this->onNotSuccessfulTest(new DomainException);
    }

    public function testInexistentElementCausesTheTestToFail()
    {
        $this->expectException(RuntimeException::class);
        $this->url('html/test_open.html');
        $this->byId('notExistent');
    }

    public function testStaleElementsCannotBeAccessed()
    {
        $this->url('html/test_element_selection.html');
        $div = $this->byId('theDivId');
        $this->url('html/test_element_selection.html');
        try {
            $div->text();
            $this->fail('The element shouldn\'t be accessible.');
        } catch (RuntimeException $e) {
            $this->assertContains('http://seleniumhq.org/exceptions/stale_element_reference.html', $e->getMessage());
        }
    }

    public function testSelectObjectsCanOnlyBeCreatedOverSelectTags()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->url('html/test_element_selection.html');
        $div = $this->byId('theDivId');
        $select = $this->select($div);
    }
}
