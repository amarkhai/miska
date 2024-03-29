<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartModel extends Model
{
    public $items = null;
    public $totalQty = 0;
    public $totalPrice = 0;

    public function __construct($oldCart)
    {
        if($oldCart){
            $this->items = $oldCart->items;
            $this->totalQty = $oldCart->totalQty;
            $this->totalPrice = $oldCart->totalPrice;
        }
    }

    public function add($item, $id)
    {
        $storedItem = [
            'qty' => 0,
            'price' => $item->price,
            'item' => $item
        ];
        if($this->items){
            if(array_key_exists($id, $this->items)){
                $storedItem = $this->items[$id];
            }
        }
        $storedItem['qty']++;
        $storedItem['price'] = $item->price * $storedItem['qty'];
        $this->items[$id] = $storedItem;
        $this->totalQty++;
        $this->totalPrice += $item->price;
    }

    public function remove($item, $id)
    {
        if($this->items && array_key_exists($id, $this->items)){
            $storedItem = $this->items[$id];
            $storedItem['qty']--;
            $storedItem['price'] = $item->price * $storedItem['qty'];
            if($storedItem['qty'] == 0){
                unset($this->items[$id]);
            } else {
                $this->items[$id] = $storedItem;
            }
            $this->totalQty--;
            $this->totalPrice -= $item->price;
        }
    }

    public function removeAllById($item, $id)
    {
        if($this->items && array_key_exists($id, $this->items)){
            $storedItem = $this->items[$id];
            $this->totalQty -= $storedItem['qty'];
            $this->totalPrice -= $storedItem['price'];
            unset($this->items[$id]);
        }
    }
}