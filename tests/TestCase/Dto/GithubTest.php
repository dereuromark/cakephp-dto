<?php

namespace CakeDto\Test\TestCase\Dto;

use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Core\Configure;
use Cake\TestSuite\TestCase;
use CakeDto\Command\DtoGenerateCommand;
use Sandbox\Dto\Github\PullRequestDto;
use TestApp\TestSuite\ConsoleOutput;

class GithubTest extends TestCase {

	/**
	 * @return void
	 */
	public function testMapping() {
		$file = TESTS . 'files' . DS . 'Github' . DS . 'demo_pr.json';

		$simulatedDataFromGitHubApi = json_decode(file_get_contents($file), true);

		Configure::write('CakeDto.strictTypes', true);

		$out = new ConsoleOutput();
		$err = new ConsoleOutput();
		$io = new ConsoleIo($out, $err);
		$command = $this->getMockBuilder(DtoGenerateCommand::class)->onlyMethods(['_getConfigPath', '_getSrcPath'])->getMock();

		$sandboxPluginPath = TESTS . 'test_app' . DS . 'plugins' . DS . 'Sandbox' . DS;

		$command->expects($this->any())->method('_getConfigPath')->willReturn($sandboxPluginPath . 'config' . DS);
		$command->expects($this->any())->method('_getSrcPath')->willReturn($sandboxPluginPath . 'src' . DS);

		$options = ['verbose' => true, 'plugin' => 'Sandbox', 'dry-run' => true]; // ['generate', '-v', '-p', 'Sandbox', '-d'];
		if (!empty($_SERVER['argv']) && in_array('--debug', $_SERVER['argv'], true)) {
			$options['dry-run'] = false;
		}
		$args = new Arguments([], $options, []);
		$result = $command->execute($args, $io);
		$this->assertSame(0, $result, 'Code: ' . $result . ' (expected 0, no change). Remove -d and re-run. ' . $out->output());

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
					 'htmlUrl' => 'https://github.com/octocat',
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
							 'htmlUrl' => 'https://github.com/octocat',
							 'type' => 'User',
						 ],
					 'repo' =>
						 [
							 'name' => 'Hello-World',
							 'htmlUrl' => 'https://github.com/octocat/Hello-World',
							 'private' => false,
							 'owner' =>
								 [
									 'login' => 'octocat',
									 'htmlUrl' => 'https://github.com/octocat',
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
							 'htmlUrl' => 'https://github.com/octocat',
							 'type' => 'User',
						 ],
					 'repo' =>
						 [
							 'name' => 'Hello-World',
							 'htmlUrl' => 'https://github.com/octocat/Hello-World',
							 'private' => false,
							 'owner' =>
								 [
									 'login' => 'octocat',
									 'htmlUrl' => 'https://github.com/octocat',
									 'type' => 'User',
								 ],
						 ],
				 ],
		];

		$this->assertSame($expected, $pullRequestDtoArray);

		Configure::write('CakeDto.strictTypes', false);
	}

}
