<?php


// Read the JSON file contents
$jsonData = file_get_contents('Narvesen_Order_Client.json');
$orderData = json_decode($jsonData);
//Products list JSON
$jsonData2 = file_get_contents('Randall.json');
$orderData2 = json_decode($jsonData2);
//Client's  JSON

// Check if the decoding was successful
if ($orderData !== null && $orderData2 !== null
    && isset($orderData->CompanyName,
        $orderData->ClientsOrder)
    && isset($orderData2->name,
        $orderData2->description,
        $orderData2->MoneyForShop)
    && is_array($orderData->ClientsOrder))
{
    // Access in the CompanyName
    $companyName =
        $orderData->CompanyName;
    // Access Client's name and money for shop
    $randallName =
        $orderData2->name;
    $randallMoney =
        $orderData2->MoneyForShop;

    // Set Empty values for later processing
    $totalQuantity = 0;
    $totalPrice = 0;

    // Set Empty Cart to later fill it with Items
    $cartItems = [];

    // Decide list of products,names and the price tags
    echo "List of Products:\n";

    foreach
    ($orderData->ClientsOrder as $order)
    {
        echo "Product: $order->Product,
         Name: $order->Name,
          Price: $ $order->Price\n";
    }

    // Add item to the cart
    $addToCart = true;
    while ($addToCart)
    {
        // Ask Client What product he/she wants to choose
        echo "\nSelect a product to add to the cart (enter 'quit' to stop): ";
        $selectedProduct =
            (string)strtolower
            (readline());

        if (strtolower
            ($selectedProduct) === 'quit')
        {
            $addToCart = false;
            continue;
        }
        echo "Enter the amount: ";
        $amount = intval(readline());

        // Search product in the list of Products
        $selectedOrder = null;

        foreach
        ($orderData->ClientsOrder
         as $order)
        {

            if (strtolower($order->Product)
                === $selectedProduct)
            {

                $selectedOrder = $order;
                break;
            }
        }

        // Selected product will be added to Cart
        if ($selectedOrder !==
            null && $amount > 0)
        {
            $itemTotalPrice =
                $selectedOrder->Price * $amount;

            $cartItems[] = [
                'Product' => $selectedOrder->Product,
                'Name' => $selectedOrder->Name,
                'Price' => $selectedOrder->Price,
                'Quantity' => $amount,
                'TotalPrice' => $itemTotalPrice
            ];

            echo "Item added to the cart.\n";
        } else {
            echo "Invalid product or amount.\n";
        }
    }

    // Display items in the cart, their price tag, and total amount for the cart
    echo "\nItems in the Cart:\n";

    foreach
    ($cartItems as $item)
    {
        echo "Product: {$item['Product']},"
            ." Name: {$item['Name']},"
            ." Price: $ {$item['Price']},"
            ." Quantity: {$item['Quantity']},"
            ." Total Price: $ {$item['TotalPrice']}\n";

        $totalQuantity += $item['Quantity'];
        $totalPrice += $item['TotalPrice'];
    }

    echo "\nTotal Quantity in Cart: $totalQuantity\n";
    echo "Total Price for Cart: $ $totalPrice\n";

    // Purchase cart , when it's not empty
    if (!empty($cartItems))
    {
        echo "\nDo you want to purchase"
            ." the items in the cart? (yes/no): ";

        $purchaseDecision = readline();
        if (strtolower
            ($purchaseDecision) === 'yes')
        {
            // Check if Client has enough money to make the purchase
            $cartTotal =
                array_sum(array_column
                ($cartItems, 'TotalPrice'));

            if ($cartTotal <= $randallMoney)
            {
                // Deduct the purchase amount from Client's money
                $randallMoney -= $cartTotal;
                // Display purchase success message
                echo "Items purchased successfully by $randallName"
                . "Remaining money for shop: $ $randallMoney\n";
                // Clear the cart after purchase
                $cartItems = [];

            } else {
                // Display message that the person don't have money for that
                echo "$randallName does not have enough"
                    ." money to make the purchase.\n";
            }
        }

    } else {
        echo "No items in the cart.\n";
    }

} else {
    // Message if JSON is not working
    echo "Error decoding JSON data or missing required data\n";
}


