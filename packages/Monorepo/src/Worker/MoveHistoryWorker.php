<?php declare(strict_types=1);

namespace Symplify\Monorepo\Worker;

use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Process\Process;

final class MoveHistoryWorker
{
    /**
     * @var string
     */
    private const GIT_MV_WITH_HISTORY_BASH_FILE = __DIR__ . '/../bash/git-mv-with-history.sh';

    /**
     * @var SymfonyStyle
     */
    private $symfonyStyle;

    public function __construct(SymfonyStyle $symfonyStyle)
    {
        $this->symfonyStyle = $symfonyStyle;
    }

    /**
     * This will:
     * - add complete history to new files
     * - delete old files
     *
     * Empty directories will remain
     */
    public function prependHistoryToNewPackageFiles(Finder $finder, string $monorepoDirectory, string $packageSubdirectory): void
    {
        $processInput = $this->createGitMoveWithHistoryProcessInput($finder, $monorepoDirectory, $packageSubdirectory);

        $moveWithHistoryProcess = new Process($processInput, $monorepoDirectory);
        $moveWithHistoryProcess->run();

        if ($moveWithHistoryProcess->isSuccessful()) {
            $this->symfonyStyle->note(trim($moveWithHistoryProcess->getOutput()));
        } else {
            $this->symfonyStyle->error(trim($moveWithHistoryProcess->getErrorOutput()));
        }
    }

    /**
     * @return mixed[]
     */
    private function createGitMoveWithHistoryProcessInput(
        Finder $finder,
        string $monorepoDirectory,
        string $packageSubdirectory
    ): array {
        $processInput = [self::GIT_MV_WITH_HISTORY_BASH_FILE];

        foreach ($finder as $fileInfo) {
            $processInput[] = sprintf(
                '%s=%s',
                $monorepoDirectory . '/' . $fileInfo->getRelativePathname(),
                $monorepoDirectory . '/' . $packageSubdirectory . '/' . $fileInfo->getRelativePathname()
            );
        }

        return $processInput;
    }
}
