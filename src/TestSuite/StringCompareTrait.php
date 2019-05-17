<?php
namespace Dto\TestSuite;

/**
 * Compare a string to the contents of a file
 *
 * Implementing objects are expected to modify the `$_compareBasePath` property
 * before use.
 */
trait StringCompareTrait
{
    /**
     * The base path for output comparisons
     *
     * Must be initialized before use
     *
     * @var string
     */
    protected $_compareBasePath = '';

    /**
     * Update comparisons to match test changes
     *
     * Initialized with the env variable UPDATE_TEST_COMPARISON_FILES
     *
     * @var bool
     */
    protected $_updateComparisons;

    /**
     * Compare the result to the contents of the file
     *
     * @param string $path partial path to test comparison file
     * @param string $result test result as a string
     * @return void
     */
    public function assertSameAsFile($path, $result)
    {
        if (!file_exists($path)) {
            $path = $this->_compareBasePath . $path;
        }

        if ($this->_updateComparisons === null) {
            $this->_updateComparisons = (bool)getenv('UPDATE_TEST_COMPARISON_FILES');
        }

        if ($this->_updateComparisons) {
            file_put_contents($path, $result);
        }

        $expected = file_get_contents($path);
        $this->assertStringContainsString($expected, $result);
    }
}
