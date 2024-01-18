<?php

namespace App\Controller;

use App\Entity\Candidat;
use App\Entity\Choix;
use App\Form\SearchType;
use App\Repository\CandidatRepository;
use App\Repository\CategorieCandidatsRepository;
use App\Repository\ChoixRepository;
use App\Repository\SourateRepository;
use Knp\Component\Pager\PaginatorInterface;

use Proxies\__CG__\App\Entity\CategorieCandidats;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    private array $cand = [];
    #[Route('/', name: 'app_index')]
    public function index(CandidatRepository $candidatRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $searchForm = $this->createForm(SearchType::class);
        $searchForm->handleRequest($request);

        $queryBuilder = $candidatRepository->createQueryBuilder('c');

        if ($searchForm->isSubmitted() && $searchForm->isValid()) {
            $searchTerm = $searchForm->get('search')->getData();

            // Modify the queryBuilder to include your search logic
            if ($searchTerm) {
                $queryBuilder
                    ->innerJoin('c.categorie', 's')
                    ->innerJoin('c.ecoledeProvenance', 'e')
                    ->orWhere('c.mom LIKE :searchTerm')
                    ->orWhere('c.prenom LIKE :searchTerm')
                    ->orWhere('c.numeroCandidat LIKE :searchTerm')
                    ->orWhere('c.age LIKE :searchTerm')
                    ->orWhere('s.name LIKE :searchTerm')
                    ->orWhere('c.localite LIKE :searchTerm')
                    ->orWhere('e.nom LIKE :searchTerm')
                    ->setParameter('searchTerm', "%$searchTerm%");
            }
        }

        $candidats = $queryBuilder->getQuery()->getResult();
        shuffle( $candidats);
        // Paginate the results using KnpPaginatorBundle
        $pagination = $paginator->paginate(
            $candidats,
            $request->query->getInt('page', 1),
            25 // Number of items per page
        );

        return $this->render('index.html.twig', [
            'pagination' => $pagination,
            'searchForm' => $searchForm->createView(),
        ]);
    }
//    public function index(CandidatRepository $candidatRepository,PaginatorInterface $paginator,Request $request): Response
//    {
//        $candidats=$candidatRepository->findAll();
//        $pagination=$paginator->paginate(
//            $candidats,
//            $request->query->getInt('page',1),
//            10
//        );
//        return $this->render('index.html.twig', [
//            'candidats'=>$candidats,
//            'pagination'=>$pagination
//
//
//        ]);
//    }

    #[Route('/questions-series/{id}/candidats', name: 'app_questions_serie')]
    public function selectSerieQuestion(Candidat $candidat,ChoixRepository $choixRepository ,CandidatRepository $candidatRepository, PaginatorInterface $paginator, Request $request): Response

    {
        $debutSourate = (int)$candidat->getCategorie()->getDebutSourate();
        $nbCandidatParCategorie = $candidatRepository->count(['categorie'=>$candidat->getCategorie()]);

        $choix = array();
        // Ajout d'éléments au tableau avec des indices dynamiques
        for ($i = 0; $i < $nbCandidatParCategorie+3; $i++) {
            $choix[$i][$candidat->getCategorie()->getId()] = $i;
        }
        $dejaChoisis = $choixRepository->findBy(['categorie'=>$candidat->getCategorie()->getId()]);
        foreach ($dejaChoisis  as $item ){
            unset($choix[$item->getChoiceIndex()]);
        }
//        dd(  $choix[9][2]);
        //   unset($choix[9][2]);
//        dd(  $choix);
        $numbers = range($debutSourate, 114);
        shuffle($numbers);
        $Sourate = array_slice($numbers, 1, 3);
        return $this->render('choix_serie_question.html.twig', [
            'choix'=> $choix,
            'candidat' => $candidat,
            'sourate' => $Sourate
        ]);
    }

    #[Route('/questions-list/{id}/candidats/{position}/sourate', name: 'app_questions_liste')]
    public function questions(Candidat $candidat, SourateRepository $sourateRepository, ChoixRepository $choixRepository, Request $request): Response

    {
        $position =  (int) $request->get('position');

        $choix = $choixRepository->findBy(['candidat'=>$candidat->getId(),'categorie'=>$candidat->getCategorie()->getId()]);

        if(!$choix){
            $index = new Choix();
            $index->setChoiceIndex( $position);
            $index->setCategorie($candidat->getCategorie()->getId());
            $index->setCandidat($candidat->getId());
   $choixRepository->save( $index,true);

        }


//        if(!(($position >= 1) && ($position <= 3))){
//            return $this->redirectToRoute('app_questions_serie', ['id'=>$candidat->getId()], Response::HTTP_SEE_OTHER);
//        }
        $nbreDePartie = 3;

        // debut de Sourate de la categorie de l'eleve
        $debutSourate = (int)$candidat->getCategorie()->getDebutSourate();

        // intervalle de Sourates de la categorie de l'eleve
        $intervalleSourate = range($debutSourate, 114);

        // Récupérer les sourates non lues de l'intervalle et les mélanger
//        $listeSourate = $sourateRepository->findBy(['surahnumber' =>  $intervalleSourate, 'isReaded' => false]);
//        shuffle($listeSourate);

        // Calculez la taille de chaque partie en utilisant la division entière
//        $taille = floor(count($listeSourate) / 3);
        $tailleSourate = floor(count($intervalleSourate) /  $nbreDePartie);
        $resteSourate = count($intervalleSourate) %  $nbreDePartie;
        $listeSourateSelectionne = [];
        $offset = 0;
        for ($i = 0; $i < $nbreDePartie; $i++) {
            $length = $tailleSourate + ($resteSourate > 0 ? 1 : 0);
            $partie[] = array_slice($intervalleSourate, $offset, $length);
            $offset += $length;
            $resteSourate = max(0, $resteSourate - 1);

            $listeSourate[]  = $sourateRepository->findBy(['surahnumber' =>   $partie[$i] , 'isReaded' => false]);
//            $listeSourateSelectionne[]  = $partie[$i][array_rand($partie[$i])];
            $listeSourateSelectionne[]  =  $listeSourate[$i][array_rand( $listeSourate[$i])];
        }

        $listeSourate1 = $sourateRepository->findBy(['surahnumber' =>   $partie[0] , 'isReaded' => false]);
        $listeSourate2 = $sourateRepository->findBy(['surahnumber' =>   $partie[1] , 'isReaded' => false]);
        $listeSourate3 = $sourateRepository->findBy(['surahnumber' =>   $partie[2] , 'isReaded' => false]);
//dd($partie, $listeSourate,$listeSourateSelectionne);
        //        dd( $partie[0][array_rand($partie[0])],array_rand($partie[0]),$partie[1],array_rand($partie[1]),$partie[2],array_rand($partie[2]));
        //        $partie2 =
//        $partie3 =




        // Sélectionner un sous-ensemble de 3 questions parmi les sourates non lues
//        $listeQuestions =  array_slice( $partieSeclionnee,0,3);

        $questionsTest=[];
        $nbVersetsAlire = 15;
        // Récupérer les 15 premières verset a partir de la sourate sélectionnée
      //dd($partie);

//            foreach (   $listeSourateSelectionne  as $item){
              //    dd($partie,$item[$i]);
        for ($i = 0; $i < $nbreDePartie; $i++) {
                $questionsTest[]= $sourateRepository->createQueryBuilder('s')
                    ->where('s.id >= :id')
                    ->andWhere('s.surahnumber IN (:sura)')
                    ->setParameter('id',  $listeSourateSelectionne[$i]->getId())
                    ->setParameter('sura', $partie[$i])
                    ->orderBy('s.id', 'ASC')
                    ->setMaxResults($nbVersetsAlire)
                    ->getQuery()
                    ->getResult();
          }

     //   shuffle($questionsTest);
        $listeQuestions =  array_slice( $questionsTest,0,3);
     //   dd($partie,$questionsTest);


//        dd(  $listeQuestions, $questionsTest );

        return $this->render('serie_question.html.twig', [
            'candidat' => $candidat,
            'versets' => $questionsTest,


        ]);
    }


}
