<?php

namespace App;

class Constant
{
    // 🏢 Informations entreprise
    const ENTREPRISE = [
        'nom' => 'SOCIETE SENEGALAISE DE TORREFACTION',
        'adresse' => 'Dakar, Sénégal',
        'telephone' => '+221 77 000 00 00',
        'email' => 'contact@sst.sn',
        'site' => 'www.sst.sn',
        'ninea' => '123456789',
        'rc' => 'SN-DKR-2026-B-00001',
        'logo' => 'assets/images/logo.png', // stocké dans public/
    ];
    
    const ROLES = [
        'ROOT' => 'ROOT',
        'ADMIN' => 'ADMIN',
        'PRODUCTION' => 'PRODUCTION',
        'COMMERCIAL' => 'COMMERCIAL',
    ];

    const MATIERE_PREMIERE_TYPES = [
        'VEGETALE'       => 'VEGETALE',
        'ANIMALE'        => 'ANIMALE',
        'MINERALE'       => 'MINERALE',
        'METALLIQUE'     => 'METALLIQUE',
        'CHIMIQUE'       => 'CHIMIQUE',
        'ENERGETIQUE'    => 'ENERGETIQUE',
        'AGROALIMENTAIRE'=> 'AGROALIMENTAIRE',
        'SYNTHETIQUE'    => 'SYNTHETIQUE',
    ];  


    const TYPESMOUVEMENT = [
        'ENTREE' => 'ENTREE',
        'SORTIE' => 'SORTIE',
    ];

    const TYPE_PRODUITS = [
        'TORREFIE' => 'TORIFIE',
        'MOULU' => 'MOULU'
    ];

    const STATUT_ORDRE_PRODUCTION = [
        'EN_ATTENTE' => 'EN_ATTENTE',
        'EN_COURS' => 'EN_COURS',
        'TERMINE' => 'TERMINE',
    ];    

    const REF = [
        'NO_REF' => 'NO_REF'
    ];

    const TYPES_CLIENT = [
        'PARTICULIER'       => 'PARTICULIER',
        'ENTREPRISE'        => 'ENTREPRISE',
        'REVENDEUR'         => 'REVENDEUR',
        'GROSSISTE'         => 'GROSSISTE',
        'DISTRIBUTEUR'      => 'DISTRIBUTEUR',
        'INSTITUTION'       => 'INSTITUTION',
        'EXPORT'            => 'EXPORT',
        'PARTENAIRE'        => 'PARTENAIRE',
    ];
    

    const FACTURE = [
        'NO_FACT' => 'NO_FACT',
        'NO_PAYEE' => 'NO_PAYEE',
        'PAYEE' => 'PAYEE',

    ];

    const MODES_PAIEMENT = [
        'CASH' => 'CASH',
        'OM'  => 'OM',
        'WAVE'  => 'WAVE',
        'VIREMENT'  => 'VIREMENT'
    ];
}

