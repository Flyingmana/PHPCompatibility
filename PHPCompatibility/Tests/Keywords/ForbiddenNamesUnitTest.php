<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2020 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Tests\Keywords;

use PHPCompatibility\Tests\BaseSniffTest;

/**
 * Test the ForbiddenNames sniff.
 *
 * @group forbiddenNames
 * @group keywords
 *
 * @covers \PHPCompatibility\Sniffs\Keywords\ForbiddenNamesSniff
 *
 * @since 5.5
 */
class ForbiddenNamesUnitTest extends BaseSniffTest
{

    /**
     * testForbiddenNames
     *
     * @dataProvider usecaseProvider
     *
     * @param string $usecase Partial filename of the test case file covering
     *                        a specific use case.
     *
     * @return void
     */
    public function testForbiddenNames($usecase)
    {
        // These use cases were generated using the PHP script
        // `generate-forbidden-names-test-files` in sniff-examples.
        $filename = __DIR__ . "/ForbiddenNames/$usecase.inc";

        // Set the testVersion to the highest PHP version encountered in the
        // \PHPCompatibility\Sniffs\Keywords\ForbiddenNamesSniff::$invalidNames
        // list to catch all errors.
        $file = $this->sniffFile($filename, '5.5');

        $this->assertNoViolation($file, 2);

        $lineCount = \count(\file($filename));
        // Each line of the use case files (starting at line 3) exhibits an
        // error.
        for ($i = 3; $i < $lineCount; $i++) {
            $this->assertError($file, $i, 'Function name, class name, namespace name or constant name can not be reserved keyword');
        }
    }

    /**
     * Provides use cases to test with each keyword.
     *
     * @return array
     */
    public function usecaseProvider()
    {
        return [
            ['namespace'],
            ['nested-namespace'],
            ['use'],
            ['use-as'],
            ['class'],
            ['class-extends'],
            ['class-use-trait'],
            ['class-use-trait-const'],
            ['class-use-trait-function'],
            ['class-use-trait-alias-method'],
            ['class-use-trait-alias-public-method'],
            ['class-use-trait-alias-protected-method'],
            ['class-use-trait-alias-private-method'],
            ['class-use-trait-alias-final-method'],
            ['trait'],
            ['function-declare'],
            ['function-declare-reference'],
            ['method-declare'],
            ['const'],
            ['class-const'],
            ['define'],
            ['interface'],
            ['interface-extends'],
        ];
    }


    /**
     * testCorrectUsageOfKeywords
     *
     * @return void
     */
    public function testCorrectUsageOfKeywords()
    {
        $file = $this->sniffFile(__FILE__, '5.5');
        $this->assertNoViolation($file);
    }


    /**
     * testNotForbiddenInPHP7
     *
     * @dataProvider usecaseProviderPHP7
     *
     * @param string $usecase Partial filename of the test case file covering
     *                        a specific use case.
     *
     * @return void
     */
    public function testNotForbiddenInPHP7($usecase)
    {
        $file = $this->sniffFile(__DIR__ . "/ForbiddenNames/$usecase.inc", '7.0');
        $this->assertNoViolation($file);
    }

    /**
     * Provides use cases to test with each keyword.
     *
     * @return array
     */
    public function usecaseProviderPHP7()
    {
        return [
            ['method-declare'],
            ['class-const'],
        ];
    }


    /**
     * testNoFalsePositives
     *
     * @return void
     */
    public function testNoFalsePositives()
    {
        $file = $this->sniffFile(__DIR__ . '/ForbiddenNames/class.inc', '4.4'); // Version number specific to the line being tested.
        $this->assertNoViolation($file, 3);
    }
}
