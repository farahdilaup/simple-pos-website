<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-8 order-md-1 order-2">
                <div class="input-group mb-3 bg-light border-bottom">
                    <input type="text" id="searchInput" class="form-control" placeholder="Search by name or amount">
                    <button class="btn btn-outline-secondary" type="button"><i class="fas fa-search"></i></button>
                </div>
                    <div class="row menu-items g-0" id="menuItems">
                        @foreach ($menu_ as $menu)
                            <div class="col-md-3 col-sm-6 menu-item" data-name="{{ strtolower($menu['name']) }}" data-price="{{ $menu['price'] }}" data-title="{{ $menu['name'] }}">
                                <div class="card h-100">
                                    <div class="card-body d-flex flex-column justify-content-between">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h5 class="card-title text-truncate mb-0">{{ $menu['name'] }}</h5>
                                            <button class="btn btn-light add-to-cart">
                                                <i class="fas fa-plus text-secondary"></i>
                                            </button>
                                        </div>
                                        <p class="card-text text-end">${{ $menu['price'] }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            <div class="col-md-4 order-md-2 order-1">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title d-flex align-items-center">
                            <i class="fas fa-shopping-cart me-2"></i> Cart
                        </h5>
                            <!-- Baris untuk total harga di kiri dan jumlah item di kanan -->
                            <div class="d-flex justify-content-between mb-3">
                                <p>Total: $<span id="totalPrice">0.00</span></p>
                                <p>Jumlah Item: <span id="itemCount">0</span></p>
                            </div>
                            <!-- Form nama pelanggan dengan logo customer -->
                            <div class="d-flex align-items-center mb-3">
                                <i class="fas fa-user me-2"></i>
                                <form id="customerForm" class="w-100">
                                    <div class="mb-0">
                                        <input type="text" id="customerName" class="form-control" placeholder="Customer name" aria-describedby="customerNameError">
                                        <div id="customerNameError" class="invalid-feedback position-absolute" style="top: 100%; left: 0; transform: translateY(5px);"></div>
                                    </div>
                                </form>
                            </div>
                            <!-- Daftar item  -->
                            <div id="cartItems"> 
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="fixed-bottom bg-light py-2">
        <div class="container">
            <button class="btn btn-secondary float-end" id="submitButton">
                <i class="fas fa-paper-plane"></i> Submit
            </button>
        </div>
    </div>

    <!-- Modal Error -->
    <div class="modal fade modal-error" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <!-- Mengubah warna teks menjadi merah menggunakan kelas `text-danger` -->
                    <h5 class="modal-title text-danger" id="errorModalLabel">Error</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Please fill out all required fields and add items to the cart.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Confirm -->
    <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmModalLabel">Confirm Submission</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to submit the form?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                    <!-- Tombol Submit di dalam Form -->
                    <button type="button" class="btn btn-secondary" id="submitCartButton">
                        <i class="fas fa-paper-plane"></i> Submit
                    </button>
                </div>
            </div>
        </div>
    </div>


    <script>
        const customerNameInput = document.getElementById('customerName');
        const errorContainer = document.getElementById('customerNameError');

        customerNameInput.addEventListener('blur', function() {
            if (!customerNameInput.value.trim()) {
                customerNameInput.classList.add('is-invalid');
                errorContainer.textContent = 'Customer name cannot be empty';
            } else {
                customerNameInput.classList.remove('is-invalid');
                errorContainer.textContent = '';
            }
        });

        customerNameInput.addEventListener('focus', function() {
            customerNameInput.classList.remove('is-invalid');
            errorContainer.textContent = '';
        });
    </script>

    <script>
        const cart = [];

        document.getElementById('searchInput').addEventListener('input', function() {
            const searchValue = this.value.toLowerCase();
            const menuItems = document.querySelectorAll('.menu-item');

            menuItems.forEach(item => {
                const itemName = item.getAttribute('data-name');
                if (itemName.includes(searchValue)) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });

        document.querySelectorAll('.add-to-cart').forEach(button => {
            button.addEventListener('click', function() {
                const itemElement = this.closest('.menu-item');

                const itemName = itemElement.querySelector('.card-title').textContent.trim();
                const itemPrice = parseFloat(itemElement.querySelector('.card-text').textContent.replace('$', '').trim());

                let cartItem = cart.find(item => item.name === itemName);

                if (cartItem) {
                    cartItem.quantity++;
                } else {
                    cartItem = { name: itemName, price: itemPrice, quantity: 1 };
                    cart.push(cartItem);
                }

                updateCart();
            });
        });

        function updateCart() {
        const cartItemsElement = document.getElementById('cartItems');
        cartItemsElement.innerHTML = '';
        let totalPrice = 0;
        let itemCount = 0;

        cart.forEach(item => {
        totalPrice += item.price * item.quantity;
        itemCount += item.quantity;

        const itemElement = document.createElement('div');
        itemElement.className = 'd-flex justify-content-between align-items-center mb-2';
        itemElement.innerHTML = `
            <span>${item.name}</span>
            <div class="d-flex align-items-center">
                <input type="number" class="form-control form-control-sm quantity-input me-2" value="${item.quantity}" min="1" data-name="${item.name}" style="width: 70px;">
                <button class="btn btn-sm btn-danger remove-item" data-name="${item.name}">
                    <i class="fas fa-trash"></i>
                </button>
                <div id="${item.name}-error" class="invalid-feedback position-absolute" style="top: 100%; left: 0; transform: translateY(5px);" role="alert"></div>
            </div>
        `;

        const quantityInput = itemElement.querySelector('.quantity-input');
        const errorContainer = itemElement.querySelector(`#${item.name}-error`);

        quantityInput.addEventListener('input', function() {
            const value = parseInt(quantityInput.value, 10);

            if (isNaN(value) || value < 1) {
                quantityInput.classList.add('is-invalid');
                errorContainer.textContent = 'The number must be greater than 0';
                errorContainer.classList.add('d-block');
            } else {
                quantityInput.classList.remove('is-invalid');
                errorContainer.textContent = '';
                errorContainer.classList.remove('d-block');

                // Update cart item quantity
                const itemName = quantityInput.getAttribute('data-name');
                const cartItem = cart.find(i => i.name === itemName);
                if (cartItem) {
                    cartItem.quantity = value;
                }
                    // Recalculate total price and item count
                    updateTotals();
                }
            });

            cartItemsElement.appendChild(itemElement);
        });

        document.getElementById('totalPrice').innerText = totalPrice.toFixed(2);
        document.getElementById('itemCount').innerText = itemCount;

        attachCartEvents();
    }
        function attachCartEvents() {
            document.querySelectorAll('.decrease-quantity').forEach(button => {
                button.addEventListener('click', function() {
                    const itemName = this.getAttribute('data-name');
                    const cartItem = cart.find(item => item.name === itemName);
                    if (cartItem.quantity > 1) {
                        cartItem.quantity--;
                    } else {
                        cart.splice(cart.indexOf(cartItem), 1);
                    }
                    updateCart();
                });
            });

            document.querySelectorAll('.increase-quantity').forEach(button => {
                button.addEventListener('click', function() {
                    const itemName = this.getAttribute('data-name');
                    const cartItem = cart.find(item => item.name === itemName);
                    cartItem.quantity++;
                    updateCart();
                });
            });

            document.querySelectorAll('.remove-item').forEach(button => {
                button.addEventListener('click', function() {
                    const itemName = this.getAttribute('data-name');
                    const cartItem = cart.find(item => item.name === itemName);
                    cart.splice(cart.indexOf(cartItem), 1);
                    updateCart();
                });
            });

            document.querySelectorAll('.quantity-input').forEach(input => {
                input.addEventListener('change', function() {
                    const itemName = this.getAttribute('data-name');
                    const newQuantity = parseInt(this.value, 10);

                    if (newQuantity > 0) {
                        const cartItem = cart.find(item => item.name === itemName);
                        if (cartItem) {
                            cartItem.quantity = newQuantity;
                        }
                        updateCart();
                    }
                });
            });
        }

        const submitCartUrl = '{{ route("cart.submit") }}';
 
        document.getElementById('submitCartButton').addEventListener('click', submitCartData);

        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        function submitCartData() {
            // Get customer name
            const customerNameInput = document.getElementById('customerName');
            const customer = customerNameInput ? customerNameInput.value.trim() : '';

            // Input validation
            if (!customer) {
                alert('Please enter your name.');
                return;
            }

            // Get selected items from cart
            const items = [];
            document.querySelectorAll('#cartItems .d-flex').forEach(itemElement => {
                const itemName = itemElement.querySelector('.quantity-input').getAttribute('data-name');
                const itemQuantity = parseInt(itemElement.querySelector('.quantity-input').value, 10);

                if (itemName && itemQuantity > 0) {
                    items.push({ name: itemName, quantity: itemQuantity });
                }
            });

            // Check if any items are selected
            if (items.length === 0) {
                alert('Your cart is empty.');
                return;
            }

            // Submit data using fetch API
            fetch('/cart/submit', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ customer, items })
            })
            .then(response => response.json())
            .then(data => {
                // Handle successful submission
                console.log('Cart submitted successfully:', data);
                alert('Your cart has been submitted successfully!');
                window.location.href = '/cart/result';
            })
            .catch(error => {
                // Handle errors
                console.error('Error submitting cart data:', error);
                // alert('An error occurred while submitting your cart. Please try again., error');
            });
        }

    </script>

    <script>
        document.getElementById('submitButton').addEventListener('click', function() {
            const customerNameInput = document.getElementById('customerName').value.trim();
            const cartItems = document.querySelectorAll('#cartItems .quantity-input');
            let isFormValid = true;
            let hasItems = cartItems.length > 0;

            // Clear previous errors
            document.getElementById('customerName').classList.remove('is-invalid');
            document.getElementById('customerNameError').textContent = '';

            cartItems.forEach(input => {
                if (!input.value || parseInt(input.value, 10) < 1) {
                    isFormValid = false;
                }
            });

            if (!customerNameInput) {
                document.getElementById('customerName').classList.add('is-invalid');
                document.getElementById('customerNameError').textContent = 'Customer name is required.';
                isFormValid = false;
            }

            if (!hasItems) {
                isFormValid = false;
            }

            if (!isFormValid) {
                var myErrorModal = new bootstrap.Modal(document.getElementById('errorModal'));
                myErrorModal.show();
            } else {
                var myConfirmModal = new bootstrap.Modal(document.getElementById('confirmModal'));
                myConfirmModal.show();
            }
        });
    </script>



    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
