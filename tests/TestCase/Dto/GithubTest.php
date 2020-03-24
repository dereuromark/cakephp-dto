<?php

namespace CakeDto\Test\TestCase\Dto;

use CakeDto\Shell\DtoShell;
use Cake\Console\ConsoleIo;
use Cake\Event\EventManager;
use Cake\TestSuite\TestCase;
use Cake\TwigView\Event\ExtensionsListener;
use Cake\TwigView\Event\TokenParsersListener;
use Sandbox\Dto\Github\PullRequestDto;
use TestApp\TestSuite\ConsoleOutput;

class GithubTest extends TestCase {

	/**
	 * @var \CakeDto\Shell\DtoShell|\PHPUnit\Framework\MockObject\MockObject
	 */
	protected $shell;

	/**
	 * @return void
	 */
	public function testMapping() {
		$this->skipIf(version_compare(PHP_VERSION, '7.1') < 0, 'Requires PHP 7.1+');

		$file = TESTS . 'files' . DS . 'Github' . DS . 'demo_pr.json';

		$simulatedDataFromGitHubApi = json_decode(file_get_contents($file), true);

		EventManager::instance()->on(new ExtensionsListener());
		EventManager::instance()->on(new TokenParsersListener());

		$this->out = new ConsoleOutput();
		$this->err = new ConsoleOutput();
		$io = new ConsoleIo($this->out, $this->err);
		$this->shell = $this->getMockBuilder(DtoShell::class)->setMethods(['_getConfigPath', '_getSrcPath'])->setConstructorArgs([$io])->getMock();

		$sandboxPluginPath = TESTS . 'test_app' . DS . 'plugins' . DS . 'Sandbox' . DS;

		$this->shell->expects($this->any())->method('_getConfigPath')->willReturn($sandboxPluginPath . 'config' . DS);
		$this->shell->expects($this->any())->method('_getSrcPath')->willReturn($sandboxPluginPath . 'src' . DS);

		$result = $this->shell->runCommand(['generate', '-v', '-p', 'Sandbox', '-d']);
		$this->assertSame(0, $result, 'Code: ' . $result . ' (expected 0, no change). Remove -d and re-run. ' . $this->out->output());

		$pullRequestDto = PullRequestDto::create($simulatedDataFromGitHubApi, true, PullRequestDto::TYPE_UNDERSCORED);

		$pullRequestDtoArray = $pullRequestDto->toArray();

		$expected = [
			'url' => 'https://api.github.com/repos/octocat/Hello-World/pulls/1347',
			'number' => 1347,
			'state' => 'open',
			'title' => 'new-feature',
			'body' => 'Please pull these awesome changes',
			'user' =>
				 [
					'login' => 'octocat',
					'html_url' => 'https://github.com/octocat',
					'type' => 'User',
				],
			'createdAt' => '2011-01-26T19:01:12Z',
			'labels' =>
				 [
					'bug' =>
						 [
							'name' => 'bug',
							'color' => 'f29513',
						],
				],
			'head' =>
				 [
					'ref' => 'new-topic',
					'sha' => '6dcb09b5b57875f334f61aebed695e2e4193db5e',
					'user' =>
						 [
							'login' => 'octocat',
							'html_url' => 'https://github.com/octocat',
							'type' => 'User',
						],
					'repo' =>
						 [
							'name' => 'Hello-World',
							'html_url' => 'https://github.com/octocat/Hello-World',
							'private' => false,
							'owner' =>
								 [
									'login' => 'octocat',
									'html_url' => 'https://github.com/octocat',
									'type' => 'User',
								],
						],
				],
			'base' =>
				 [
					'ref' => 'master',
					'sha' => '6dcb09b5b57875f334f61aebed695e2e4193db5e',
					'user' =>
						 [
							'login' => 'octocat',
							'html_url' => 'https://github.com/octocat',
							'type' => 'User',
						],
					'repo' =>
						 [
							'name' => 'Hello-World',
							'html_url' => 'https://github.com/octocat/Hello-World',
							'private' => false,
							'owner' =>
								 [
									'login' => 'octocat',
									'html_url' => 'https://github.com/octocat',
									'type' => 'User',
								],
						],
				],
		];

		$this->assertSame($expected, $pullRequestDtoArray);
	}

}
