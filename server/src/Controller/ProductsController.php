<?php

declare(strict_types=1);

namespace App\Controller;

use Cake\Http\Exception\HttpException;
use Cake\View\JsonView;

/**
 * Products Controller
 *
 * @property \App\Model\Table\ProductsTable $Products
 */
class ProductsController extends AppController
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
        $products = $this->Products->find('all')->all();
        $this->set('products', $products);
        $this->viewBuilder()->setOption('serialize', ['products']);
    }

    /**
     * View method
     *
     * @param string $id Product id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id)
    {
        $product = $this->Products->get($id);
        $this->set('product', $product);
        $this->viewBuilder()->setOption('serialize', ['product']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $this->request->allowMethod(['post']);
        $product = $this->Products->newEntity($this->request->getData());
        if (!$this->Products->save($product)) {
            throw new HttpException('Erro On Save Product', 422);
        }

        $this->set(['product' => $product]);
        $this->viewBuilder()->setOption('serialize', ['product', 'message']);
        $this->response = $this->response->withStatus(201);
    }

    /**
     * Edit method
     *
     * @param string $id Product id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id)
    {
        $this->request->allowMethod(['put']);
        $product = $this->Products->get($id);
        $product = $this->Products->patchEntity($product, $this->request->getData());
        if (!$this->Products->save($product)) {
            throw new HttpException('Erro On Save Product', 422);
        }

        $this->set(['product' => $product]);
        $this->viewBuilder()->setOption('serialize', ['product']);
    }

    /**
     * Delete method
     *
     * @param string $id Product id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id)
    {
        $this->request->allowMethod(['delete']);
        $product = $this->Products->get($id);

        if (!$this->Products->delete($product)) {
            throw new HttpException('Erro On Delete Product', 422);
        }

        $this->viewBuilder()->setOption('serialize', []);
        $this->response = $this->response->withStatus(204);
    }
}
