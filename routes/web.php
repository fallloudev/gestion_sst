<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MatierePremiereController;
use App\Http\Controllers\EntrepotController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\MouvementStockController;
use App\Http\Controllers\ProduitController;
use App\Http\Controllers\OrdreProductionController;
use App\Http\Controllers\CommandeController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\FactureController;
use App\Http\Controllers\PaiementController;
use App\Http\Controllers\DashboardController;



use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/', function () {
//     return view('pages.dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // utilisateurs
    Route::get('/list_user', [UserController::class, 'listUser'])->name('user.list');
    Route::get('/add_user', [UserController::class, 'addUser'])->name('user.addUser');
    Route::post('/store_user', [UserController::class, 'storeUser'])->name('user.storeUser');
    Route::get('/edit_user/{userId}/edit', [UserController::class, 'editUser'])->name('user.editUser');
    Route::put('/update_user/{userId}', [UserController::class, 'updateUser'])->name('user.updateUser');
    Route::delete('/delete_user/{userId}/delete', [UserController::class, 'deleteUser'])->name('user.deleteUser');
    Route::put('/statut_user/{userId}/changed', [UserController::class, 'changeUserStatut'])->name('user.changeUserStatut');

    //Matiere Premiere
    Route::get('/list_mp', [MatierePremiereController::class, 'listMp'])->name('mp.list');
    Route::get('/add_mp', [MatierePremiereController::class, 'addMp'])->name('mp.add');
    Route::post('/store_mp', [MatierePremiereController::class, 'storeMp'])->name('mp.store');
    Route::get('/edit_mp/{mpId}/edit', [MatierePremiereController::class, 'editMp'])->name('mp.edit');
    Route::put('/update_mp/{mpId}', [MatierePremiereController::class, 'updateMp'])->name('mp.update');
    Route::delete('/delete_mp/{mpId}/delete', [MatierePremiereController::class, 'deleteMp'])->name('mp.delete');


    //entrepot
    Route::get('/list_entrepot', [EntrepotController::class, 'listEntrepot'])->name('entrepot.list');
    Route::get('/add_entrepot', [EntrepotController::class, 'addEntrepot'])->name('entrepot.add');
    Route::post('/store_entrepot', [EntrepotController::class, 'storeEntrepot'])->name('entrepot.store');
    Route::get('/edit_entrepot/{entrepotId}/edit', [EntrepotController::class, 'editEntrepot'])->name('entrepot.edit');
    Route::put('/update_entrepot/{entrepotId}', [EntrepotController::class, 'updateEntrepot'])->name('entrepot.update');
    Route::delete('/delete_entrepot/{entrepotId}/delete', [EntrepotController::class, 'deleteEntrepot'])->name('entrepot.delete');

    //stock
    Route::get('/list_stockMp', [StockController::class, 'listStockMp'])->name('stock.listMp');
    Route::get('/add_stock', [StockController::class, 'addStock'])->name('stock.add');
    Route::post('/store_stock', [StockController::class, 'storeStock'])->name('stock.store');
    Route::get('/edit_stock/{stockId}/edit', [StockController::class, 'editStock'])->name('stock.edit');
    Route::put('/update_stock/{stockId}', [StockController::class, 'updateStock'])->name('stock.update');
    Route::delete('/delete_stock/{stockId}/delete', [StockController::class, 'deleteStock'])->name('stock.delete');

    //Mouvement stock
    Route::get('/list_mouvement_stock', [MouvementStockController::class, 'listMouvementStock'])->name('mouvementStock.list');
    Route::get('/add_mouvement_stock', [MouvementStockController::class, 'addMouvementStock'])->name('mouvementStock.add');
    Route::post('/store_mouvement_stock', [MouvementStockController::class, 'storeMouvementStock'])->name('mouvementStock.store');
    Route::get('/edit_mouvement_stock/{mouvementStockId}/edit', [MouvementStockController::class, 'editMouvementStock'])->name('mouvementStock.edit');
    Route::put('/update_mouvement_stock/{mouvementStockId}', [MouvementStockController::class, 'updateMouvementStock'])->name('mouvementStock.update');
    Route::delete('/delete_mouvement_stock/{mouvementStockId}/delete', [MouvementStockController::class, 'deleteMouvementStock'])->name('mouvementStock.delete');

    //Produit
    Route::get('/list_produit', [ProduitController::class, 'listProduit'])->name('produit.list');
    Route::get('/add_produit', [ProduitController::class, 'addProduit'])->name('produit.add');
    Route::post('/store_produit', [ProduitController::class, 'storeProduit'])->name('produit.store');
    Route::get('/edit_produit/{produitId}/edit', [ProduitController::class, 'editProduit'])->name('produit.edit');
    Route::put('/update_produit/{produitId}', [ProduitController::class, 'updateProduit'])->name('produit.update');
    Route::delete('/delete_produit/{produitId}/delete', [ProduitController::class, 'deleteproduit'])->name('produit.delete');

    //ordreProduction
    Route::get('/list_ordreProduction', [OrdreProductionController::class, 'listOrdreProduction'])->name('ordreProduction.list');
    Route::get('/add_ordreProduction', [OrdreProductionController::class, 'addOrdreProduction'])->name('ordreProduction.add');
    Route::post('/store_ordreProduction', [OrdreProductionController::class, 'storeOrdreProduction'])->name('ordreProduction.store');
    Route::get('/edit_ordreProduction/{ordreProductionId}/edit', [OrdreProductionController::class, 'editOrdreProduction'])->name('ordreProduction.edit');
    Route::put('/update_ordreProduction/{ordreProductionId}', [OrdreProductionController::class, 'updateOrdreProduction'])->name('ordreProduction.update');
    Route::delete('/delete_ordreProduction/{ordreProductionId}/delete', [OrdreProductionController::class, 'deleteOrdreProduction'])->name('ordreProduction.delete');
    Route::delete('/show_ordreProduction/{ordreProductionId}/show', [OrdreProductionController::class, 'showOrdreProduction'])->name('ordreProduction.show');


    //stock Produit
    Route::get('/list_stockProduit', [StockController::class, 'listStockProduit'])->name('stockProduit.list');
    Route::get('/add_stockProduit', [StockController::class, 'addStockProduit'])->name('stockProduit.add');
    Route::post('/store_stockProduit', [StockController::class, 'storeStockProduit'])->name('stockProduit.store');
    Route::get('/edit_stockProduit/{stockProduitId}/edit', [StockController::class, 'editStockProduit'])->name('stockProduit.edit');
    Route::put('/update_stockProduit/{stockProduitId}', [StockController::class, 'updateStockProduit'])->name('stockProduit.update');
    Route::delete('/delete_stockProduit/{stockProduitId}/delete', [StockController::class, 'deleteStockProduit'])->name('stockProduit.delete');

    //Mouvement stock Produit
    Route::get('/list_mouvementStockProduit', [MouvementStockController::class, 'listMouvementStockProduit'])->name('mouvementStockProduit.list');
    Route::get('/add_mouvementStockProduit', [MouvementStockController::class, 'addMouvementStockProduit'])->name('mouvementStockProduit.add');
    Route::post('/store_mouvementStockProduit', [MouvementStockController::class, 'storeMouvementStockProduit'])->name('mouvementStockProduit.store');
    Route::get('/edit_mouvementStockProduit/{mouvementStockProduitId}/edit', [MouvementStockController::class, 'editMouvementStockProduit'])->name('mouvementStockProduit.edit');
    Route::put('/update_mouvementStockProduit/{mouvementStockProduitId}', [MouvementStockController::class, 'updateMouvementStockProduit'])->name('mouvementStockProduit.update');
    Route::delete('/delete_mouvementStockProduit/{mouvementStockProduitId}/delete', [MouvementStockController::class, 'deleteMouvementStockProduit'])->name('mouvementStockProduit.delete');


    //client
    Route::get('/list_client', [ClientController::class, 'listClient'])->name('client.list');
    Route::get('/add_client', [ClientController::class, 'addClient'])->name('client.add');
    Route::post('/store_client', [ClientController::class, 'storeClient'])->name('client.store');
    Route::get('/edit_client/{clientId}/edit', [ClientController::class, 'editClient'])->name('client.edit');
    Route::put('/update_client/{clientId}', [ClientController::class, 'updateClient'])->name('client.update');
    Route::delete('/delete_client/{clientId}/delete', [ClientController::class, 'deleteClient'])->name('client.delete');


    //commande
    Route::get('/list_commande', [CommandeController::class, 'listCommande'])->name('commande.list');
    Route::get('/add_commande', [CommandeController::class, 'addCommande'])->name('commande.add');
    Route::post('/store_commande', [CommandeController::class, 'storeCommande'])->name('commande.store');
    Route::get('/edit_commande/{commandeId}/edit', [CommandeController::class, 'editCommande'])->name('commande.edit');
    Route::put('/update_commande/{commandeId}', [CommandeController::class, 'updateCommande'])->name('commande.update');
    Route::delete('/delete_commande/{commandeId}/delete', [CommandeController::class, 'deleteCommande'])->name('commande.delete');
    Route::get('/show_commande/{commandeId}/show', [CommandeController::class, 'showCommande'])->name('commande.show');

    //Facture Commande
    Route::get('/facture_commande/{commandeId}/facture', [CommandeController::class, 'facturerCommande'])->name('commande.facture');
    Route::post('/facture_commande/{commandeId}/factureStore', [CommandeController::class, 'storeFactureCommande'])->name('commande.facture.store');

    //Facture
    Route::get('/factures/{id}/pdf',[FactureController::class, 'pdfFacture'])->name('facture.pdf');
    Route::get('/factures/{id}/print',[FactureController::class, 'printFacture'])->name('facture.print');
    Route::get('/list_facture', [FactureController::class, 'listFacture'])->name('facture.list');


    //paiement
    Route::get('/list_paiement', [PaiementController::class, 'listPaiement'])->name('paiement.list');
    Route::get('/add_paiement/{factureId}/add', [PaiementController::class, 'addPaiement'])->name('paiement.add');
    Route::post('/store_paiement', [PaiementController::class, 'storePaiement'])->name('paiement.store');
    Route::get('/edit_paiement/{paiementId}/edit', [PaiementController::class, 'editPaiement'])->name('paiement.edit');
    Route::put('/update_paiement/{paiementId}', [PaiementController::class, 'updatePaiement'])->name('paiement.update');
    Route::delete('/delete_paiement/{paiementId}/delete', [PaiementController::class, 'deletePaiement'])->name('paiement.delete');
    Route::get('/show_paiement/{paiementId}/show', [PaiementController::class, 'showPaiement'])->name('paiement.show');


    Route::get('/paiement/{id}/pdf',[PaiementController::class, 'paiementPdf'])->name('paiement.pdf');
    Route::get('/paiement/{id}/print',[PaiementController::class, 'paiementPrint'])->name('paiement.print');


});

require __DIR__.'/auth.php';
