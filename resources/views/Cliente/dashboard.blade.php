<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <link rel="shortcut icon" href="{{ asset('images/icono.ico') }}" type="image/x-icon">
    <title>Eatsy - Auto Servicio</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        input[type="checkbox"]:not([disabled]) {
            cursor: pointer !important;
        }
        input[type="checkbox"].text-orange-600 {
            accent-color: #ff5722 !important;
        }
        input[type="checkbox"]:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        .animate-pulse {
            animation: pulse 0.5s ease-in-out 2;
        }
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.2); }
            100% { transform: scale(1); }
        }
        /* Hide scrollbar but keep functionality */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</head>

<body class="bg-gray-100 font-sans">
    <div class="container mx-auto p-4 sm:p-6" x-data="app()">
        <header class="mb-6 sm:mb-8 bg-gradient-to-r from-orange-500 to-orange-700 text-white rounded-xl shadow-xl p-4 sm:p-6 flex flex-col sm:flex-row items-center sm:justify-between">
            <div x-show="showNotification" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 translate-y-4"
                 class="fixed bottom-16 sm:bottom-20 right-4 bg-green-500 text-white p-3 sm:p-4 rounded-lg shadow-lg z-50 flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                <span x-text="notificationMessage"></span>
            </div>

            <div class="flex items-center mb-4 sm:mb-0">
                <div class="bg-white p-2 sm:p-3 rounded-lg shadow-sm">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-24 sm:h-32 md:h-40 w-auto object-contain">
                </div>
            </div>
            <div class="text-center sm:text-left">
                <h1 class="text-2xl sm:text-3xl md:text-4xl font-extrabold tracking-tight cursor-pointer" @click="showLogoutModal = true">Catálogo de Productos</h1>
                <p class="mt-2 sm:mt-3 text-base sm:text-lg md:text-xl opacity-90">Explora nuestro menú y personaliza tu pedido</p>
            </div>
        </header>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-4 sm:gap-6 lg:gap-8">
            <div class="lg:col-span-3">
                <div class="mb-6 sm:mb-8 overflow-x-auto no-scrollbar pb-2">
                    <div class="inline-flex items-center space-x-2 sm:space-x-3">
                        <a href="#" @click.prevent="loadProducts(null)"
                        class="px-4 sm:px-6 py-2 sm:py-3 bg-white rounded-lg shadow text-gray-700 text-sm sm:text-base md:text-lg hover:bg-gray-50 transition transform hover:scale-105"
                        :class="{ 'border-b-4 border-orange-600': !categoria_id }">
                            Todas
                        </a>
                        <template x-for="categoriaPipe in categorias" :key="categoriaPipe.id">
                            <a href="#" @click.prevent="loadProducts(categoriaPipe.id)"
                            class="px-4 sm:px-6 py-2 sm:py-3 bg-white rounded-lg shadow text-gray-700 text-sm sm:text-base md:text-lg hover:bg-gray-50 transition transform hover:scale-105"
                            :class="{ 'border-b-4 border-orange-600': categoria_id == categoriaPipe.id }">
                                <span x-text="categoriaPipe.nombre"></span>
                            </a>
                        </template>
                    </div>
                </div>


                <div x-show="true" x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3 gap-4 sm:gap-6 lg:gap-8">
                    <template x-for="producto in productos.data" :key="producto.id">
                        <div class="bg-white rounded-xl shadow-lg overflow-hidden flex flex-col min-h-[350px] sm:min-h-[400px]" x-data="{ open: false }">
                            <img :src="producto.imagen" :alt="producto.nombre" class="w-full h-48 sm:h-56 object-cover">
                            <div class="p-4 sm:p-6">
                                <h2 class="text-lg sm:text-xl md:text-2xl font-semibold text-gray-800" x-text="producto.nombre"></h2>
                                <p class="text-gray-600 mt-2 text-sm sm:text-base md:text-lg line-clamp-3" x-text="producto.descripcion"></p>
                            </div>
                            <div class="px-4 sm:px-6 pb-4">
                                <p class="text-base sm:text-lg md:text-xl font-bold text-orange-600" x-text="'$' + parseFloat(producto.precio).toFixed(2)"></p>
                                <div class="mt-2 sm:mt-3 flex flex-wrap gap-2">
                                    <template x-for="categoriaPipe in producto.categorias" :key="categoriaPipe.id">
                                        <span class="inline-block bg-gray-200 text-gray-700 text-xs sm:text-sm px-2 py-1 rounded-full" x-text="categoriaPipe.nombre"></span>
                                    </template>
                                </div>
                            </div>
                            <div class="px-4 sm:px-6 pb-4 flex flex-col justify-end flex-1">
                                <template x-if="producto.ingredientes && producto.ingredientes.length > 0">
                                    <div>
                                        <button type="button" @click="open = !open" class="text-orange-600 hover:text-orange-800 flex items-center text-sm sm:text-base md:text-lg transition transform hover:scale-105">
                                            <span>Personalizar Ingredientes</span>
                                            <svg :class="{ 'rotate-180': open }" class="w-4 h-4 sm:w-5 sm:h-5 md:w-6 md:h-6 ml-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </button>
                                        <div x-show="open" x-transition:enter="transition ease-out duration-300"
                                             x-transition:enter-start="opacity-0 translate-y-2"
                                             x-transition:enter-end="opacity-100 translate-y-0"
                                             x-transition:leave="transition ease-in duration-200"
                                             x-transition:leave-start="opacity-100 translate-y-0"
                                             x-transition:leave-end="opacity-0 translate-y-2"
                                             class="mt-3">
                                            <h3 class="text-sm sm:text-base md:text-md font-medium text-gray-700">Quitar Ingredientes</h3>
                                            <template x-for="ingrediente in producto.ingredientes" :key="ingrediente.id">
                                                <div class="flex items-center mt-2">
                                                    <input
                                                        type="checkbox"
                                                        :name="'ingredientes_' + producto.id"
                                                        :value="ingrediente.id"
                                                        :id="'ingrediente_' + producto.id + '_' + ingrediente.id"
                                                        :checked="!ingredientesQuitados[Number(producto.id)]?.includes(Number(ingrediente.id))"
                                                        :disabled="ingrediente.pivot.es_obligatorio ? true : false"
                                                        class="h-4 w-4 sm:h-5 sm:w-5 md:h-6 md:w-6 border-gray-300 rounded focus:ring-orange-500 text-orange-600"
                                                        @change="updateIngredientesQuitados(producto.id, ingrediente.id, !$event.target.checked)">
                                                    <label :for="'ingrediente_' + producto.id + '_' + ingrediente.id"
                                                           class="ml-2 sm:ml-3 text-sm sm:text-base md:text-md"
                                                           :class="{ 'text-gray-400': ingrediente.pivot.es_obligatorio, 'text-gray-600': !ingrediente.pivot.es_obligatorio }"
                                                           x-text="ingrediente.nombre + (ingrediente.pivot.es_obligatorio ? ' (Obligatorio)' : '')">
                                                    </label>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </template>
                            </div>
                            <div class="px-4 sm:px-6 pb-4 sm:pb-6 mt-auto">
                                <div x-data="{ loading: false }">
                                    <button @click="addToCart(producto.id)" :disabled="loading"
                                            class="w-full bg-orange-600 text-white py-2 sm:py-3 rounded-lg hover:bg-orange-700 text-sm sm:text-base md:text-lg transition transform hover:scale-105 active:scale-95"
                                            x-text="loading ? 'Añadiendo...' : 'Agregar al Carrito'">
                                    </button>
                                </div>
                            </div>
                        </div>
                    </template>
                    <template x-if="!productos.data || productos.data.length === 0">
                        <p class="text-gray-600 col-span-full text-base sm:text-lg md:text-xl text-center">No se encontraron productos.</p>
                    </template>
                </div>

                <div class="mt-6 sm:mt-8 flex justify-center">
                    <nav aria-label="Paginación" class="inline-flex space-x-2 sm:space-x-3">
                        <button @click="loadProducts(categoria_id, productos.current_page - 1)"
                                :disabled="!productos.prev_page_url"
                                class="px-3 sm:px-4 py-2 rounded-lg text-sm sm:text-base md:text-lg transition transform hover:scale-105"
                                :class="productos.prev_page_url ? 'bg-orange-600 text-white hover:bg-orange-700' : 'bg-gray-200 text-gray-500 cursor-not-allowed'">
                            Anterior
                        </button>
                        <template x-for="page in Array.from({ length: productos.last_page }, (_, i) => i + 1)" :key="page">
                            <button @click="loadProducts(categoria_id, page)"
                                    class="px-3 sm:px-4 py-2 rounded-lg text-sm sm:text-base md:text-lg transition transform hover:scale-105"
                                    :class="productos.current_page === page ? 'bg-orange-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50'"
                                    x-text="page">
                            </button>
                        </template>
                        <button @click="loadProducts(categoria_id, productos.current_page + 1)"
                                :disabled="!productos.next_page_url"
                                class="px-3 sm:px-4 py-2 rounded-lg text-sm sm:text-base md:text-lg transition transform hover:scale-105"
                                :class="productos.next_page_url ? 'bg-orange-600 text-white hover:bg-orange-700' : 'bg-gray-200 text-gray-500 cursor-not-allowed'">
                            Siguiente
                        </button>
                    </nav>
                </div>
            </div>

            <div class="lg:col-span-1 relative">
                <!-- Floating cart button for small screens -->
                <div class="lg:hidden fixed bottom-4 right-4 z-50">
                    <button @click="showCart = !showCart" 
                            class="bg-orange-600 text-white p-3 sm:p-4 rounded-full shadow-lg hover:bg-orange-700 transition transform hover:scale-105 focus:outline-none flex items-center">
                        <i class="fas fa-shopping-cart mr-2"></i>
                        <span class="text-xs sm:text-sm font-semibold">Carrito (<span x-text="Object.keys(items).length"></span>)</span>
                    </button>
                </div>

                <!-- Cart panel for small screens -->
                <div x-show="showCart" 
                     x-transition:enter="transition ease-out duration-300 transform"
                     x-transition:enter-start="translate-x-full"
                     x-transition:enter-end="translate-x-0"
                     x-transition:leave="transition ease-in duration-200 transform"
                     x-transition:leave-start="translate-x-0"
                     x-transition:leave-end="translate-x-full"
                     class="lg:hidden fixed top-0 right-0 w-11/12 sm:w-3/4 max-w-sm h-full bg-white p-4 sm:p-6 shadow-2xl z-50 overflow-y-auto"
                     @click.away="showCart = false">
                    <button @click="showCart = false" class="absolute top-4 right-4 text-gray-600 hover:text-gray-800">
                        <i class="fas fa-times text-lg sm:text-xl"></i>
                    </button>
                    <div class="mt-8">
                        <h2 class="text-lg sm:text-xl md:text-2xl font-semibold text-gray-800">Carrito</h2>
                        <div class="mt-4">
                            <template x-if="Object.keys(items).length === 0">
                                <p class="text-gray-600 text-base sm:text-lg md:text-xl">Tu carrito está vacío.</p>
                            </template>
                            <div class="space-y-4 sm:space-y-6">
                                <template x-for="(item, itemKey) in items" :key="itemKey">
                                    <div x-show="true" x-transition:enter="transition ease-out duration-300"
                                         x-transition:enter-start="opacity-0 translate-x-4"
                                         x-transition:enter-end="opacity-100 translate-x-0"
                                         x-transition:leave="transition ease-in duration-200"
                                         x-transition:leave-start="opacity-100 translate-x-0"
                                         x-transition:leave-end="opacity-0 translate-x-4"
                                         class="flex justify-between items-center">
                                        <div>
                                            <p class="text-sm sm:text-base md:text-lg font-medium" x-text="item.nombre"></p>
                                            <template x-if="item.quitados && item.quitados.length > 0">
                                                <p class="text-xs sm:text-sm text-gray-600 line-clamp-2" x-text="'Sin: ' + item.quitados_nombres.join(', ')"></p>
                                            </template>
                                            <p class="text-xs sm:text-sm md:text-md text-gray-600" x-text="'Subtotal: $' + (item.precio * item.cantidad).toFixed(2)"></p>
                                        </div>
                                        <div class="flex items-center space-x-2 sm:space-x-3">
                                            <input type="number" min="1" :value="item.cantidad" @change="updateCantidad(itemKey, $event.target.value)"
                                                   class="w-12 sm:w-16 md:w-20 border-gray-300 rounded-lg shadow-sm text-sm sm:text-base md:text-lg p-1 sm:p-2">
                                            <button @click="removeFromCart(itemKey)" class="text-red-600 hover:text-red-800 transition transform hover:scale-110">
                                                <svg class="w-4 h-4 sm:w-5 sm:h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                        <div class="mt-4 sm:mt-6 border-t pt-4 sm:pt-6">
                            <p class="text-base sm:text-lg md:text-xl font-semibold text-gray-800">Total: <span x-text="'$' + total.toFixed(2)"></span></p>
                            <button @click="Object.keys(items).length === 0 ? showEmptyCartModal = true : showModal = true"
                                    class="mt-4 w-full bg-orange-600 text-white py-2 sm:py-3 rounded-lg hover:bg-orange-700 text-sm sm:text-base md:text-lg transition transform hover:scale-105 active:scale-95">
                                Proceder al Pago
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Cart for large screens -->
                <div class="hidden lg:block bg-white p-4 sm:p-6 rounded-xl shadow-lg sticky top-6">
                    <div x-data="{ open: true }" class="mb-4 sm:mb-6">
                        <button @click="open = !open" class="w-full flex justify-between items-center text-lg sm:text-xl font-semibold text-gray-800 transition transform hover:scale-105">
                            Filtros
                            <svg :class="{ 'rotate-180': open }" class="w-5 h-5 sm:w-6 sm:h-6 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div x-show="open" x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 translate-y-2"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-200"
                             x-transition:leave-start="opacity-100 translate-y-0"
                             x-transition:leave-end="opacity-0 translate-y-2"
                             class="mt-4">
                            <div>
                                <label for="orden" class="block text-sm sm:text-md font-medium text-gray-700">Ordenar por</label>
                                <select x-model="orden" id="orden" @change="loadProducts(categoria_id)"
                                        class="mt-2 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-orange-500 focus:border-orange-500 text-sm sm:text-base md:text-lg">
                                    <option value="nombre_asc" :selected="orden === 'nombre_asc'">Alfabéticamente (A-Z)</option>
                                    <option value="nombre_desc" :selected="orden === 'nombre_desc'">Alfabéticamente (Z-A)</option>
                                    <option value="precio_desc" :selected="orden === 'precio_desc'">Precio (Mayor a Menor)</option>
                                    <option value="precio_asc" :selected="orden === 'precio_asc'">Precio (Menor a Mayor)</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="border-t pt-4 sm:pt-6">
                        <h2 class="text-lg sm:text-xl md:text-2xl font-semibold text-gray-800">Carrito</h2>
                        <div class="mt-4">
                            <template x-if="Object.keys(items).length === 0">
                                <p class="text-gray-600 text-base sm:text-lg md:text-xl">Tu carrito está vacío.</p>
                            </template>
                            <div class="space-y-4 sm:space-y-6">
                                <template x-for="(item, itemKey) in items" :key="itemKey">
                                    <div x-show="true" x-transition:enter="transition ease-out duration-300"
                                         x-transition:enter-start="opacity-0 translate-x-4"
                                         x-transition:enter-end="opacity-100 translate-x-0"
                                         x-transition:leave="transition ease-in duration-200"
                                         x-transition:leave-start="opacity-100 translate-x-0"
                                         x-transition:leave-end="opacity-0 translate-x-4"
                                         class="flex justify-between items-center">
                                        <div>
                                            <p class="text-sm sm:text-base md:text-lg font-medium" x-text="item.nombre"></p>
                                            <template x-if="item.quitados && item.quitados.length > 0">
                                                <p class="text-xs sm:text-sm text-gray-600 line-clamp-2" x-text="'Sin: ' + item.quitados_nombres.join(', ')"></p>
                                            </template>
                                            <p class="text-xs sm:text-sm md:text-md text-gray-600" x-text="'Subtotal: $' + (item.precio * item.cantidad).toFixed(2)"></p>
                                        </div>
                                        <div class="flex items-center space-x-2 sm:space-x-3">
                                            <input type="number" min="1" :value="item.cantidad" @change="updateCantidad(itemKey, $event.target.value)"
                                                   class="w-12 sm:w-16 md:w-20 border-gray-300 rounded-lg shadow-sm text-sm sm:text-base md:text-lg p-1 sm:p-2">
                                            <button @click="removeFromCart(itemKey)" class="text-red-600 hover:text-red-800 transition transform hover:scale-110">
                                                <svg class="w-4 h-4 sm:w-5 sm:h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                        <div class="mt-4 sm:mt-6 border-t pt-4 sm:pt-6">
                            <p class="text-base sm:text-lg md:text-xl font-semibold text-gray-800">Total: <span x-text="'$' + total.toFixed(2)"></span></p>
                            <button @click="Object.keys(items).length === 0 ? showEmptyCartModal = true : showModal = true"
                                    class="mt-4 w-full bg-orange-600 text-white py-2 sm:py-3 rounded-lg hover:bg-orange-700 text-sm sm:text-base md:text-lg transition transform hover:scale-105 active:scale-95">
                                Proceder al Pago
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Modals -->
            <div x-show="showModal" x-transition:enter="transition ease-out duration-300 transform"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-200 transform"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50" @click.away="showModal = false">
                <div class="bg-white rounded-2xl p-4 sm:p-6 md:p-8 w-full max-w-xs sm:max-w-sm md:max-w-lg shadow-2xl">
                    <h3 class="text-lg sm:text-xl md:text-2xl font-bold text-gray-800 mb-4 sm:mb-6 text-center">Selecciona tu método de pago</h3>
                    <div class="flex flex-wrap justify-center gap-3 sm:gap-4">
                        <button @click="procesarPago('Efectivo')" :disabled="loadingPago"
                                class="flex-1 min-w-[120px] sm:min-w-[140px] bg-white border-2 border-gray-300 text-gray-800 py-2 sm:py-3 px-3 sm:px-4 rounded-lg hover:bg-gray-100 flex flex-col items-center justify-center transition transform hover:scale-105 active:scale-95"
                                :class="{ 'opacity-50 cursor-not-allowed': loadingPago }">
                            <i class="fas fa-money-bill-wave text-lg sm:text-xl md:text-2xl mb-2"></i>
                            <span x-text="loadingPago && metodoPago === 'Efectivo' ? 'Procesando...' : 'Efectivo'"></span>
                        </button>
                        <button @click="procesarPago('Tarjeta')" :disabled="loadingPago"
                                class="flex-1 min-w-[120px] sm:min-w-[140px] bg-white border-2 border-gray-300 text-gray-800 py-2 sm:py-3 px-3 sm:px-4 rounded-lg hover:bg-gray-100 flex flex-col items-center justify-center transition transform hover:scale-105 active:scale-95"
                                :class="{ 'opacity-50 cursor-not-allowed': loadingPago }">
                            <i class="fas fa-credit-card text-lg sm:text-xl md:text-2xl mb-2"></i>
                            <span x-text="loadingPago && metodoPago === 'Tarjeta' ? 'Procesando...' : 'Tarjeta'"></span>
                        </button>
                        <button @click="showModal = false"
                                class="flex-1 min-w-[120px] sm:min-w-[140px] bg-white border-2 border-gray-300 text-gray-800 py-2 sm:py-3 px-3 sm:px-4 rounded-lg hover:bg-gray-100 flex flex-col items-center justify-center transition transform hover:scale-105 active:scale-95">
                            <i class="fas fa-xmark text-lg sm:text-xl md:text-2xl mb-2"></i>
                            <span>Cancelar</span>
                        </button>
                    </div>
                    <p class="mt-3 sm:mt-4 md:mt-6 text-xs sm:text-sm md:text-md text-gray-500 text-center">Todos los pagos son procesados de forma segura.</p>
                </div>
            </div>

            <div x-show="showEmptyCartModal" x-transition:enter="transition ease-out duration-300 transform"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-200 transform"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50" @click.away="showEmptyCartModal = false">
                <div class="bg-white rounded-2xl p-4 sm:p-6 md:p-8 w-full max-w-xs sm:max-w-sm md:max-w-md shadow-2xl">
                    <h3 class="text-lg sm:text-xl md:text-2xl font-bold text-gray-800 mb-4 sm:mb-6 text-center">Carrito vacío</h3>
                    <p class="text-gray-600 text-center mb-3 sm:mb-4 md:mb-6 text-sm sm:text-base md:text-lg">No tienes productos en el carrito.</p>
                    <p class="text-gray-600 text-center mb-3 sm:mb-4 md:mb-6 text-sm sm:text-base md:text-lg">Agrega algunos para proceder al pago.</p>
                    <button @click="showEmptyCartModal = false"
                            class="w-full bg-white border-2 border-gray-300 text-gray-800 py-2 sm:py-3 px-3 sm:px-4 rounded-lg hover:bg-gray-100 flex flex-col items-center justify-center transition transform hover:scale-105 active:scale-95">
                        <i class="fas fa-arrow-left text-lg sm:text-xl md:text-2xl mb-2"></i>
                        <span>Volver</span>
                    </button>
                </div>
            </div>

            <div x-show="showResultadoModal" x-transition:enter="transition ease-out duration-300 transform"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-200 transform"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50" @click.away="showResultadoModal = false">
                <div class="bg-white rounded-2xl p-4 sm:p-6 md:p-8 w-full max-w-xs sm:max-w-sm md:max-w-md shadow-2xl">
                    <div id="ticket-content" class="text-center">
                        <div class="flex justify-center mb-3 sm:mb-4">
                            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-12 sm:h-16 md:h-20">
                        </div>
                        <h3 class="text-lg sm:text-xl md:text-2xl font-bold text-gray-800 mb-2" x-text="resultadoPago.titulo"></h3>
                        <p class="text-gray-600 mb-3 sm:mb-4 text-sm sm:text-base md:text-lg" x-text="resultadoPago.mensaje"></p>
                        <div class="border-2 border-dashed border-gray-300 p-3 sm:p-4 md:p-6 rounded-lg mb-3 sm:mb-4">
                            <p class="text-sm sm:text-base md:text-lg font-semibold mb-2">Comprobante del Pedido</p>
                            <p class="text-gray-600 mb-1 sm:mb-2 text-xs sm:text-sm md:text-base" x-text="'Código: ' + resultadoPago.codigo"></p>
                            <p class="text-gray-600 mb-2 sm:mb-3 text-xs sm:text-sm md:text-base" x-text="'Fecha: ' + new Date().toLocaleString()"></p>
                            <p class="text-gray-600 mb-2 sm:mb-3 text-xs sm:text-sm md:text-base" x-text="'Método: ' + resultadoPago.metodo"></p>
                            <svg id="barcode" class="w-full h-12 sm:h-16 md:h-20 mb-2 sm:mb-3"></svg>
                            <p class="text-xs sm:text-sm md:text-md text-gray-500">Gracias por tu compra</p>
                        </div>
                        <div class="flex justify-center space-x-3 sm:space-x-4 mt-3 sm:mt-4 md:mt-6">
                            <button @click="imprimirTicket()"
                                    class="bg-orange-600 text-white py-2 sm:py-3 px-3 sm:px-4 md:px-6 rounded-lg hover:bg-orange-700 transition transform hover:scale-105 text-sm sm:text-base">
                                <i class="fas fa-print mr-2"></i>Imprimir Ticket
                            </button>
                            <button @click="showResultadoModal = false"
                                    class="bg-gray-200 text-gray-800 py-2 sm:py-3 px-3 sm:px-4 md:px-6 rounded-lg hover:bg-gray-300 transition transform hover:scale-105 text-sm sm:text-base">
                                <i class="fas fa-arrow-left mr-2"></i>Volver
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Logout Modal -->
            <div x-show="showLogoutModal" x-transition:enter="transition ease-out duration-300 transform"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-200 transform"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50" @click.away="showLogoutModal = false">
                <div class="bg-white rounded-2xl p-4 sm:p-6 md:p-8 w-full max-w-xs sm:max-w-sm md:max-w-md shadow-2xl">
                    <h3 class="text-lg sm:text-xl md:text-2xl font-bold text-gray-800 mb-4 sm:mb-6 text-center">Ingrese la clave para cerrar sesión</h3>
                    <div class="mb-4">
                        <input type="password" x-model="logoutPassword" @keyup.enter="checkLogoutPassword"
                               class="w-full border-gray-300 rounded-lg shadow-sm text-sm sm:text-base md:text-lg p-2 focus:ring-orange-500 focus:border-orange-500 text-center center-placeholder"
                               placeholder="Ingrese la clave">
                    </div>
                    <p x-show="logoutError" class="text-red-600 text-center mb-4 text-sm sm:text-base" x-text="logoutError"></p>
                    <div class="flex justify-center gap-3 sm:gap-4">
                        <button @click="checkLogoutPassword"
                                class="bg-orange-600 text-white py-2 sm:py-3 px-3 sm:px-4 rounded-lg hover:bg-orange-700 transition transform hover:scale-105 text-sm sm:text-base">
                            Confirmar
                        </button>
                        <button @click="showLogoutModal = false; logoutPassword = ''; logoutError = ''"
                                class="bg-gray-200 text-gray-800 py-2 sm:py-3 px-3 sm:px-4 rounded-lg hover:bg-gray-300 transition transform hover:scale-105 text-sm sm:text-base">
                            Cancelar
                        </button>
                    </div>
                </div>
            </div>

            <style>
                .center-placeholder::placeholder {
                    text-align: center;
                }
                .line-clamp-3 {
                    display: -webkit-box;
                    -webkit-line-clamp: 3;
                    -webkit-box-orient: vertical;
                    overflow: hidden;
                }
                .line-clamp-2 {
                    display: -webkit-box;
                    -webkit-line-clamp: 2;
                    -webkit-box-orient: vertical;
                    overflow: hidden;
                }
            </style>
        </div>
    </div>

    <script>
        window._appData = {
            productos: @json($productos),
            categorias: @json($categorias),
            items: @json($carrito),
            orden: '{{ request("orden", "nombre_asc") }}',
            categoria_id: {{ request('categoria_id') ? request('categoria_id') : 'null' }},
            csrf: '{{ csrf_token() }}',
            rutas: {
                dashboard: '{{ route("Cliente.dashboard") }}',
                addToCart: '{{ route("Cliente.addToCart") }}',
                updateCart: '{{ route("Cliente.updateCart") }}',
                removeFromCart: '{{ route("Cliente.removeFromCart") }}',
                procesarPago: '{{ route("Cliente.procesarPago") }}',
                logout: '{{ route("logout") }}'
            }
        };
    </script>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('app', () => ({
                productos: window._appData.productos,
                categorias: window._appData.categorias,
                items: window._appData.items,
                orden: window._appData.orden,
                categoria_id: window._appData.categoria_id,
                ingredientesQuitados: {},
                showModal: false,
                showEmptyCartModal: false,
                showResultadoModal: false,
                showCart: false,
                showLogoutModal: false,
                logoutPassword: '',
                logoutError: '',
                showNotification: false,
                notificationMessage: '',
                resultadoPago: {
                    titulo: '',
                    mensaje: '',
                    codigo: null,
                    metodo: ''
                },
                loadingPago: false,
                metodoPago: null,

                init() {
                    Object.keys(this.items).forEach(key => {
                        this.items[key].cantidad = this.items[key].cantidad || 1;
                        this.items[key].quitados_nombres = this.items[key].quitados ?
                            this.getIngredientesNombres(this.items[key].quitados) : [];
                    });
                    this.productos.data.forEach(producto => {
                        const productoId = Number(producto.id);
                        this.ingredientesQuitados[productoId] = [];
                    });
                    this.showNotification = false;
                    this.notificationMessage = '';
                },

                checkLogoutPassword() {
                    if (this.logoutPassword === '4500') {
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = window._appData.rutas.logout;
                        const csrfInput = document.createElement('input');
                        csrfInput.type = 'hidden';
                        csrfInput.name = '_token';
                        csrfInput.value = window._appData.csrf;
                        form.appendChild(csrfInput);
                        document.body.appendChild(form);
                        form.submit();
                    } else {
                        this.logoutError = 'Clave incorrecta';
                        this.logoutPassword = '';
                    }
                },

                async loadProducts(categoriaId = null, page = 1) {
                    try {
                        const params = new URLSearchParams();
                        if (categoriaId) params.append('categoria_id', categoriaId);
                        if (this.orden) params.append('orden', this.orden);
                        params.append('page', page);

                        const response = await fetch(`${window._appData.rutas.dashboard}?${params.toString()}`, {
                            headers: { 'Accept': 'application/json' }
                        });

                        const data = await response.json();
                        this.productos = data.productos;
                        this.categoria_id = categoriaId;
                        this.productos.data.forEach(producto => {
                            const productoId = Number(producto.id);
                            this.ingredientesQuitados[productoId] = [];
                        });
                    } catch (error) {
                        console.error('Error al cargar los productos:', error);
                    }
                },

                async addToCart(productoId) {
                    try {
                        const producto = this.productos.data.find(p => p.id === productoId);
                        if (!producto) throw new Error('Producto no encontrado');

                        const quitados = this.ingredientesQuitados[productoId] || [];

                        const response = await fetch(window._appData.rutas.addToCart, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': window._appData.csrf
                            },
                            body: JSON.stringify({
                                producto_id: productoId,
                                quitados: quitados,
                                cantidad: 1
                            })
                        });

                        const data = await response.json();
                        if (data.success) {
                            this.items = data.carrito;
                            Object.keys(this.items).forEach(key => {
                                this.items[key].quitados_nombres = this.items[key].quitados ?
                                    this.getIngredientesNombres(this.items[key].quitados) : [];
                            });
                            this.notificationMessage = `${producto.nombre} añadido al carrito`;
                            this.showNotification = true;
                            setTimeout(() => {
                                this.showNotification = false;
                            }, 3000);
                            const cartButton = document.querySelector('.fixed.bottom-4.right-4 button');
                            if (cartButton) {
                                cartButton.classList.add('animate-pulse');
                                setTimeout(() => cartButton.classList.remove('animate-pulse'), 1000);
                            }
                        } else {
                            console.error('Error:', data.message);
                        }
                    } catch (error) {
                        console.error('Error al añadir al carrito:', error);
                    }
                },

                async updateCantidad(itemKey, cantidad) {
                    cantidad = Math.max(1, parseInt(cantidad));
                    try {
                        const response = await fetch(window._appData.rutas.updateCart, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': window._appData.csrf
                            },
                            body: JSON.stringify({
                                item_key: itemKey,
                                cantidad: cantidad
                            })
                        });

                        const data = await response.json();
                        if (data.success) {
                            this.items = data.carrito;
                            Object.keys(this.items).forEach(key => {
                                this.items[key].quitados_nombres = this.items[key].quitados ?
                                    this.getIngredientesNombres(this.items[key].quitados) : [];
                            });
                        } else {
                            console.error('Error:', data.message);
                        }
                    } catch (error) {
                        console.error('Error al actualizar el carrito:', error);
                    }
                },

                async removeFromCart(itemKey) {
                    try {
                        const response = await fetch(window._appData.rutas.removeFromCart, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': window._appData.csrf
                            },
                            body: JSON.stringify({
                                item_key: itemKey
                            })
                        });

                        const data = await response.json();
                        if (data.success) {
                            this.items = data.carrito;
                            Object.keys(this.items).forEach(key => {
                                this.items[key].quitados_nombres = this.items[key].quitados ?
                                    this.getIngredientesNombres(this.items[key].quitados) : [];
                            });
                        } else {
                            console.error('Error:', data.message);
                        }
                    } catch (error) {
                        console.error('Error al eliminar del carrito:', error);
                    }
                },

                async procesarPago(metodoPago) {
                    if (Object.keys(this.items).length === 0) {
                        this.showModal = false;
                        this.showEmptyCartModal = true;
                        return;
                    }
                    this.loadingPago = true;
                    this.metodoPago = metodoPago;
                    try {
                        const response = await fetch(window._appData.rutas.procesarPago, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': window._appData.csrf
                            },
                            body: JSON.stringify({
                                metodo_pago: metodoPago
                            })
                        });

                        const data = await response.json();
                        this.showModal = false;
                        this.showCart = false;
                        if (data.success) {
                            this.items = {};
                            this.resultadoPago = {
                                titulo: '¡Pago Exitoso!',
                                mensaje: 'Tu pedido ha sido procesado con éxito.',
                                codigo: data.codigo,
                                metodo: metodoPago
                            };
                            this.showResultadoModal = true;
                            this.generarCodigoBarras(data.codigo);
                        } else {
                            this.resultadoPago = {
                                titulo: 'Error en el Pago',
                                mensaje: data.message || 'Hubo un problema al procesar tu pago. Por favor, intenta de nuevo.',
                                codigo: null,
                                metodo: ''
                            };
                            this.showResultadoModal = true;
                        }
                    } catch (error) {
                        this.showModal = false;
                        this.showCart = false;
                        this.resultadoPago = {
                            titulo: 'Error en el Pago',
                            mensaje: 'Ocurrió un error al procesar el pago. Por favor, intenta de nuevo.',
                            codigo: null,
                            metodo: ''
                        };
                        this.showResultadoModal = true;
                        console.error('Error al procesar el pago:', error);
                    } finally {
                        this.loadingPago = false;
                        this.metodoPago = null;
                    }
                },

                generarCodigoBarras(codigo) {
                    if (!codigo) return;
                    setTimeout(() => {
                        try {
                            JsBarcode("#barcode", codigo, {
                                format: "CODE128",
                                lineColor: "#000",
                                width: 2,
                                height: 50,
                                displayValue: true,
                                fontSize: 16,
                                margin: 10
                            });
                        } catch (error) {
                            console.error("Error generando código de barras:", error);
                        }
                    }, 100);
                },

                imprimirTicket() {
                    const contenido = document.querySelector('#ticket-content .border-2').outerHTML; // ya es más seguro
                    const ventana = window.open('', '_blank');
                    ventana.document.open();
                    ventana.document.write(`
                        <!DOCTYPE html>
                        <html>
                        <head>
                            <title>Ticket de Pedido</title>
                            <style>
                                body { 
                                    font-family: 'Poppins', sans-serif; 
                                    margin: 0; 
                                    padding: 10px; 
                                    width: 76mm;
                                }
                                .ticket { 
                                    width: 76mm; 
                                    max-width: 76mm;
                                    margin: 0 auto; 
                                    text-align: center; 
                                }
                                .info { 
                                    margin: 10px 0; 
                                }
                                .barcode { 
                                    margin: 10px 0; 
                                }
                                .footer { 
                                    font-size: 12px; 
                                    margin-top: 10px; 
                                }
                                p { 
                                    margin: 0; 
                                    padding: 5px 0; 
                                }
                                h3 { 
                                    margin: 0; 
                                    padding: 5px 0; 
                                }
                                hr {
                                    border: none;
                                    border-top: 1px dashed #000;
                                    margin: 10px 0;
                                }
                                img {
                                    max-width: 60mm;
                                    margin: 0 auto 10px;
                                }
                                @media print {
                                    body { 
                                        width: 76mm; 
                                        margin: 0;
                                    }
                                    @page {
                                        size: 76mm auto;
                                        margin: 0;
                                    }
                                    #btn-imprimir-comprobante {
                                        display: none !important;
                                    }
                                }
                            </style>
                        </head>
                        <body onload="window.print();">
                            <div class="ticket">
                                ${contenido}
                            </div>
                        </body>
                        </html>
                    `);
                    ventana.document.close();
                },

                updateIngredientesQuitados(productoId, ingredienteId, isRemoved) {
                    productoId = Number(productoId);
                    ingredienteId = Number(ingredienteId);
                    if (!this.ingredientesQuitados[productoId]) {
                        this.ingredientesQuitados[productoId] = [];
                    }
                    if (isRemoved) {
                        if (!this.ingredientesQuitados[productoId].includes(ingredienteId)) {
                            this.ingredientesQuitados[productoId].push(ingredienteId);
                        }
                    } else {
                        this.ingredientesQuitados[productoId] = this.ingredientesQuitados[productoId].filter(id => id !== ingredienteId);
                    }
                },

                getIngredientesNombres(ingredienteIds) {
                    const allIngredientes = this.productos.data
                        .flatMap(p => p.ingredientes)
                        .reduce((acc, ing) => ({ ...acc, [ing.id]: ing.nombre }), {});
                    return ingredienteIds.map(id => allIngredientes[id] || 'Desconocido');
                },

                get total() {
                    return Object.values(this.items).reduce((sum, item) => sum + (item.precio * item.cantidad), 0);
                }
            }));
        });
    </script>
</body>

</html>