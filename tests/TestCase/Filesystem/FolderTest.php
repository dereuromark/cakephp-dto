<?php
declare(strict_types=1);

namespace CakeDto\Test\TestCase\Filesystem;

use Cake\TestSuite\TestCase;
use CakeDto\Filesystem\Folder;

/**
 * @uses \CakeDto\Filesystem\Folder
 */
class FolderTest extends TestCase {

	/**
	 * @var string
	 */
	protected string $testPath;

	/**
	 * @return void
	 */
	protected function setUp(): void {
		parent::setUp();

		$this->testPath = TMP . 'folder_test_' . uniqid();
	}

	/**
	 * @return void
	 */
	protected function tearDown(): void {
		if (is_dir($this->testPath)) {
			$folder = new Folder($this->testPath);
			$folder->delete();
		}

		parent::tearDown();
	}

	/**
	 * Test constructor with default path.
	 *
	 * @return void
	 */
	public function testConstructorDefault(): void {
		$folder = new Folder();

		$this->assertSame(TMP, $folder->pwd());
	}

	/**
	 * Test constructor with path.
	 *
	 * @return void
	 */
	public function testConstructorWithPath(): void {
		$folder = new Folder(TMP);

		$this->assertSame(TMP, $folder->pwd());
	}

	/**
	 * Test constructor with create.
	 *
	 * @return void
	 */
	public function testConstructorWithCreate(): void {
		$folder = new Folder($this->testPath, true);

		$this->assertTrue(is_dir($this->testPath));
		$this->assertSame($this->testPath, $folder->pwd());
	}

	/**
	 * Test constructor with custom mode.
	 *
	 * @return void
	 */
	public function testConstructorWithMode(): void {
		$folder = new Folder($this->testPath, true, 0700);

		$this->assertTrue(is_dir($this->testPath));
		$this->assertSame(0700, $folder->mode);
	}

	/**
	 * Test pwd returns current path.
	 *
	 * @return void
	 */
	public function testPwd(): void {
		$folder = new Folder(TMP);

		$this->assertSame(TMP, $folder->pwd());
	}

	/**
	 * Test cd changes directory.
	 *
	 * @return void
	 */
	public function testCd(): void {
		$folder = new Folder();
		$result = $folder->cd(TMP);

		$this->assertSame(TMP, $result);
		$this->assertSame(TMP, $folder->pwd());
	}

	/**
	 * Test cd returns false for non-existent directory.
	 *
	 * @return void
	 */
	public function testCdNonExistent(): void {
		$folder = new Folder();
		$result = $folder->cd('/non/existent/path');

		$this->assertFalse($result);
	}

	/**
	 * Test isAbsolute with absolute paths.
	 *
	 * @return void
	 */
	public function testIsAbsoluteUnix(): void {
		$this->assertTrue(Folder::isAbsolute('/var/www'));
		$this->assertTrue(Folder::isAbsolute('/'));
	}

	/**
	 * Test isAbsolute with Windows paths.
	 *
	 * @return void
	 */
	public function testIsAbsoluteWindows(): void {
		$this->assertTrue(Folder::isAbsolute('C:\\Users'));
		$this->assertTrue(Folder::isAbsolute('D:\\'));
		$this->assertTrue(Folder::isAbsolute('\\\\network\\share'));
	}

	/**
	 * Test isAbsolute with relative paths.
	 *
	 * @return void
	 */
	public function testIsAbsoluteRelative(): void {
		$this->assertFalse(Folder::isAbsolute('relative/path'));
		$this->assertFalse(Folder::isAbsolute(''));
	}

	/**
	 * Test slashTerm adds trailing slash.
	 *
	 * @return void
	 */
	public function testSlashTerm(): void {
		$result = Folder::slashTerm('/var/www');

		$this->assertSame('/var/www' . DIRECTORY_SEPARATOR, $result);
	}

	/**
	 * Test slashTerm with already terminated path.
	 *
	 * @return void
	 */
	public function testSlashTermAlreadyTerminated(): void {
		$result = Folder::slashTerm('/var/www/');

		$this->assertSame('/var/www/', $result);
	}

	/**
	 * Test isSlashTerm with slash terminated paths.
	 *
	 * @return void
	 */
	public function testIsSlashTermTrue(): void {
		$this->assertTrue(Folder::isSlashTerm('/var/www/'));
		$this->assertTrue(Folder::isSlashTerm('C:\\Users\\'));
	}

	/**
	 * Test isSlashTerm with non-terminated paths.
	 *
	 * @return void
	 */
	public function testIsSlashTermFalse(): void {
		$this->assertFalse(Folder::isSlashTerm('/var/www'));
		$this->assertFalse(Folder::isSlashTerm('C:\\Users'));
	}

	/**
	 * Test create creates directory.
	 *
	 * @return void
	 */
	public function testCreate(): void {
		$folder = new Folder();
		$result = $folder->create($this->testPath);

		$this->assertTrue($result);
		$this->assertTrue(is_dir($this->testPath));
	}

	/**
	 * Test create with existing directory.
	 *
	 * @return void
	 */
	public function testCreateExisting(): void {
		$folder = new Folder();
		$folder->create($this->testPath);

		$result = $folder->create($this->testPath);
		$this->assertTrue($result);
	}

	/**
	 * Test create with empty path.
	 *
	 * @return void
	 */
	public function testCreateEmpty(): void {
		$folder = new Folder();
		$result = $folder->create('');

		$this->assertTrue($result);
	}

	/**
	 * Test delete removes directory.
	 *
	 * @return void
	 */
	public function testDelete(): void {
		$folder = new Folder($this->testPath, true);

		// Create some files and subdirectories
		mkdir($this->testPath . '/subdir');
		file_put_contents($this->testPath . '/test.txt', 'test');
		file_put_contents($this->testPath . '/subdir/test2.txt', 'test2');

		$result = $folder->delete();

		$this->assertTrue($result);
		$this->assertFalse(is_dir($this->testPath));
	}

	/**
	 * Test delete with null path uses current path.
	 *
	 * @return void
	 */
	public function testDeleteWithNullPath(): void {
		$folder = new Folder($this->testPath, true);
		$result = $folder->delete(null);

		$this->assertTrue($result);
	}

	/**
	 * Test delete returns false with no path.
	 *
	 * @return void
	 */
	public function testDeleteNoPath(): void {
		$folder = new Folder();
		$folder->path = null;
		$result = $folder->delete(null);

		$this->assertFalse($result);
	}

}
