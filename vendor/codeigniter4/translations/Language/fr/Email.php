<?php 
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Application Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */

/**
 * This file is part of CodeIgniter 4 framework.
 *
 * (c) CodeIgniter Foundation <admin@codeigniter.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

// Email language settings
return [
    'mustBeArray'          => 'La méthode de validation de l\'email n\'accepte que les tableaux.',
    'invalidAddress'       => 'Adresse email invalide : {0}',
    'attachmentMissing'    => 'Impossible de localiser le ficher joint suivant : {0}',
    'attachmentUnreadable' => 'Impossible d\'ouvrir ce fichier joint : {0}',
    'noFrom'               => 'Impossible d\'envoyer un email sans en-tête "From".',
    'noRecipients'         => 'Vous devez spécifier des destinataires : To, Cc, ou Bcc',
    'sendFailurePHPMail'   => 'Impossible d\'envoyer des emails avec la fonction mail() de PHP. Votre serveur n\'est peut-être pas configuré pour pouvoir utiliser cette méthode.',
    'sendFailureSendmail'  => 'Impossible d\'envoyer des emails avec la méthode Sendmail de PHP. Votre serveur n\'est peut-être pas configuré pour pouvoir utiliser cette méthode.',
    'sendFailureSmtp'      => 'Impossible d\'envoyer des emails avec la méthode SMTP de PHP. Votre serveur n\'est peut-être pas configuré pour pouvoir utiliser cette méthode.',
    'sent'                 => 'Votre message a bien été envoyé avec le protocole suivant : {0}',
    'noSocket'             => 'Impossible d\'ouvrir un socket avec Sendmail. Veuillez vérifier la configuration de votre environnement.',
    'noHostname'           => 'Vous n\'avez pas spécifié de nom d\'hôte SMTP.',
    'SMTPError'            => 'L\'erreur SMTP suivante s\'est produite : {0}',
    'noSMTPAuth'           => 'Erreur : Vous devez spécifier un nom d\'utilisateur et un mot de passe SMTP.',
    'failedSMTPLogin'      => 'Échec lors de l\'envoi de la commande AUTH LOGIN. Erreur : {0}',
    'SMTPAuthUsername'     => 'Impossible d\'identifier le nom d\'utilisateur. Erreur : {0}',
    'SMTPAuthPassword'     => 'Impossible d\'identifier le mot de passe. Erreur : {0}',
    'SMTPDataFailure'      => 'Impossible d\'envoyer les données : {0}',
    'exitStatus'           => 'Code de retour : {0}',
];
