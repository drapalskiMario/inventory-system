<?php

declare(strict_types=1);

namespace App\Mailer;

use Cake\Log\Log;
use Cake\Mailer\Mailer;
use Cake\Queue\Mailer\QueueTrait;
use Cake\Queue\Queue\Processor;

/**
 * Order mailer.
 */
class OrderMailer extends Mailer
{
    use QueueTrait;
    /**
     * Mailer's name.
     *
     * @var string
     */
    public static string $name = 'Order';

    public function notifyCustomer(array $data): void
    {
        try {
            $emailContent = "Resumo dos Pedidos:\n\n";
            $emailContent .= "Cliente: " . $data['customer_name'] . "\n";
            $emailContent .= "Email: " . $data['customer_email'] . "\n";
            $emailContent .= "ID do Pedido: " . $data['order_id'] . "\n";
            $emailContent .= "Produto: " . $data['product_name'] . "\n";
            $emailContent .= "Quantidade: " . $data['quantity'] . "\n";
            $emailContent .= "Valor Total: R$ " . number_format((float)$data['total_value'], 2, ',', '.') . "\n";
            $emailContent .= "Status do Pedido: " . $data['order_status'] . "\n";
            $emailContent .= "----------------------------------------\n";

            $mailer = new Mailer('default');
            $mailer->setFrom(['me@example.com' => 'My Site'])
                ->setTo('you@example.com')
                ->setSubject('Resumo dos Pedidos')
                ->deliver($emailContent);

            Log::info("E-mail send to {$data['customer_email']}");
        } catch (\Throwable $th) {
            Log::error("Error on email send: " . $th->getMessage(), json_encode([
                'message' => $th->getMessage(),
                'code' => $th->getCode(),
                'file' => $th->getFile(),
                'line' => $th->getLine(),
                'trace' => $th->getTrace(),
            ]));
        }
    }
}
