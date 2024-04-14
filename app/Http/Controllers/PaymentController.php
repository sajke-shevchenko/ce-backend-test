<?php

namespace App\Http\Controllers;

use App\Exceptions\Gateway\GatewayRequestException;
use App\Http\Requests\UpdatePaymentRequest;
use App\Models\Payment;
use App\Services\Gateway\Factory\GatewayFactoryHelper;
use App\Services\Payment\PaymentService;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\JsonResponse;

class PaymentController extends Controller
{
    public function update(
        Payment $payment,
        UpdatePaymentRequest $updatePaymentRequest,
        PaymentService $paymentService,
    ): JsonResponse {
        $paymentData = $updatePaymentRequest->getPaymentData();

        if ($payment->status <> $paymentData->status) {
            try {
                $paymentService->update($payment, $updatePaymentRequest->getPaymentData(), auth()->user());

                $gatewayFactory = GatewayFactoryHelper::getGatewayFactory($updatePaymentRequest->get('gateway_type'));

                $gatewayFactory->sendRequest($payment);
            } catch (GatewayRequestException|AccessDeniedException $gatewayRequestException) {
                return response()->json([
                    'success' => false,
                    'code' => $gatewayRequestException->getCode(),
                    'message' => $gatewayRequestException->getMessage(),
                ]);
            }

            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => 'Payment status has been changed',
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Payment status has not changed',
        ]);
    }
}
