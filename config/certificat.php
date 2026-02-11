<?php

return [
    // Montant par défaut d'un certificat (FCFA)
    'default_montant' => 5000,

    // Nombre de jours avant de signaler un certificat en attente comme "en retard"
    'pending_alert_days' => 7,

    // Métadonnées d'affichage et de validation pour les statuts
    'statuses' => [
        'en_attente' => [
            'label' => 'En attente',
            'color' => 'amber',
        ],
        'paye' => [
            'label' => 'Payé',
            'color' => 'indigo',
        ],
        'delivre' => [
            'label' => 'Délivré',
            'color' => 'emerald',
        ],
    ],
];
