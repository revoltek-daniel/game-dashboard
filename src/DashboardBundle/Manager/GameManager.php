<?php
namespace DashboardBundle\Manager;

use DashboardBundle\Entity\Game;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use Knp\Component\Pager\Paginator;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class GameManager
 *
 * @package ${NAMESPACE}
 */
class GameManager
{
    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var Paginator
     */
    protected $paginator;

    /**
     * @var array
     */
    protected $repository;

    /**
     * @var  string
     */
    protected $gameClass;

    /**
     * @var array
     */
    protected $allowedFiletypes = array('image/jpeg', 'image/png');

    /**
     * @param EntityManager $entityManager
     * @param Paginator     $paginator
     * @param string        $gameClass
     * @param string        $filepath
     */
    public function __construct(EntityManager $entityManager, Paginator $paginator, $gameClass, $filepath)
    {
        $this->entityManager = $entityManager;
        $this->paginator     = $paginator;
        $this->gameClass     = $gameClass;
        $this->filepath      = $filepath;
    }

    /**
     * get paginated entry list.
     *
     * @param int $page
     * @param int $limit
     *
     * @return \Knp\Component\Pager\Pagination\PaginationInterface
     */
    public function getPaginatedGameList($page = 1, $limit = 10)
    {
        $dql   = "SELECT g FROM DashboardBundle:Game g";
        $query = $this->entityManager->createQuery($dql);
        $pagination = $this->paginator->paginate(
            $query,
            $page,
            $limit
        );

        return $pagination;
    }

    /**
     * Update Entry.
     *
     * @param Game $game
     * @param bool $flushOm
     *
     * @return $this
     */
    public function updateGame(Game $game, $flushOm = true)
    {
        $this->entityManager->persist($game);

        if ($flushOm) {
            $this->entityManager->flush();
        }

        return $this;
    }

    /**
     * create new entry.
     *
     * @return Game
     */
    public function createGame()
    {
        return new $this->gameClass();
    }

    /**
     * Find Entry by id.
     *
     * @param string $entryId
     *
     * @return Game
     */
    public function findGameById($entryId)
    {
        return $this->getRepository($this->gameClass)->findOneBy(array('id' => $entryId));
    }

    /**
     * get Repository.
     *
     * @param string $class
     *
     * @return EntityRepository
     */
    protected function getRepository($class)
    {
        if (!isset($this->repository[$class])) {
            $this->repository[$class] = $this->entityManager->getRepository($class);
        }

        return $this->repository[$class];
    }

    /**
     * Upload file.
     *
     * @param UploadedFile $file
     * @param string       $gameId
     *
     * @return string
     * @throws \Exception
     */
    public function uploadNewPicture(UploadedFile $file, $gameId)
    {
        if (!in_array($file->getMimeType(), $this->allowedFiletypes)) {
            throw new \Exception('tubemesh.user.edit.picture.invalid');
        }

        $filepath = $this->filepath;
        $filename = sha1(rand(0, 50) . $gameId . rand(0, 50) . $file->getClientOriginalName() . rand(0, 50)) . '.' . $file->getClientOriginalExtension();

        $file->move($filepath, $filename);

        $this->resizePictures($filepath, $filename);

        return $filename;
    }

    /**
     * Remove Old File.
     *
     * @param $oldFile
     *
     * @return void
     */
    public function removeOldPicture($oldFile)
    {
        $file = $this->filepath . DIRECTORY_SEPARATOR . $oldFile;
        if ($oldFile && file_exists($file)) {
            unlink($file);
            $thumb = $this->filepath . DIRECTORY_SEPARATOR . 'thumb' . DIRECTORY_SEPARATOR . $oldFile;
            if (file_exists($thumb)) {
                unlink($thumb);
            }
        }
    }

    /**
     * Resize picture and save thumb.
     *
     * @param string $filepath
     * @param string $filename
     *
     * @return void
     */
    protected function resizePictures($filepath, $filename)
    {
        $fullpath = $filepath . DIRECTORY_SEPARATOR . $filename;

        $thumbPath = $filepath . DIRECTORY_SEPARATOR . 'thumb';
        if (!is_dir($thumbPath)) {
            mkdir($thumbPath);
        }

        $imagine = new Imagine();
        $image = $imagine->open($fullpath);
        $image->resize(new Box(35, 35))
        ->save($thumbPath . DIRECTORY_SEPARATOR . $filename);
    }
}
