<?php

declare(strict_types=1);

namespace App\Controller;

use Cake\Event\EventInterface;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\HttpException;
use Cake\Log\Log;
use Cake\View\JsonView;

/**
 * Customers Controller
 *
 * @property \App\Model\Table\CustomersTable $Customers
 */
class CustomersController extends AppController
{
    public function viewClasses(): array
    {
        return [JsonView::class];
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $customers = $this->Customers->find('all')->all();
        $this->set('customers', $customers);
        $this->viewBuilder()->setOption('serialize', ['customers']);
    }

    /**
     * View method
     *
     * @param string $id Customer id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id)
    {
        $customer = $this->Customers->get($id, contain: ['Orders']);
        $this->set('customer', $customer);
        $this->viewBuilder()->setOption('serialize', ['customer']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $this->request->allowMethod(['post']);
        $customer = $this->Customers->newEntity($this->request->getData());

        if (!$this->Customers->save($customer)) {
            throw new HttpException('Error On Save Customer', 422);
        }

        $this->set(['customer' => $customer]);
        $this->viewBuilder()->setOption('serialize', ['customer', 'message']);
        $this->response = $this->response->withStatus(201);
    }

    /**
     * Edit method
     *
     * @param string $id Customer id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id)
    {
        $this->request->allowMethod(['put']);
        $customer = $this->Customers->get($id);
        $customer = $this->Customers->patchEntity($customer, $this->request->getData());
        if (!$this->Customers->save($customer)) {
            throw new HttpException('Error On Save Customer', 422);
        }

        $this->set(['customer' => $customer]);
        $this->viewBuilder()->setOption('serialize', ['customer']);
    }

    /**
     * Delete method
     *
     * @param string $id Customer id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id)
    {
        $this->request->allowMethod(['delete']);
        $customer = $this->Customers->get($id);
        if (!$this->Customers->delete($customer)) {
            throw new HttpException('Error On Delete Customer', 422);
        }

        $this->viewBuilder()->setOption('serialize', []);
        $this->response = $this->response->withStatus(204);
    }

    public function viewOrders($id)
    {
        $status = $this->request->getQuery('status');

        if ($status) {
            $validStatuses = ['new', 'processed', 'shipped', 'cancelled'];
            if (!in_array($status, $validStatuses)) {
                throw new BadRequestException('Invalid status parameter.');
            }

            $customer = $this->Customers->get($id, [
                'contain' => ['Orders' => function ($q) use ($status) {
                    return $q->where(['Orders.status' => $status]);
                }],
            ]);
        } else {
            $customer = $this->Customers->get($id, [
                'contain' => ['Orders'],
            ]);
        }

        Log::info(json_encode($customer));
        $this->set(['customer' => $customer]);
        $this->viewBuilder()->setOption('serialize', ['customer']);
    }
}
