
@php use App\Models\Product; @endphp
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Email Template for Order Confirmation Email</title>

        <!-- Start Common CSS -->
        <style type="text/css">
            #outlook a {padding:0;}
            body{width:100% !important; -webkit-text-size-adjust:100%; -ms-text-size-adjust:100%; margin:0; padding:0; font-family: Helvetica, arial, sans-serif;}
            .ExternalClass {width:100%;}
            .ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div {line-height: 100%;}
            .backgroundTable {margin:0; padding:0; width:100% !important; line-height: 100% !important;}
            .main-temp table { border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt; font-family: Helvetica, arial, sans-serif;}
            .main-temp table td {border-collapse: collapse;}
        </style>
        <!-- End Common CSS -->
    </head>
    <body>
        <table width="100%" cellpadding="0" cellspacing="0" border="0" class="backgroundTable main-temp" style="background-color: #d5d5d5;">
            <tbody>
                <tr>
                    <td>
                        <table width="600" align="center" cellpadding="15" cellspacing="0" border="0" class="devicewidth" style="background-color: #ffffff;">
                            <tbody>
                                <!-- Start header Section -->
                                <tr>
                                    <td style="padding-top: 30px;">
                                        <table width="560" align="center" cellpadding="0" cellspacing="0" border="0" class="devicewidthinner" style="border-bottom: 1px solid #eeeeee; text-align: center;">
                                            <tbody>
                                                <tr>
                                                    <td style="padding-bottom: 10px;">
                                                        <a href="https://htmlcodex.com"><img src="{{ asset('front/images/logo.png') }}" alt="PapaChina" /></a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="font-size: 14px; line-height: 18px; color: #666666;">
                                                       Lektumbas.id
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="font-size: 14px; line-height: 18px; color: #666666;">
                                                        Purworejo, Jawa Tengah, 54261
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="font-size: 14px; line-height: 18px; color: #666666;">
                                                        Phone: 082322875211| Email: info@hafidtech.com
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="font-size: 14px; line-height: 18px; color: #666666; padding-bottom: 25px;">
                                                        <strong>Order Number:</strong> {{ $order_id }} | <strong>Order Date:</strong> {{ date('F j, Y, g:i a', strtotime($orderDetails['created_at'])) }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="font-size: 14px; line-height: 18px; color: #666666; padding-bottom: 25px;">Your Order has been Shipped with below Tracking Details: <br>
                                                        <strong>Courier Name:</strong> {{ $courier_name }} | <strong>Tracking Number:</strong> {{ $tracking_number }}
                                                    </td>
                                                </tr>

                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                                <!-- End header Section -->

                                <!-- Start address Section -->
                                <tr>
                                    <td style="padding-top: 0;">
                                        <table width="560" align="center" cellpadding="0" cellspacing="0" border="0" class="devicewidthinner" style="border-bottom: 1px solid #bbbbbb;">
                                            <tbody>
                                                <tr>
                                                    <td style="width: 55%; font-size: 16px; font-weight: bold; color: #666666; padding-bottom: 5px;">
                                                        Delivery Address
                                                    </td>
                                                    <td style="width: 45%; font-size: 16px; font-weight: bold; color: #666666; padding-bottom: 5px;">
                                                        Billing Address
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 55%; font-size: 14px; line-height: 18px; color: #666666;">
                                                        {{ $orderDetails['name'] }}
                                                    </td>
                                                    <td style="width: 45%; font-size: 14px; line-height: 18px; color: #666666;">
                                                        {{ $orderDetails['user']['name'] }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 55%; font-size: 14px; line-height: 18px; color: #666666;">
                                                        {{ $orderDetails['address'] }}
                                                    </td>
                                                    <td style="width: 45%; font-size: 14px; line-height: 18px; color: #666666;">
                                                        {{ $orderDetails['user']['address'] }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 55%; font-size: 14px; line-height: 18px; color: #666666; padding-bottom: 10px;">
                                                        {{ $orderDetails['city'] }}, {{ $orderDetails['pincode'] }}
                                                    </td>
                                                    <td style="width: 45%; font-size: 14px; line-height: 18px; color: #666666; padding-bottom: 10px;">
                                                        {{ $orderDetails['user']['city'] }}, {{ $orderDetails['user']['pincode'] }}
                                                    </td>
                                                </tr>

                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                                <!-- End address Section -->

                                <!-- Start product Section -->
                                @php $total_price = 0 @endphp
                                @foreach($orderDetails['orders_products'] as $order)
                                @php
                                $total_price = $total_price + ($order['product_price'] * $order['product_qty'])
                                @endphp
                                <tr>
                                    <td style="padding-top: 0;">
                                        <table width="560" align="center" cellpadding="0" cellspacing="0" border="0" class="devicewidthinner" style="border-bottom: 1px solid #eeeeee;">
                                            <tbody>
                                                <tr>
                                                    <td rowspan="4" style="padding-right: 10px; padding-bottom: 10px;">
                                                        @php $getProductImage = Product::getProductImage($order['product_id']) @endphp
                                                            @if($getProductImage != "")
                                                        <a href="{{ url('product/'.$order['product_id']) }}" target="_blank">
                                                            <img style="height: 80px;" src="{{ asset('front/images/products/small/'.$getProductImage) }}" alt="Product Image" />
                                                        </a>
                                                        @else
                                                            <a href="{{ url('product/'.$order['product_id']) }}" target="_blank">
                                                                <img style="height: 80px;" src="{{ asset('front/images/product/sitemakers-tshirt.png') }}" alt="Product Image" />
                                                            </a>
                                                            @endif
                                                    </td>
                                                    <td colspan="2" style="font-size: 14px; font-weight: bold; color: #666666; padding-bottom: 5px;">
                                                        {{ $order['product_name'] }}
                                                        {{ $order['product_code'] }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="font-size: 14px; line-height: 18px; color: #757575; width: 440px;">
                                                        Quantity: {{ $order['product_qty'] }}
                                                    </td>
                                                    <td style="width: 130px;"></td>
                                                </tr>
                                                <tr>
                                                    <td style="font-size: 14px; line-height: 18px; color: #757575;">
                                                        Color: {{ $order['product_color'] }}
                                                    </td>
                                                    <td style="font-size: 14px; line-height: 18px; color: #757575; text-align: right;">

                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="font-size: 14px; line-height: 18px; color: #757575; padding-bottom: 10px;">
                                                        Size: {{ $order['product_size'] }}
                                                    </td>
                                                    <td style="font-size: 14px; line-height: 18px; color: #757575; text-align: right; padding-bottom: 10px;">
                                                        <b style="color: #666666;">Rp.{{ $order['product_price'] }}</b> Total
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                                @endforeach
                                <!-- End product Section -->

                                <!-- Start calculation Section -->
                                <tr>
                                    <td style="padding-top: 0;">
                                        <table width="560" align="center" cellpadding="0" cellspacing="0" border="0" class="devicewidthinner" style="border-bottom: 1px solid #bbbbbb; margin-top: -5px;">
                                            <tbody>
                                                <tr>
                                                    <td rowspan="5" style="width: 55%;"></td>
                                                    <td style="font-size: 14px; line-height: 18px; color: #666666;">
                                                        Sub-Total:
                                                    </td>
                                                    <td style="font-size: 14px; line-height: 18px; color: #666666; width: 130px; text-align: right;">
                                                        Rp.{{ $total_price }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="font-size: 14px; line-height: 18px; color: #666666; padding-bottom: 10px; border-bottom: 1px solid #eeeeee;">
                                                        Shipping Fee:
                                                    </td>
                                                    <td style="font-size: 14px; line-height: 18px; color: #666666; padding-bottom: 10px; border-bottom: 1px solid #eeeeee; text-align: right;">
                                                       Rp.{{ $orderDetails['shipping_charges'] }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="font-size: 14px; font-weight: bold; line-height: 18px; color: #666666; padding-top: 10px;">
                                                        Coupon Discount
                                                    </td>
                                                    <td style="font-size: 14px; font-weight: bold; line-height: 18px; color: #666666; padding-top: 10px; text-align: right;">
                                                        @if($orderDetails['coupon_amount'] > 0)
                                                        Rp.{{ $orderDetails['coupon_amount'] }}
                                                        @else
                                                        Rp.0
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="font-size: 14px; font-weight: bold; line-height: 18px; color: #666666; padding-top: 10px;">
                                                        Order Total
                                                    </td>
                                                    <td style="font-size: 14px; font-weight: bold; line-height: 18px; color: #666666; padding-top: 10px; text-align: right;">
                                                        Rp.{{ $orderDetails['grand_total'] }}
                                                    </td>
                                                </tr>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                                <!-- End calculation Section -->

                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
    </body>
</html>
