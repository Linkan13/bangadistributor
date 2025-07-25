<?php
namespace App\Services;

use Illuminate\Support\Arr;
use App\Repositories\OrderRepository;

class OrderService{

    protected $orderRepository;

    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function myPurchaseOrderList()
    {
        return $this->orderRepository->myPurchaseOrderList();
    }

    public function myPurchaseOrderListwithRN($data)
    {
        return $this->orderRepository->myPurchaseOrderListwithRN($data);
    }

    public function myPurchaseOrderListNotPaid()
    {
        return $this->orderRepository->myPurchaseOrderListNotPaid();
    }

    public function myPurchaseOrderPackageListShipped()
    {
        return $this->orderRepository->myPurchaseOrderPackageListShipped();
    }

    public function myPurchaseOrderPackageListRecieved()
    {
        return $this->orderRepository->myPurchaseOrderPackageListRecieved();
    }


    public function orderFindByID($id)
    {
        return $this->orderRepository->orderFindByID($id);
    }

    public function changeReceiveStatusByCustomer($request)
    {
        return $this->orderRepository->changeReceiveStatusByCustomer($request);
    }

    public function orderFindByOrderID($id)
    {
        return $this->orderRepository->orderFindByOrderID($id);
    }

    public function orderPackageFindByID($id)
    {
        return $this->orderRepository->orderPackageFindByID($id);
    }

    public function orderFindByOrderNumber($data, $user = null)
    {
        return $this->orderRepository->orderFindByOrderNumber($data, $user);
    }

    public function orderStore($data)
    {

        return $this->orderRepository->orderStore($data);
    }

    public function orderStoreForAPI($user, $data){
        return $this->orderRepository->orderStoreForAPI($user, $data);
    }

    public function orderStoreForInAppPurchse($user, $data)
    {
        return $this->orderRepository->orderStoreForInAppPurchse($user, $data);
    }

    public function orderPaymentDelete($id)
    {
        return $this->orderRepository->orderPaymentDelete($id);
    }

    public function getOrderToShip($user_id){
        return $this->orderRepository->getOrderToShip($user_id);
    }

    public function getOrderToReceive($user_id){
        return $this->orderRepository->getOrderToReceive($user_id);
    }

    public function getNumberOfOrdersCancelled($user){
        return $this->orderRepository->getNumberOfOrdersCancelled($user);
    }

    public function purchaseHistories($filter = null,$club_point=false){
        return $this->orderRepository->purchaseHistories($filter,$club_point);
    }

    public function getOrderPackage($data){
        return $this->orderRepository->getOrderPackage($data);
    }

    public function orderByDeliveryStatus($data, $user_id)
    {
        return $this->orderRepository->orderByDeliveryStatus($data, $user_id);
    }

}
