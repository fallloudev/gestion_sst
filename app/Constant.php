<?php

namespace App;

class Constant
{
    // 🏢 Informations entreprise
    const ENTREPRISE = [
        'nom' => 'SOCIETE SENEGALAISE DE TORREFACTION',
        'adresse' => 'Dakar, Sénégal',
        'telephone' => '+221 78 811 55 55 / +221 77 095 51 66',
        'email' => 'info@sst.sn',
        'site' => 'www.sst.sn',
        'ninea' => '009450496',
        'rc' => 'SN DKR 2022 B 18865',
        'logo' => 'assets/images/logo.jpeg'
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
        'TORREFIE' => 'TORRÉFIÉ',
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

