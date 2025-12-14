<?php
declare(strict_types=1);

namespace CakeDto\Filesystem;

use Exception;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

/**
 * Folder structure browser, lists folders and files.
 * Provides an Object interface for Common directory related tasks.
 *
 * @internal
 */
class Folder {

	/**
	 * Path to Folder.
	 */
	public ?string $path = null;

	/**
	 * Mode to be used on create. Does nothing on windows platforms.
	 */
	public int $mode = 0755;

	/**
	 * Constructor.
	 *
	 * @param string|null $path Path to folder
	 * @param bool $create Create folder if not found
	 * @param int|null $mode Mode (CHMOD) to apply to created folder, false to ignore
	 */
	public function __construct(?string $path = null, bool $create = false, ?int $mode = null) {
		if (empty($path)) {
			$path = TMP;
		}
		if ($mode) {
			$this->mode = $mode;
		}

		if (!file_exists($path) && $create === true) {
			$this->create($path, $this->mode);
		}
		if (!static::isAbsolute($path)) {
			$path = realpath($path);
		}
		if (!empty($path)) {
			$this->cd($path);
		}
	}

	/**
	 * Return current path.
	 *
	 * @return string|null Current path
	 */
	public function pwd(): ?string {
		return $this->path;
	}

	/**
	 * Change directory to $path.
	 *
	 * @param string $path Path to the directory to change to
	 * @return string|false The new path. Returns false on failure
	 */
	public function cd(string $path): string|false {
		if (is_dir($path)) {
			return $this->path = $path;
		}

		return false;
	}

	/**
	 * Returns true if given $path is an absolute path.
	 *
	 * @param string $path Path to check
	 * @return bool true if path is absolute.
	 */
	public static function isAbsolute(string $path): bool {
		if (empty($path)) {
			return false;
		}

		return $path[0] === '/' ||
			preg_match('/^[A-Z]:\\\\/i', $path) ||
			substr($path, 0, 2) === '\\\\';
	}

	/**
	 * Returns $path with added terminating slash.
	 *
	 * @param string $path Path to check
	 * @return string Path with ending slash
	 */
	public static function slashTerm(string $path): string {
		if (static::isSlashTerm($path)) {
			return $path;
		}

		return $path . DIRECTORY_SEPARATOR;
	}

	/**
	 * Returns true if given $path ends in a slash.
	 *
	 * @param string $path Path to check
	 * @return bool true if path ends with slash, false otherwise
	 */
	public static function isSlashTerm(string $path): bool {
		$lastChar = $path[strlen($path) - 1];

		return $lastChar === '/' || $lastChar === '\\';
	}

	/**
	 * Create a directory structure recursively.
	 *
	 * @param string $pathname The directory structure to create.
	 * @param int|null $mode octal value 0755
	 * @return bool Returns TRUE on success, FALSE on failure
	 */
	public function create(string $pathname, ?int $mode = null): bool {
		if (is_dir($pathname) || empty($pathname)) {
			return true;
		}

		if (!$mode) {
			$mode = $this->mode;
		}

		$old = umask(0);
		$result = mkdir($pathname, $mode, true);
		umask($old);

		return $result;
	}

	/**
	 * Recursively Remove directories if the system allows.
	 *
	 * @param string|null $path Path of directory to delete
	 * @return bool Success
	 */
	public function delete(?string $path = null): bool {
		if (!$path) {
			$path = $this->pwd();
		}
		if (!$path) {
			return false;
		}
		$path = static::slashTerm($path);
		if (is_dir($path)) {
			try {
				$directory = new RecursiveDirectoryIterator($path, RecursiveDirectoryIterator::CURRENT_AS_SELF);
				$iterator = new RecursiveIteratorIterator($directory, RecursiveIteratorIterator::CHILD_FIRST);
			} catch (Exception $e) {
				return false;
			}

			foreach ($iterator as $item) {
				$filePath = $item->getPathname();
				if ($item->isFile() || $item->isLink()) {
					// phpcs:ignore
					@unlink($filePath);
				} elseif ($item->isDir() && !$item->isDot()) {
					// phpcs:ignore
					@rmdir($filePath);
				}
			}

			unset($directory, $iterator);

			$path = rtrim($path, DIRECTORY_SEPARATOR);
			// phpcs:ignore
			@rmdir($path);
		}

		return true;
	}

}
