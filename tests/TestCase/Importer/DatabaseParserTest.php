<?php
declare(strict_types=1);

namespace CakeDto\Test\TestCase\Importer;

use Cake\Database\Connection;
use Cake\Database\Driver\Sqlite;
use Cake\Datasource\ConnectionManager;
use Cake\TestSuite\TestCase;
use CakeDto\Importer\DatabaseParser;

class DatabaseParserTest extends TestCase {

	protected DatabaseParser $parser;

	/**
	 * @return void
	 */
	public function setUp(): void {
		parent::setUp();

		ConnectionManager::setConfig('test_dto', [
			'className' => Connection::class,
			'driver' => Sqlite::class,
			'database' => ':memory:',
		]);

		$connection = ConnectionManager::get('test_dto');
		/** @var \Cake\Database\Connection $connection */
		$connection->execute('CREATE TABLE articles (
			id INTEGER PRIMARY KEY AUTOINCREMENT,
			title VARCHAR(255) NOT NULL,
			body TEXT,
			author_id INTEGER NOT NULL,
			is_published BOOLEAN DEFAULT 0,
			rating REAL,
			published_date DATE,
			created DATETIME,
			modified DATETIME
		)');
		$connection->execute('CREATE TABLE user_profiles (
			id INTEGER PRIMARY KEY AUTOINCREMENT,
			user_id INTEGER NOT NULL,
			bio TEXT,
			website VARCHAR(255)
		)');

		$this->parser = new DatabaseParser();
	}

	/**
	 * @return void
	 */
	public function tearDown(): void {
		parent::tearDown();

		ConnectionManager::drop('test_dto');
	}

	/**
	 * @return void
	 */
	public function testListTables(): void {
		$tables = $this->parser->listTables('test_dto');

		$this->assertContains('articles', $tables);
		$this->assertContains('user_profiles', $tables);
	}

	/**
	 * @return void
	 */
	public function testParse(): void {
		$result = $this->parser->parse(['articles'], 'test_dto');

		$this->assertArrayHasKey('Article', $result);

		$fields = $result['Article'];
		$this->assertArrayHasKey('id', $fields);
		$this->assertArrayHasKey('title', $fields);
		$this->assertArrayHasKey('body', $fields);
		$this->assertArrayHasKey('authorId', $fields);
		$this->assertArrayHasKey('created', $fields);
		$this->assertArrayHasKey('modified', $fields);
	}

	/**
	 * @return void
	 */
	public function testParseTypeMapping(): void {
		$result = $this->parser->parse(['articles'], 'test_dto');
		$fields = $result['Article'];

		$this->assertSame('int', $fields['id']['type']);
		$this->assertSame('string', $fields['title']['type']);
		$this->assertSame('string', $fields['body']['type']);
		$this->assertSame('int', $fields['authorId']['type']);
	}

	/**
	 * @return void
	 */
	public function testParseRequiredFields(): void {
		$result = $this->parser->parse(['articles'], 'test_dto');
		$fields = $result['Article'];

		// title is NOT NULL without default and not PK/autoincrement => required
		$this->assertTrue($fields['title']['required']);

		// author_id is NOT NULL without default => required
		$this->assertTrue($fields['authorId']['required']);

		// id is autoincrement PK => not required
		$this->assertArrayNotHasKey('required', $fields['id']);

		// body is nullable => not required
		$this->assertArrayNotHasKey('required', $fields['body']);
	}

	/**
	 * @return void
	 */
	public function testParseMultipleTables(): void {
		$result = $this->parser->parse(['articles', 'user_profiles'], 'test_dto');

		$this->assertArrayHasKey('Article', $result);
		$this->assertArrayHasKey('UserProfile', $result);

		$this->assertArrayHasKey('userId', $result['UserProfile']);
		$this->assertArrayHasKey('bio', $result['UserProfile']);
	}

	/**
	 * @return void
	 */
	public function testTableNameConversion(): void {
		$result = $this->parser->parse(['user_profiles'], 'test_dto');

		$this->assertArrayHasKey('UserProfile', $result);
		$this->assertArrayNotHasKey('user_profiles', $result);
	}

}
