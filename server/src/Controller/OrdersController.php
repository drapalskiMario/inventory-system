<?php

declare(strict_types=1);

namespace App\Controller;

use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\ConflictException;
use Cake\Http\Exception\HttpException;
use Cake\View\JsonView;
use App\Controller\QueuedJobsTable;
use App\Mailer\OrderMailer;

/**
 * Orders Controller
 *
 * @property \App\Model\Table\OrdersTable $Orders
 * @property \App\Model\Table\ProductsTable $Products
 */
class OrdersController extends AppController
{
  public function viewClasses(): array
  {
    return [JsonView::class];
  }

  /**
   * View method
   *
   * @param string $id Order id.
   * @return \Cake\Http\Response|null|void Renders view
   * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
   */
  public function view($id)
  {
    $this->request->allowMethod(['get']);
    $order = $this->Orders->get($id, contain: ['Customers', 'Products']);
    $this->set('order', $order);
    $this->viewBuilder()->setOption('serialize', ['order']);
  }

  /**
   * Add method
   *
   * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
   */
  public function add()
  {
    $this->request->allowMethod(['post']);

    $productTable = $this->fetchTable('Products');
    $product = $productTable->find()->where(['id' => $this->request->getData('product_id')])->first();
    if (!$product) {
      throw new BadRequestException('Product Not Found');
    }

    $customer = $this->fetchTable('Customers')->find()->where(['id' => $this->request->getData('customer_id')])->first();
    if (!$customer) {
      throw new BadRequestException('Customer Not Found');
    }

    if ($product->stock_quantity < $this->request->getData('quantity')) {
      throw new ConflictException('Insufficient Stock');
    }

    $order = $this->Orders->newEntity($this->request->getData());
    if (!$this->Orders->save($order)) {
      throw new HttpException('Erro On Save Order', 422);
    }

    $product->stock_quantity -= $order->quantity;
    if (!$productTable->save($product)) {
      throw new HttpException('Erro On Stock Update', 422);
    }

    $emailData = [
      'customer_name' => $customer->name,
      'customer_email' => $customer->email,
      'order_id' => $order->id,
      'quantity' => $order->quantity,
      'product_name' => $product->name,
      'total_value' => $product->price * $order->quantity,
      'order_status' => 'new',
    ];

    $mailer = new OrderMailer();
    $mailer->push('notifyCustomer', [$emailData]);

    $this->set(['order' => $order]);
    $this->response = $this->response->withStatus(201);
    $this->viewBuilder()->setOption('serialize', ['order']);
  }

  /**
   * Edit method
   *
   * @param string $id Order id.
   * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
   * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
   */
  public function edit($id)
  {
    $this->request->allowMethod(['patch']);

    $order = $this->Orders->get($id);
    $data = $this->request->getData();

    if (isset($data['status']) && $order->status == 'shipped') {
      throw new BadRequestException('Order Has Already Been Shipped');
    }

    if (isset($data['status']) && $order->status == 'cancelled') {
      throw new BadRequestException('Order Has Already Been Cancelled');
    }

    if (isset($data['status']) && $data['status'] == 'new') {
      throw new BadRequestException('Status Cannnot Be "new".');
    }

    if (isset($data['quantity']) && $order->status != 'new') {
      throw new ConflictException('The Quantity Cannot Be Changed As The Order Status Is No Longer "new".');
    }

    if (isset($data['quantity'])) {
      $productTable = $this->fetchTable('Products');
      $product = $productTable->get($order->product_id);

      if ($data['quantity'] > $order->quantity) {
        $diffQuantity = $data['quantity'] - $order->quantity;
        if ($product->stock_quantity < $diffQuantity) {
          throw new ConflictException('Insufficient Stock');
        }
        $product->stock_quantity -= $diffQuantity;
      }

      if ($data['quantity'] <  $order->quantity) {
        $diffQuantity = $order->quantity - $data['quantity'];
        $product->stock_quantity += $diffQuantity;
      }

      if (!$productTable->save($product)) {
        throw new HttpException('Error On Save Product', 422);
      }

      $order->quantity = $data['quantity'];
    } else if (isset($data['status'])) {
      $order->status = $data['status'];
    }

    if (!$this->Orders->save($order)) {
      throw new HttpException('Error On Save Order', 422);
    }

    $this->set(['order' => $order]);
    $this->viewBuilder()->setOption('serialize', ['order']);
  }
}
