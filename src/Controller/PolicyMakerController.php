<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Http\Response;

/**
 * Class PolicyMakerController
 * @package App\Controller
 */
class PolicyMakerController extends Controller
{
    /**
     * Main page
     *
     * @return Response
     */
    public function index(): Response
    {
        $this->viewBuilder()->disableAutoLayout();
        return $this->render('/privacy/index');
    }

    /**
     * Policy maker with view
     *
     * @return Response
     */
    public function makePolicy(): Response
    {
        $this->viewBuilder()->disableAutoLayout();
        $form = $this->request->getData();
        $policy = new PolicyText($form);
        $policyText = $policy->getParsedText();
        $this->set('policy', $policyText);
        return $this->render('/privacy/index');
    }
}
