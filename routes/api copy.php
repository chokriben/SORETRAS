<?php

use App\Http\Controllers\ApiController;
use App\Http\Controllers\PageFrontController;
use App\Http\Controllers\AbonneController;
use App\Http\Controllers\AbonnePrixController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EvenementFrontController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\ActualiteController;
use App\Http\Controllers\ActualiteFrontController;
use App\Http\Controllers\EvenementController;
use App\Http\Controllers\SliderController;
use App\Http\Controllers\AppelDoffreController;
use App\Http\Controllers\AppelDoffreFrontController;
use App\Http\Controllers\AuthcController;
use App\Http\Controllers\AuthpController;
use App\Http\Controllers\AuthrController;
use App\Http\Controllers\CategorieController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CircuitController;
use App\Http\Controllers\CommandeController;
use App\Http\Controllers\CommunicationMediaController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ContactFrontController;
use App\Http\Controllers\DemandeAbonnementController;
use App\Http\Controllers\EmailsNewsLetterController;
use App\Http\Controllers\EtablissementController;
use App\Http\Controllers\EtablissementFrontController;
use App\Http\Controllers\LigneController;
use App\Http\Controllers\PresentationController;
use App\Http\Controllers\RecrutementController;
use App\Http\Controllers\SecuriteController;
use App\Http\Controllers\GallerieController;
use App\Http\Controllers\GareController;
use App\Http\Controllers\GouvernoratController;
use App\Http\Controllers\HoraireController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\LigneFrontController;
use App\Http\Controllers\LigneFrontstation;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\MonerisController;
use App\Http\Controllers\ParentModelController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PresentationFrontController;
use App\Http\Controllers\QualiteFrontController;
use App\Http\Controllers\RecrutementFrontController;
use App\Http\Controllers\SecuriteFrontController;
use App\Http\Controllers\StationController;
use App\Http\Controllers\StationFrontController;
use App\Http\Controllers\TarifController;
use App\Http\Controllers\TypeAbonneController;
use App\Http\Controllers\TypeAbonnementController;
use App\Http\Controllers\TypeEtablissementController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VilleController;
use App\Http\Controllers\VisiteurController;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('/getAllLangs', [LanguageController::class, 'getAllLangs']);
Route::get('/getCurrentLang', [LanguageController::class, 'getCurrentLang']);

// ------------------------------------(AUTH/REGISTER)-----------------------------------------

Route::post('/admin/login', [AuthController::class, 'login']);
Route::group(
  ["prefix" => "admin"],
  function () {

    Route::post('/register', [AuthController::class, 'register']);
    //----------------------------auth parent------------------------//
    Route::post('/loginp', [AuthpController::class, 'loginp']);
  }
);

// routes events
Route::get('/evenementss', [EvenementFrontController::class, 'index']);
Route::get('/evenement', [EvenementFrontController::class, 'show']);
Route::get('/selectparents/{id}', [ParentModelController::class, 'select_parent']);


Route::get('/settingsclient', [SettingController::class, 'index']);
Route::get('/setting/show/{id}', [SettingController::class, 'show']);
Route::get('/page/{id}', [PageFrontController::class, 'show']);
Route::post('/paiement', [MonerisController::class, 'createSession']);
Route::get('/fetchdata', [EtablissementController::class, 'fetchAndDisplayAPIResult']);
//route media
Route::get('/photo_front/{id}', [MediaControllerfront::class, 'index']);
Route::get('/video_front/{id}', [MediaControllerfront::class, 'index']);





//************************************(ADMIN)********************************************** */
Route::middleware(['web'])->group(function () {

  Route::get('/token', [ApiController::class, 'getToken']);
});

// Protected routes---------------------------------------------------------------------------
Route::group(["prefix" => "admin", 'middleware' => ['auth:sanctum']], function () {
  Route::post('/register', [AuthController::class, 'register']);
  Route::post('/logout', [AuthController::class, 'logout']);
  Route::post('/logoutp', [AuthpController::class, 'logoutp']);
  //routes users
  Route::get('/users', [UserController::class, 'index']);
  Route::get('/listus', [UserController::class, 'list']);
  Route::get('/user/show/{id} ', [UserController::class, 'show']);
  Route::post('/user/add ', [UserController::class, 'store']);
  Route::put('/user/update/{id}', [UserController::class, 'update']);
  Route::delete('/user/{id}', [UserController::class, 'destroy']);



  //routes Actualites
  Route::get('/actualites', [ActualiteController::class, 'index']);
  Route::get('/actualite/show/{id} ', [ActualiteController::class, 'show']);
  Route::post('/actualite/add ', [ActualiteController::class, 'store']);
  Route::put('/actualite/update/{id}', [ActualiteController::class, 'update']);
  Route::delete('/actualite/{id}', [ActualiteController::class, 'destroy']);

  //routes presentations
  Route::get('/presentations', [PresentationController::class, 'index']);
  Route::get('/presentation/show/{id} ', [PresentationController::class, 'show']);
  Route::post('/presentation/add ', [PresentationController::class, 'store']);
  Route::put('/presentation/update/{id}', [PresentationController::class, 'update']);
  Route::delete('/presentation/{id}', [PresentationController::class, 'destroy']);
  // routes horaire
  Route::get('/horaires', [HoraireController::class, 'index']);
  Route::get('/horaire/show/{id} ', [HoraireController::class, 'show']);
  Route::post('/horaire/add', [HoraireController::class, 'store']);
  Route::put('/horaire/update/{id}', [HoraireController::class, 'update']);
  Route::delete('/horaire/{id}', [HoraireController::class, 'destroy']);

  //routes slider
  Route::get('/sliders', [SliderController::class, 'index']);
  Route::get('/slider/show/{id} ', [SliderController::class, 'show']);
  Route::post('/slider/add', [SliderController::class, 'store']);
  Route::put('/slider/update/{id}', [SliderController::class, 'update']);
  Route::delete('/slider/{id}', [SliderController::class, 'destroy']);

  //routes categories
  Route::get('/categories', [CategorieController::class, 'index']);
  Route::get('/categorie/show/{id} ', [CategorieController::class, 'show']);
  Route::post('/categorie/add', [CategorieController::class, 'store']);
  Route::put('/categorie/update/{id}', [CategorieController::class, 'update']);
  Route::delete('/categorie/{id}', [CategorieController::class, 'destroy']);


  //routes communication media
  Route::get('/communication_medias', [CommunicationMediaController::class, 'index']);
  Route::get('/communication_media/show/{id} ', [CommunicationMediaController::class, 'show']);
  Route::post('/communication_media/add ', [CommunicationMediaController::class, 'store']);
  Route::put('/communication_media/update/{id}', [CommunicationMediaController::class, 'update']);
  Route::delete('/communication_media/{id}', [CommunicationMediaController::class, 'destroy']);






  //routes gouvernorat
  Route::get('/gouvernorats', [GouvernoratController::class, 'index']);
  Route::get('/gouvernorat/show/{id} ', [GouvernoratController::class, 'show']);
  Route::post('/gouvernorat/add', [GouvernoratController::class, 'store']);
  Route::put('/gouvernorat/update/{id}', [GouvernoratController::class, 'update']);
  Route::delete('/gouvernorat/{id}', [GouvernoratController::class, 'destroy']);

  //routes Secruite
  Route::get('/secruites', [SecuriteController::class, 'index']);
  Route::get('/secruite/show/{id} ', [SecuriteController::class, 'show']);
  Route::post('/secruite/add ', [SecuriteController::class, 'store']);
  Route::put('/secruite/update/{id}', [SecuriteController::class, 'update']);
  Route::delete('/secruite/{id}', [SecuriteController::class, 'destroy']);

  //mediaaaaaaaaaa
  Route::get('/medias', [MediaController::class, 'index']);
  Route::get('/media/show/{id} ', [MediaController::class, 'show']);
  Route::post('/media/add', [MediaController::class, 'store']);
  Route::put('/media/update/{id}', [MediaController::class, 'update']);
  Route::delete('/media/{id}', [MediaController::class, 'destroy']);

  //routes recrutements
  Route::get('/recrutements', [RecrutementController::class, 'index']);
  Route::get('/recrutement/show/{id} ', [RecrutementController::class, 'show']);
  Route::post('/recrutement/add', [RecrutementController::class, 'store']);
  Route::put('/recrutement/update/{id}', [RecrutementController::class, 'update']);
  Route::delete('/recrutement/{id}', [RecrutementController::class, 'destroy']);




  //routes newes lettre emailsnewsletter
  Route::get('/email_news_letters', [EmailsNewsLetterController::class, 'index']);
  Route::get('/email_news_letter/show/{id} ', [EmailsNewsLetterController::class, 'show']);
  Route::post('/email_news_letter/add', [EmailsNewsLetterController::class, 'store']);
  Route::put('/email_news_letter/update/{id}', [EmailsNewsLetterController::class, 'update']);
  Route::delete('/email_news_letter/{id}', [EmailsNewsLetterController::class, 'destroy']);


  //routes visiteur
  Route::get('/visiteurs', [VisiteurController::class, 'index']);
  Route::get('/visiteur/show/{id} ', [VisiteurController::class, 'show']);
  Route::post('/visiteur/add', [VisiteurController::class, 'store']);
  Route::put('/visiteur/update/{id}', [VisiteurController::class, 'update']);
  Route::delete('/visiteur/{id}', [VisiteurController::class, 'destroy']);


  //routes gares
  Route::get('/gares', [GareController::class, 'index']);
  Route::get('/gare/show/{id} ', [GareController::class, 'show']);
  Route::post('/gare/add', [GareController::class, 'store']);
  Route::put('/gare/update/{id}', [GareController::class, 'update']);
  Route::delete('/gare/{id}', [GareController::class, 'destroy']);


  //routes abonnes
  Route::get('/abonnes', [AbonneController::class, 'index']);
  Route::get('/abonne/show/{id} ', [AbonneController::class, 'show']);
  Route::get('/abonne/export/csv', [AbonneController::class, 'exportCSV']);
  Route::post('/abonne/import/csv', [AbonneController::class, 'importCSV']);
  Route::post('/abonne/add', [AbonneController::class, 'store']);
  Route::put('/abonne/update/{id}', [AbonneController::class, 'update']);
  Route::put('/abonne/updateEtat/{id}', [AbonneController::class, 'updateEtat']);
  Route::delete('/abonne/{id}', [AbonneController::class, 'destroy']);
  Route::post('/abonne/insert-multiple', [AbonneController::class, 'insertMultipleAbonnes']);


  //routes abonnesreg & urbain
  Route::get('/listabn', [AbonneController::class, 'listabn']);


  //routes station
  Route::get('/stations', [StationController::class, 'index']);
  Route::get('/station/show/{id} ', [StationController::class, 'show']);
  Route::post('/station/add', [StationController::class, 'store']);
  Route::put('/station/update/{id}', [StationController::class, 'update']);
  Route::delete('/station/{id}', [StationController::class, 'destroy']);
  //routes gallerie
  Route::get('/galleries', [GallerieController::class, 'index']);
  Route::get('/gallerie/show/{id} ', [GallerieController::class, 'show']);
  Route::post('/gallerie/add', [GallerieController::class, 'store']);
  Route::put('/gallerie/update/{id}', [GallerieController::class, 'update']);
  Route::delete('/gallerie/{id}', [GallerieController::class, 'destroy']);

  //routes typeetablissement
  Route::get('/typeetablissements', [TypeEtablissementController::class, 'index']);
  Route::get('/typeetablissement/show/{id} ', [TypeEtablissementController::class, 'show']);
  Route::post('/typeetablissement/add', [TypeEtablissementController::class, 'store']);
  Route::put('/typeetablissement/update/{id}', [TypeEtablissementController::class, 'update']);
  Route::delete('/typeetablissement/{id}', [TypeEtablissementController::class, 'destroy']);

  //routes Etablissement
  Route::get('/etablissemnts', [EtablissementController::class, 'index']);
  Route::get('/etablissemnt/show/{id} ', [EtablissementController::class, 'show']);
  Route::post('/etablissemnt/add', [EtablissementController::class, 'store']);
  Route::put('/etablissemnt/update/{id}', [EtablissementController::class, 'update']);
  Route::delete('/etablissemnt/{id}', [EtablissementController::class, 'destroy']);


  //routes Appeldoffres
  Route::get('/appel_doffres', [AppelDoffreController::class, 'index']);
  Route::get('/appel_doffre/show/{id} ', [AppelDoffreController::class, 'show']);
  Route::post('/appel_doffre/add ', [AppelDoffreController::class, 'store']);
  Route::put('/appel_doffre/update/{id}', [AppelDoffreController::class, 'update']);
  Route::delete('/appel_doffre/{id}', [AppelDoffreController::class, 'destroy']);
  //routes lignes
  Route::get('/lignes', [LigneController::class, 'index']);
  Route::get('/ligne/show/{id} ', [LigneController::class, 'show']);
  Route::post('/ligne/add', [LigneController::class, 'store']);
  Route::put('/ligne/update/{id}', [LigneController::class, 'update']);
  Route::delete('/ligne/{id}', [LigneController::class, 'destroy']);

  //routes typeabonnement
  Route::get('/typeabonnements', [TypeAbonnementController::class, 'index']);
  Route::get('/typeabonnement/show/{id} ', [TypeAbonnementController::class, 'show']);
  Route::post('/typeabonnement/add', [TypeAbonnementController::class, 'store']);
  Route::put('/typeabonnement/update/{id}', [TypeAbonnementController::class, 'update']);
  Route::delete('/typeabonnement/{id}', [TypeAbonnementController::class, 'destroy']);

  //routes typeabonnement
  Route::get('/typeabonnes', [TypeAbonneController::class, 'index']);
  Route::get('/typeabonne/show/{id} ', [TypeAbonneController::class, 'show']);
  Route::post('/typeabonne/add', [TypeAbonneController::class, 'store']);
  Route::put('/typeabonne/update/{id}', [TypeAbonneController::class, 'update']);
  Route::delete('/typeabonne/{id}', [TypeAbonneController::class, 'destroy']);

  Route::get('/etablissements', [EtablissementFrontController::class, 'index']);
  Route::get('/etablissement ', [EtablissementFrontController::class, 'show']);

  //routes circuits
  Route::get('/circuits', [CircuitController::class, 'index']);
  Route::get('/circuit/show/{id} ', [CircuitController::class, 'show']);
  Route::post('/circuit/add', [CircuitController::class, 'store']);
  Route::put('/circuit/update/{id}', [CircuitController::class, 'update']);
  Route::delete('/circuit/{id}', [CircuitController::class, 'destroy']);
  //routes commandes
  Route::get('/commandes', [CommandeController::class, 'index']);
  Route::get('/commande/show/{id} ', [CommandeController::class, 'show']);
  Route::post('/commande/add', [CommandeController::class, 'store']);
  Route::put('/commande/update/{id}', [CommandeController::class, 'update']);
  Route::delete('/commande/{id}', [CommandeController::class, 'destroy']);

  //routes DemandeAbonnement
  Route::get('/demandeabonnements', [DemandeAbonnementController::class, 'index']);
  Route::get('/demandeabonnement/show/{id} ', [DemandeAbonnementController::class, 'show']);
  Route::post('/demandeabonnement/add', [DemandeAbonnementController::class, 'store']);
  Route::put('/demandeabonnement/update/{id}', [DemandeAbonnementController::class, 'update']);
  Route::delete('/demandeabonnement/{id}', [DemandeAbonnementController::class, 'destroy']);


  Route::get('/parents', [ParentModelController::class, 'index']);
  Route::get('/parent/show/{id} ', [ParentModelController::class, 'show']);
  Route::post('/parent/add', [ParentModelController::class, 'store']);
  Route::put('/parent/update/{id}', [ParentModelController::class, 'update']);
  Route::delete('/parent/{id}', [ParentModelController::class, 'destroy']);



  //routes ville
  Route::get('/villes', [VilleController::class, 'index']);
  Route::get('/ville/show/{id} ', [VilleController::class, 'show']);
  Route::post('/ville/add', [VilleController::class, 'store']);
  Route::put('/ville/update/{id}', [VilleController::class, 'update']);
  Route::delete('/ville/{id}', [VilleController::class, 'destroy']);

  //routes reservations
  Route::get('/resrvations', [ReservationController::class, 'index']);
  Route::get('/resrvation/show/{id} ', [ReservationController::class, 'show']);
  Route::post('/resrvation/add', [ReservationController::class, 'store']);
  Route::put('/resrvation/update/{id}', [ReservationController::class, 'update']);
  Route::delete('/resrvation/{id}', [ReservationController::class, 'destroy']);

  //routes Setting -----------------------------------------------------------------------------
  Route::get('/settings', [SettingController::class, 'index']);
  Route::get('/setting/show/{id}', [SettingController::class, 'show']);
  Route::post('/setting/add ', [SettingController::class, 'store']);
  Route::put('/setting/update/{id}', [SettingController::class, 'update']);
  //routes events
  Route::get('/evenements', [EvenementController::class, 'index']);
  Route::get('/evenement/show/{id}', [EvenementController::class, 'show']);
  Route::post('/evenement/add', [EvenementController::class, 'store']);
  Route::put('/evenement/update/{id}', [EvenementController::class, 'update']);
  Route::delete('/evenement/{id}', [EvenementController::class, 'destroy']);


  //routes tarif
  Route::get('/tarifs', [TarifController::class, 'index']);
  Route::get('/tarif/show/{id} ', [TarifController::class, 'show']);
  Route::post('/tarif/add', [TarifController::class, 'store']);
  Route::put('/tarif/update/{id}', [TarifController::class, 'update']);
  Route::delete('/tarif/{id}', [TarifController::class, 'destroy']);
});

//routes contact
Route::get('/contacts', [ContactController::class, 'index']);
Route::get('/contact/show/{id} ', [ContactController::class, 'show']);
Route::post('/contact/add', [ContactController::class, 'store']);
Route::put('/contact/update/{id}', [ContactController::class, 'update']);
Route::delete('/contact/{id}', [ContactController::class, 'destroy']);



//************************************(client)********************************************** */

Route::get('/etablissements', [EtablissementFrontController::class, 'index']);
Route::get('/etablissement ', [EtablissementFrontController::class, 'show']);

Route::get('/lignes', [LigneController::class, 'index']);
Route::get('/ligne ', [LigneController::class, 'show']);

//routes circuits
Route::get('/circuits', [CircuitController::class, 'index']);
Route::get('/circuit/show/{id} ', [CircuitController::class, 'show']);
//routes parent 
Route::get('/parents', [ParentModelController::class, 'index']);
Route::get('/parent/show/{id} ', [ParentModelController::class, 'show']);
Route::post('/parent/add', [ParentModelController::class, 'store']);
Route::put('/parent/update/{id}', [ParentModelController::class, 'update']);
Route::delete('/parent/{id}', [ParentModelController::class, 'destroy']);
Route::get('/parent/recover/{cin}', [ParentModelController::class, 'recoverPassword']);


// routes prixabonnes
Route::get('/abonnesprix', [AbonnePrixController::class, 'index']);
Route::get('/abonneprix/show/{id} ', [AbonnePrixController::class, 'show']);
Route::post('/abonneprix/add', [AbonnePrixController::class, 'store']);
Route::put('/abonneprix/update/{id}', [AbonnePrixController::class, 'update']);
Route::delete('/abonneprix/{id}', [AbonnePrixController::class, 'destroy']);





//routes circuits
Route::get('/circuits', [CircuitController::class, 'index']);
Route::get('/circuit/show/{id} ', [CircuitController::class, 'show']);

// Routes Actualites pour la partie client
Route::get('/actualites', [ActualiteFrontController::class, 'index']);
Route::get('/actualite', [ActualiteFrontController::class, 'show']);


//routes abonnes
Route::get('/abonnes', [AbonneController::class, 'index']);
Route::get('/abonne/show/{id} ', [AbonneController::class, 'show']);
Route::post('/abonne/add', [AbonneController::class, 'store']);
Route::put('/abonne/update/{code}', [AbonneController::class, 'update']);
Route::delete('/abonne/{id}', [AbonneController::class, 'destroy']);
Route::get('/abonne/recover/{cin}', [AbonneController::class, 'recoverPassword']);



// routes stations
Route::get('/stations', [StationFrontController::class, 'index']);
Route::get('/station/{id}', [StationFrontController::class, 'show']);

// routes recrutements
Route::get('/recrutements', [RecrutementFrontController::class, 'index']);
Route::get('/recrutement/{id}', [RecrutementFrontController::class, 'show']);


// routes appels d'offres
Route::get('/appel_doffres', [AppelDoffreFrontController::class, 'index']);
Route::get('/appel_doffre/{id}', [AppelDoffreFrontController::class, 'show']);

// routes qualités
Route::get('/qualites', [QualiteFrontController::class, 'index']);
Route::get('/qualite/{id}', [QualiteFrontController::class, 'show']);


// routes présentations
Route::get('/presentations', [PresentationFrontController::class, 'index']);
Route::get('/presentation/{id}', [PresentationFrontController::class, 'show']);

// routes sécurité
Route::get('/securites', [SecuriteFrontController::class, 'index']);
Route::get('/securite/{id}', [SecuriteFrontController::class, 'show']);
// routes contact
Route::get('/contacts', [ContactFrontController::class, 'index']);
Route::get('/contact/{id}', [ContactFrontController::class, 'show']);
//paimentttttttttttttt

Route::post('/payement', [MonerisController::class, 'createSession']);
Route::post('/payements', [CheckoutController::class, 'createPaymentSession']);
//ccc
Route::post('/loginr', [AuthrController::class, 'loginr']);

Route::post('/loginc', [AuthcController::class, 'loginc']);

Route::get('/gares', [GareController::class, 'index']);
Route::get('/ligne_station', [LigneFrontstation::class, 'index']);
Route::get('/ligne_station/{id}', [LigneFrontstation::class, 'show']);
Route::get('/lgine/show/{cod}', [LigneFrontController::class, 'show']);

Route::post('/upload', [ImageController::class, 'upload']);
Route::get('/horairesss', [HoraireController::class, 'index']);



Route::get('{any}', function () {
  return view('welcome');
})->where('any', '.*');
