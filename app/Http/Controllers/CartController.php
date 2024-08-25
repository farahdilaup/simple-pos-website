<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Cart;

class CartController
{
    public function index(): View
    {
        $menu_ = [
            ['id' => 232, 'name' => 'Margherita Pizza', 'price' => 8.99],
            ['id' => 457, 'name' => 'Pepperoni Pizza', 'price' => 9.99],
            ['id' => 621, 'name' => 'BBQ Chicken Pizza', 'price' => 10.99],
            ['id' => 315, 'name' => 'Hawaiian Pizza', 'price' => 9.49],
            ['id' => 482, 'name' => 'Veggie Pizza', 'price' => 8.49],
            ['id' => 758, 'name' => 'Cheese Pizza', 'price' => 7.99],
            ['id' => 340, 'name' => 'Garlic Bread', 'price' => 4.99],
            ['id' => 679, 'name' => 'Caesar Salad', 'price' => 5.99],
            ['id' => 192, 'name' => 'Chicken Wings', 'price' => 6.99],
            ['id' => 530, 'name' => 'Mozzarella Sticks', 'price' => 5.49],
            ['id' => 469, 'name' => 'Onion Rings', 'price' => 4.49],
            ['id' => 781, 'name' => 'French Fries', 'price' => 3.99],
            ['id' => 290, 'name' => 'Spaghetti Bolognese', 'price' => 12.99],
            ['id' => 693, 'name' => 'Lasagna', 'price' => 13.49],
            ['id' => 176, 'name' => 'Chicken Alfredo', 'price' => 11.99],
            ['id' => 324, 'name' => 'Beef Stroganoff', 'price' => 14.99],
            ['id' => 548, 'name' => 'Shrimp Scampi', 'price' => 15.49],
            ['id' => 603, 'name' => 'Chicken Parmesan', 'price' => 12.49],
            ['id' => 437, 'name' => 'BBQ Ribs', 'price' => 16.99],
            ['id' => 752, 'name' => 'Grilled Salmon', 'price' => 17.49],
            ['id' => 198, 'name' => 'Tacos', 'price' => 8.49],
            ['id' => 483, 'name' => 'Burrito', 'price' => 9.99],
            ['id' => 572, 'name' => 'Quesadilla', 'price' => 10.49],
            ['id' => 671, 'name' => 'Nachos', 'price' => 7.99],
            ['id' => 230, 'name' => 'Fajitas', 'price' => 11.49],
            ['id' => 598, 'name' => 'Chimichanga', 'price' => 10.99],
            ['id' => 329, 'name' => 'Chili Con Carne', 'price' => 9.49],
            ['id' => 436, 'name' => 'Buffalo Wings', 'price' => 6.99],
            ['id' => 505, 'name' => 'Garlic Knots', 'price' => 4.49],
            ['id' => 316, 'name' => 'Stuffed Mushrooms', 'price' => 5.99],
            ['id' => 783, 'name' => 'Eggplant Parmesan', 'price' => 11.49],
            ['id' => 264, 'name' => 'Chicken Caesar Wrap', 'price' => 9.29],
            ['id' => 470, 'name' => 'Mediterranean Salad', 'price' => 7.99],
            ['id' => 592, 'name' => 'Fettuccine Alfredo', 'price' => 12.49],
            ['id' => 407, 'name' => 'Penne Arrabbiata', 'price' => 11.99],
            ['id' => 531, 'name' => 'Pork Schnitzel', 'price' => 13.49],
            ['id' => 220, 'name' => 'Beef Tacos', 'price' => 8.99],
            ['id' => 489, 'name' => 'Chicken Burrito', 'price' => 10.49],
            ['id' => 681, 'name' => 'Vegetable Stir Fry', 'price' => 9.49],
            ['id' => 313, 'name' => 'Seafood Pasta', 'price' => 14.99],
            ['id' => 749, 'name' => 'Lamb Chops', 'price' => 16.49],
            ['id' => 210, 'name' => 'Cobb Salad', 'price' => 8.99],
            ['id' => 578, 'name' => 'Pork Ribs', 'price' => 15.99],
            ['id' => 654, 'name' => 'Buffalo Chicken Pizza', 'price' => 11.49],
            ['id' => 329, 'name' => 'Pasta Carbonara', 'price' => 12.99],
            ['id' => 781, 'name' => 'Greek Salad', 'price' => 6.99],
            ['id' => 168, 'name' => 'Shrimp Tacos', 'price' => 9.49],
            ['id' => 494, 'name' => 'Cheeseburger', 'price' => 10.99],
            ['id' => 287, 'name' => 'Pork Belly', 'price' => 13.49],
            ['id' => 615, 'name' => 'Vegetable Soup', 'price' => 7.49],
            ['id' => 499, 'name' => 'Lentil Salad', 'price' => 6.99],
        ];

        // sort $menu_ by name
        usort($menu_, function ($a, $b) {
            return strcmp($a['name'], $b['name']);
        });

        return view('cart.index', [
            'menu_' => $menu_,
        ]);
    }

    public function submit(Request $request)
{
    try {
        // Validasi parameter POST
        $request->validate([
            'customer' => 'required|string',
            'items' => 'required|array',
            'items.*.name' => 'required|string',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        // Simpan data ke dalam database
        $cart = new Cart;
        $cart->customer = $request->customer; // Menggunakan 'customer' sesuai dengan nama yang dikirim
        $cart->items = json_encode($request->items); // Mengubah 'items' menjadi JSON sebelum disimpan
        $cart->save();

        session(['cart_data' => [
            'token' => csrf_token(),
            'customer' => $cart->customer,
            'items' => json_decode($cart->items, true),
        ]]);

        // Kembalikan data dalam format JSON
        return response()->json([
            'token' => csrf_token(),
            'customer' => $cart->customer,
            'items' => json_decode($cart->items, true), // Mengembalikan 'items' yang telah disimpan
        ], 200); // Status 200 OK

    } catch (\Illuminate\Validation\ValidationException $e) {
        // Tangani kesalahan validasi
        return response()->json([
            'error' => 'Validation error',
            'messages' => $e->errors(),
        ], 422); // Status 422 Unprocessable Entity

    } catch (\Exception $e) {
        // Tangani kesalahan umum
        return response()->json([
            'error' => 'An error occurred',
            'message' => $e->getMessage(),
        ], 500); // Status 500 Internal Server Error
    }
}

public function result()
{
    $data = session('cart_data');
    
    return view('cart.result', compact('data'));
}



    
}
