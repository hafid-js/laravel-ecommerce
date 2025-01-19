<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\OrdersLog;
use Illuminate\Support\Facades\Mail;
use Session;
use Auth;

class OrderController extends Controller
{
    public function orders() {
        Session::put('page','orders');
    $orders = Order::with('orders_products','user')->orderBy('id','DESC')->get()->toArray();
    return view('admin.orders.orders')->with(compact('orders'));
    }

    public function orderDetails($id){
        $orderDetails = Order::with('orders_products','user','log')->where('id',$id)->first()->toArray();
        $orderStatuses = OrderStatus::where('status',1)->get()->toArray();
        // dd($orderStatuses);
        return view('admin.orders.order_detail')->with(compact('orderDetails','orderStatuses'));
    }

    public function updateOrderStatus(Request $request) {
        if($request->isMethod('post')){
            $data = $request->all();

            Order::where(
                'id',$data['order_id']
                )->update([
                'order_status' => $data['order_status']
            ]);

            // update courier name and tracking number
            if(!empty($data['courier_name']) && !empty($data['tracking_number'])){
                Order::where('id',$data['order_id'])->update([
                    'courier_name' => $data['courier_name'],
                    'tracking_number' => $data['tracking_number']
                ]);

             // send order shipped email to customer with tracking details
            $orderDetails = Order::with('orders_products','user')->where('id',$data['order_id'])->first()->toArray();

            // send order shipped email
            $email = $orderDetails['user']['email'];
            $messageData = [
                'email' => $email,
                'name' => $orderDetails['user']['name'],
                'order_id' => $data['order_id'],
                'order_status' => $data['order_status'],
                'courier_name' => $data['courier_name'],
                'tracking_number' => $data['tracking_number'],
                'orderDetails' => $orderDetails
            ];
            Mail::send('emails.shipped_order',$messageData, function($message) use($email){
                $message->to($email)->subject('Order Placed - SiteMakers');
            });

            }

            // insert order status in order logs
            $log = new OrdersLog;
            $log->order_id = $data['order_id'];
            $log->order_status = $data['order_status'];
            $log->save();

            $message = "Order Status has been updated successfully!";
            return redirect()->back()->with('success_message',$message);
        }
    }

    public function printHTMLOrderInvoice($order_id) {
        $orderDetails = Order::with('orders_products','user')->where('id',$order_id)->first()->toArray();
        return view('admin.orders.print_html_order_invoice')->with(compact('orderDetails'));
    }
}
