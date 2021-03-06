<?php
namespace In2code\Femanager\Domain\Validator;

use SJBR\SrFreecap\Validation\Validator\CaptchaValidator as FreecapCaptchaValidator;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

/**
 * Class CaptchaValidator
 */
class CaptchaValidator extends AbstractValidator
{

    /**
     * Validation of given Params
     *
     * @param $user
     * @return bool
     */
    public function isValid($user)
    {
        $this->init();

        if (!$this->captchaEnabled()) {
            return true;
        }
        $captchaCode = $this->pluginVariables['captcha'];

        $freecapValidator = $this->objectManager->get(FreecapCaptchaValidator::class);
        if ($freecapValidator->isValid($captchaCode)) {
            return true;
        }

        $this->addError('validationErrorCaptcha', 'captcha');
        return false;
    }

    /**
     * Check if captcha is enabled (TypoScript, and sr_freecap loaded)
     *
     * @return bool
     */
    protected function captchaEnabled()
    {
        // if sr_freecap is not loaded
        if (!ExtensionManagementUtility::isLoaded('sr_freecap')) {
            return false;
        }

        // if not enabled via TypoScript
        if (empty($this->validationSettings['captcha']['captcha'])) {
            return false;
        }

        return true;
    }
}
