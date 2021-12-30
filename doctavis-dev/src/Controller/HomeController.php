<?php


namespace App\Controller;


use App\Entity\Distance;
use App\Entity\Pathology;
use App\Entity\Practitioner;
use App\Entity\Speciality;
use App\Entity\User;
use App\Form\HomeType;
use App\Service\GeocoderService;
use CiroVargas\GoogleDistanceMatrix\GoogleDistanceMatrix;
use Doctrine\ORM\EntityManagerInterface;
use Geocoder\Query\GeocodeQuery;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Phelium\Component\GeoApiFr;
use Phelium\Component\GeoApiFr\Communes;


class HomeController extends AbstractController
{
    private $em;

    /**
     * HomeController constructor.
     */
    public function __construct( EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/", name="home")
     */
    public function index(Request $request)
    {
        $session = $this->container->get('session');
        $pathologies = $this->em->getRepository(Pathology::class)->getGroupedPathologies();
        $mainSpecialities = $this->em->getRepository(Speciality::class)->getMains();
        $form = $this->createForm(HomeType::class,null, [
            'pathologies' => $pathologies,
            'specialities' => $mainSpecialities,
        ]);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $data  = $form->getData();
            $flag = true;
            $location  =$data['location'];
            $radius  = (int)$data['rayon'];
            $pathologies = $data['pathologies'];
            $speciality = $data['specialities'];
            if ($form->getClickedButton() === $form->get('partialSubmit')) {
                $flag = false;
            }
            $results = [];
            $origin = $this->getCoordinates($location)->first()->getCoordinates();
            $postalCodes = $this->em->getRepository(Practitioner::class)->getPostalCodes($speciality, $flag);
            foreach ($postalCodes as $postalCode) {
                $distanceObject = $this->em->getRepository(Distance::class)->findByVille((int)$location,$postalCode);
                if(null === $distanceObject) {
                    $destistination = $this->getCoordinates($postalCode)->first()->getCoordinates();
                    $value = $this->getDistanceM($origin->getLatitude(), $origin->getLongitude(),$destistination->getLatitude(), $destistination->getLongitude() );
                    $value = round($value/1000);
                    /** @var $distance */
                    $distance = new Distance();
                    $distance->setOrigin((int)$location);
                    $distance->setDestination((int)$postalCode);
                    $distance->setDistance($value);
                    $this->em->persist($distance);

                    if ($value < $radius) {
                        $results [] = $postalCode;
                    }

                } else {
                    $distanceValue = $distanceObject->getDistance();
                    if ($distanceValue < $radius) {
                        $results [] = $postalCode;
                    }
                }

            }
            $results = array_unique($results);
            $this->em->flush();
            $data['postalCodes'] = $results;

            if ($form->getClickedButton() === $form->get('submit')) {
                $data['pathologies'] = $pathologies;
                $practitioners = $this->em->getRepository(Practitioner::class)->getByPostalAndPathology($results, $pathologies);

                $session->set('searchData', $data);
                $session->set('searchCount', count($practitioners));

                return $this->redirectToRoute('search_by_criterions');
            } else {
                unset($data["pathologies"], $data["specialities"]);
                $session->set('searchData', $data);
                return $this->redirectToRoute('search_speciality');
            }
        }
        return $this->render('index.html.twig',
        [
            'form' => $form->createView()
        ]);
    }

    /**
     * @param $address
     * @param $practitioner
     * @return \CiroVargas\GoogleDistanceMatrix\Response\GoogleDistanceMatrixResponse
     */
    public function getDistance($address, $postalCode){

            $distanceMatrix = new GoogleDistanceMatrix($_ENV['MAPS_DISTANCE_KEY']);
            $distance = $distanceMatrix
                ->setLanguage('fr')
                ->addOrigin($address)
                ->setMode(GoogleDistanceMatrix::MODE_DRIVING)
            ;
            $distance =  $distance->addDestination($postalCode);
//            try{
//                $response = $distance->sendRequest();
//            }catch (\Exception $e){
//                dd($distance->sendRequest()->getStatus());
//                throw new \Exception($e->getMessage() .$postalCode );
//            }
        return $distance->sendRequest();
    }

    public function getDistanceM($lat1, $lng1, $lat2, $lng2) {
        $earth_radius = 6378137;   // Terre = sphère de 6378km de rayon
        $rlo1 = deg2rad($lng1);
        $rla1 = deg2rad($lat1);
        $rlo2 = deg2rad($lng2);
        $rla2 = deg2rad($lat2);
        $dlo = ($rlo2 - $rlo1) / 2;
        $dla = ($rla2 - $rla1) / 2;
        $a = (sin($dla) * sin($dla)) + cos($rla1) * cos($rla2) * (sin($dlo) * sin(
                    $dlo));
        $d = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return ($earth_radius * $d);
    }

    public function getCoordinates($ville){
        $httpClient = new \Http\Adapter\Guzzle6\Client();
        $provider = new \Geocoder\Provider\GoogleMaps\GoogleMaps($httpClient, 'France', $_ENV['MAPS_DISTANCE_KEY']);
        $geocoder = new \Geocoder\StatefulGeocoder($provider, 'fr');
        $response = $geocoder->geocodeQuery(GeocodeQuery::create($ville. ', France'));

        return $response;
    }

    /**
     * @Route("/vision", name="our_vision")
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function ourVision(Request $request) {
        return $this->render('vision/index.html.twig');
    }

    /**
     * @Route("/welcome", name="welcome")
     *
     * @param Request $request
     *
     */
    public function welcome(Request $request) {
        return $this->render('welcome.html.twig');
    }
}