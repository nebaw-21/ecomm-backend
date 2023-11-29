<?php

namespace App\Http\Controllers;

use App\Models\Color;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    // Create a new order
    public function addOrders(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'status' => 'required',
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.name' => 'required',
            'items.*.color' => 'required',
            'items.*.image' => 'required',
            'items.*.price' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Create a new order
        $order = Order::create([
            'user_id' => $request->input('user_id'),
            'status' => $request->input('status'),
        ]);

        // Convert the items JSON string to an array of objects
        $items = $request->input('items');

        // Create order items
        foreach ($items as $item) {
            $order->OrderItems()->create([
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'name' => $item['name'],
                'color' => $item['color'],
                'size' => $item['size'],
                'image' => $item['image'],
                'price' => $item['price'],
            
                // Set values for other columns in the order_item table if applicable
            ]);
        }

        // Return the created order
        return response()->json(['order' => $order], 200);
    }
    
    public function userOrderHistory($id)
    {
        try {
            // Fetch order details from orders table
            $user = User::findOrFail($id);
            $orderIds = $user->orders->pluck('id');
    
            $orderItemsData = [];
    
            foreach ($orderIds as $orderId) {
                $order = Order::findOrFail($orderId);
    
                $orderStatus = $order->status;
                $orderDate = $order->created_at;
    
                $orderItems = $order->OrderItems->toArray();
    
                // Attach orderDate and orderStatus to each orderItem array
                $orderItemsWithInfo = array_map(function ($orderItem) use ($orderDate, $orderStatus) {
                    $orderItem['orderDate'] = $orderDate;
                    $orderItem['orderStatus'] = $orderStatus;
                    return $orderItem;
                }, $orderItems);
    
                $orderItemsData = array_merge($orderItemsData, $orderItemsWithInfo);
            }
    
            $orderHistory = [
                'orderItemData' => $orderItemsData,
            ];
    
            return response()->json($orderHistory, 200);
        } catch (\Exception $e) {
            // Handle the exception
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }
    public function displayAllOrder()
    {
        $orders = Order::all();
    
        $orderData = [];
    
        foreach ($orders as $order) {
            $orderId = $order->id;
            $orderDate = $order->created_at;
            $status = $order->status;
            $name = $order->User->fname;
            $lname = $order->User->lname;
    
            $orderData[] = [
                'orderId' => $orderId,
                'orderDate' => $orderDate,
                'status' => $status,
                'name' => $name,
                'lname'=>$lname,
            ];
        }
    
        return $orderData;
    }

    public function orderDetailForAdmin($id)
    {
        $order = Order::find($id);
    
        $orderData = [];
    
        if ($order) {
            $orderItems = $order->OrderItems;
    
            $orderItemsData = [];
            $totalPrice = 0; // Variable to store the total price
    
            foreach ($orderItems as $orderItem) {
                $quantity = $orderItem->quantity;
                $name = $orderItem->name;
                $color = $orderItem->color;
                $size = $orderItem->size;
                $image = $orderItem->image;
                $price = $orderItem->price;
    
                $totalPrice += floatval($price); // Accumulate the total price
    
                $orderItemsData[] = [
                    'quantity' => $quantity,
                    'name' => $name,
                    'color' => $color,
                    'size' => $size,
                    'image' => $image,
                    'price' => $price,
                ];
            }
            $orderId = $order->id;
            $orderDate = $order->created_at;
            $status = $order->status;
            $fname = $order->User->fname;
            $lname = $order->User->lname;
            $phone = $order->User->phone;
            $email = $order->User->email;
            $country = $order->User->country;
            $city = $order->User->city;
    
            $orderData = [
                'orderId' => $orderId,
                'orderDate' => $orderDate,
                'status' => $status,
                'fname' => $fname,
                'lname' => $lname,
                'phone' => $phone,
                'email' => $email,
                'country' => $country,
                'city' => $city,
                'orderItems' => $orderItemsData,
                'totalPrice' => $totalPrice, // Include the total price in the response data
            ];
        }
    
        return $orderData;
    }


}