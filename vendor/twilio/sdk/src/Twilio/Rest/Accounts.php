<?php 
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Application Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */
namespace Twilio\Rest;

use Twilio\Rest\Accounts\V1;

class Accounts extends AccountsBase {

    /**
     * @deprecated Use v1->authTokenPromotion instead
     */
    protected function getAuthTokenPromotion(): \Twilio\Rest\Accounts\V1\AuthTokenPromotionList {
        echo "authTokenPromotion is deprecated. Use v1->authTokenPromotion instead.";
        return $this->v1->authTokenPromotion;
    }

    /**
     * @deprecated Use v1->authTokenPromotion() instead.
     */
    protected function contextAuthTokenPromotion(): \Twilio\Rest\Accounts\V1\AuthTokenPromotionContext {
        echo "authTokenPromotion() is deprecated. Use v1->authTokenPromotion() instead.";
        return $this->v1->authTokenPromotion();
    }

    /**
     * @deprecated Use v1->credentials instead.
     */
    protected function getCredentials(): \Twilio\Rest\Accounts\V1\CredentialList {
        echo "credentials is deprecated. Use v1->credentials instead.";
        return $this->v1->credentials;
    }

    /**
     * @deprecated Use v1->secondaryAuthToken instead.
     */
    protected function getSecondaryAuthToken(): \Twilio\Rest\Accounts\V1\SecondaryAuthTokenList {
        echo "secondaryAuthToken is deprecated. Use v1->secondaryAuthToken instead.";
        return $this->v1->secondaryAuthToken;
    }

    /**
     * @deprecated Use v1->secondaryAuthToken() instead.
     */
    protected function contextSecondaryAuthToken(): \Twilio\Rest\Accounts\V1\SecondaryAuthTokenContext {
        echo "secondaryAuthToken() is deprecated. Use v1->secondaryAuthToken() instead.";
        return $this->v1->secondaryAuthToken();
    }
}