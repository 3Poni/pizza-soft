<?php

namespace app\Http\Controllers\Api;

use App\Models\Order;
use vendor\Request\Request;
use vendor\Response\Response;
use vendor\Validator\Validator;

class OrderController
{
    private $request;
    private $response;
    public function __construct()
    {
        $this->request = new Request();
        $this->response = new Response();
    }

    public function index()
    {
        $orders = new Order();
        $query = $this->request->getQueryParams();

        if(isset($query['done'])) {
            $orders = $orders->filterBy('done', $query['done']);
        }else{
            $orders = $orders->getAll();
        }

        $this->response->setData($orders);
        $this->response->send();
    }
    public function show()
    {
        $id = $this->request->getQueryParam('id');
        $orders = (new Order())->getById($id);

        if($orders === null) {
            $this->response->not_found('Order not found');
        }
        $this->response->setData($orders);
        $this->response->send();
    }
    public function create()
    {
        $params = $this->request->getBodyParams();
        $rules = [
            'items' => 'required|array|min:1|max:5000|only_int',
        ];
        $validator = new Validator();
        $validator->validate($params, $rules);

        $item = [
            'items' => json_decode($params['items'], true),
            'done' => false,
        ];
        $created_order = (new Order())->create($item);

        if($created_order === null) {
            $this->response->setStatusCode(500);
            $this->response->setMessage('Failed to create order');
        }

        $this->response->setData($created_order);
        $this->response->setStatusCode(201);
        $this->response->send();
    }

    public function add()
    {
        $id = $this->request->getQueryParam('id');
        $body = $this->request->getBodyParams();
        $rules = [
            'items' => 'required|array|min:1|max:5000|only_int',
        ];
        $validator = new Validator();
        $validator->validate($body, $rules);

        $items = json_decode($body['items'], true);
        $order = new Order();
        $order_get = $order->getById($id);

        // из тз добавил странное правило, если done===false, то нельзя добавить items к заказу
        if($order_get === null || $order_get['done'] === false) {
            $this->response->not_found('Order not found');
        }else{
            $order_updated = $order->update($id, 'items', $items);
            $this->response->success($order_updated,'Order updated');
        }
    }
    public function done()
    {
        $id = $this->request->getQueryParam('id');
        $order = new Order();
        $order_get = $order->getById($id);

        if($order_get === null) {
            $this->response->not_found('Order not found');
        }else{
            $order_updated = $order->update($id, 'done', true);
            $this->response->success($order_updated,'Order updated');
        }

        $this->response->send();
    }
}