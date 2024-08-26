<?php 
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Access Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */



return [
    // Errors
    'auth_incorrect_password' => 'Incorrect password!',
    'auth_incorrect_login' => 'Incorrect login!',
    'auth_incorrect_email_or_username' => 'Login or email doesn\'t exist!',
    'auth_email_in_use' => 'Email is already used by another user! Please choose another email.',
    'auth_username_in_use' => 'Username already exists! Please choose another username.',
    'auth_current_email' => 'This is your current email.',
    'auth_incorrect_captcha' => 'Your confirmation code does not match the one in the image!',
    'auth_captcha_expired' => 'Your confirmation code has expired! Please try again.',
    
    // Notifications
    'auth_message_logged_out' => 'You have been successfully logged out!',
    'auth_message_registration_disabled' => 'Registration is disabled!',
    'auth_message_registration_completed_1' => 'You have successfully registered! Check your email address to activate your account.',
    'auth_message_registration_completed_2' => 'You have successfully registered!',
    'auth_message_activation_email_sent' => 'A new activation email has been sent to %s! Follow the instructions in the email to activate your account.',
    'auth_message_activation_completed' => 'Your account has been successfully activated!',
    'auth_message_activation_failed' => 'The activation code you entered is incorrect or expired!',
    'auth_message_password_changed' => 'Your password has been successfully changed!',
    'auth_message_new_password_sent' => 'An email with instructions for creating a new password has been sent to you!',
    'auth_message_new_password_activated' => 'You have successfully reset your password!',
    'auth_message_new_password_failed' => 'Your activation key is incorrect or expired! Please check your email again and follow the instructions.',
    'auth_message_new_email_sent' => 'A confirmation email has been sent to %s! Follow the instructions in the email to complete this change of email address.',
    'auth_message_new_email_activated' => 'You have successfully changed your email!',
    'auth_message_new_email_failed' => 'Your activation key is incorrect or expired! Please check your email again and follow the instructions.',
    'auth_message_banned' => 'You are banned!',
    'auth_message_unregistered' => 'Your account has been deleted...',
    
    // Email subjects
    'auth_subject_welcome' => 'Welcome to %s!',
    'auth_subject_activate' => 'Welcome to %s!',
    'auth_subject_forgot_password' => 'Forgot your password on %s?',
    'auth_subject_reset_password' => 'Your new password on %s.',
    'auth_subject_change_email' => 'Your new email address on %s.',
];
