<?php

namespace DashboardBundle\Controller;

use DashboardBundle\Entity\Game;
use DashboardBundle\Form\Type\GameType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class GameController
 *
 * @package DashboardBundle\Controller
 */
class GameController extends Controller
{
    /**
     * @var Container
     */
    protected $container;

    /**
     * Homepage.
     *
     * @return array
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $gameManager = $this->container->get('game_dashboard.game.manager');
        $paginatedList = $gameManager->getPaginatedGameList($request->query->get('page', 1), 20);

        return ['pagination' => $paginatedList];
    }

    /**
     *
     * @Template()
     * @param Request $request
     * @param         $gameId
     *
     * @return array
     */
    public function startAction(Request $request, $gameId)
    {
        $gameManager = $this->container->get('game_dashboard.game.manager');
        $game = $gameManager->findGameById($gameId);
        if (!$game instanceof Game) {
            return $this->redirectToRoute('game_dashboard_game_list');
        }

        if ($game->isRunning()) {
            $request->getSession()->getFlashBag()->add('error', 'dashboard.game.isrunning');
            return $this->redirectToRoute('game_dashboard_game_start_list');
        }

//        if ($game->getStartType() === $game::GAME_START_TYPE_CONSOLE) {
//            $gameManager->startConsole($game);
//        } elseif ($game->getStartType() === $game::GAME_START_TYPE_KNOCK){
//            $gameManager->startKnock($game);
//        }

        $game->setIsRunning(true);
        $gameManager->updateGame($game);

        $request->getSession()->getFlashBag()->add('success', 'dashboard.game.started');

        return $this->redirectToRoute('game_dashboard_game_start_list');
    }

    /**
     *
     * @param Request $request
     *
     * @Template()
     * @return array
     */
    public function startListAction(Request $request)
    {
        $gameManager = $this->container->get('game_dashboard.game.manager');
        $paginatedList = $gameManager->getPaginatedGameList($request->query->get('page', 1), 10);

        return ['pagination' => $paginatedList];
    }

    /**
     *
     *
     * @return array
     */
    public function stopAction(Request $request, $gameId)
    {
        $gameManager = $this->container->get('game_dashboard.game.manager');
        $game = $gameManager->findGameById($gameId);
        if (!$game instanceof Game) {
            return $this->redirectToRoute('game_dashboard_game_list');
        }

        if (!$game->isRunning()) {
            $request->getSession()->getFlashBag()->add('error', 'dashboard.game.isnotrunning');
            return $this->redirectToRoute('game_dashboard_game_start_list');
        }

        $game->setIsRunning(false);
        $gameManager->updateGame($game);

        $request->getSession()->getFlashBag()->add('success', 'dashboard.game.stopped');

        return $this->redirectToRoute('game_dashboard_game_start_list');
    }

    /**
     *
     *
     * @return array
     * @Template()
     */
    public function createAction(Request $request)
    {
        $gameManager = $this->container->get('game_dashboard.game.manager');
        $game = $gameManager->createGame();
        $form = $this->createForm(GameType::class, $game);

        if ($request->isMethod('post')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $gameManager->updateGame($game);

                $data = $form->getData();
                if ($data->getLogo()) {
                    $filename = $gameManager->uploadNewPicture($data->getLogo(), $game->getId());

                    $gameManager->removeOldPicture($game->getLogo());
                    $game->setLogo($filename);
                    $gameManager->updateGame($game);
                }

                return $this->redirectToRoute('game_dashboard_game_list');
            }
        }

        return ['form' => $form->createView()];
    }

    /**
     * Edit Action.
     *
     * @param Request $request
     * @param string  $gameId
     *
     * @Template()
     * @return array
     */
    public function editAction(Request $request, $gameId)
    {
        $gameManager = $this->container->get('game_dashboard.game.manager');
        $game = $gameManager->findGameById($gameId);
        if (!$game instanceof Game) {
            return $this->redirectToRoute('game_dashboard_game_list');
        }

        $oldLogo = $game->getLogo();

        if ($oldLogo) {
            $logo = new File($this->getParameter('game_dashboard.file.path') . DIRECTORY_SEPARATOR . $oldLogo);
            $game->setLogo($logo);
        }

        $form = $this->createForm(GameType::class, $game);

        if ($request->isMethod('post')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $data = $form->getData();
                if ($data->getLogo()) {
                    $filename = $gameManager->uploadNewPicture($data->getLogo(), $game->getId());

                    $gameManager->removeOldPicture($oldLogo);
                    $game->setLogo($filename);
                } else {
                    $game->setLogo($oldLogo);
                }
                $gameManager->updateGame($game);

                return $this->redirectToRoute('game_dashboard_game_list');
            }
        }

        return array('form' => $form->createView());
    }
}
