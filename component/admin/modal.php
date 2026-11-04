<div id="modal-popup" class="fixed invisible opacity-0 inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
    <div id="form-product" class="form w-full max-w-2xl bg-white rounded-3xl shadow-2xl border border-gray-100 overflow-hidden max-h-[90vh] flex flex-col">
        
        <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
            <h2 class="text-xl font-bold text-gray-900 tracking-tight">Add New Product</h2>
            <button type="button" class="text-gray-400 hover:text-gray-600 transition-colors p-1 rounded-full hover:bg-gray-100">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <div class="p-6 overflow-y-auto flex-1">
            <form id="form" action="add_product_admin.php" method="post" enctype="multipart/form-data" class="space-y-5">
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label for="product_name" class="block text-xs font-semibold uppercase tracking-wider text-gray-500">Product Name</label>
                        <input type="text" name="product_name" class="w-full px-4 py-2.5 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-all" id="product_name" placeholder="Enter product name">
                    </div>
                    <div class="space-y-1.5">
                        <label for="product_price" class="block text-xs font-semibold uppercase tracking-wider text-gray-500">Product Price</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-gray-400 text-sm">$</span>
                            <input type="text" name="product_price" class="w-full pl-8 pr-4 py-2.5 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-all" id="product_price" placeholder="0.00">
                        </div>
                    </div>
                </div>

                <div class="space-y-1.5">
                    <label for="product_describe" class="block text-xs font-semibold uppercase tracking-wider text-gray-500">Product Describe</label>
                    <textarea name="product_describe" rows="2" class="w-full px-4 py-2.5 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-all resize-none" id="product_describe" placeholder="Enter product description..."></textarea>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label for="product_color" class="block text-xs font-semibold uppercase tracking-wider text-gray-500">Product Color</label>
                        <input type="text" name="product_color" class="w-full px-4 py-2.5 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-all" id="product_color" placeholder="e.g., Red, Black">
                    </div>
                    <div class="space-y-1.5">
                        <label for="product_size" class="block text-xs font-semibold uppercase tracking-wider text-gray-500">Product Size</label>
                        <input type="text" name="product_size" class="w-full px-4 py-2.5 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-all" id="product_size" placeholder="e.g., S, M, L, XL">
                    </div>
                </div>

                <div class="space-y-1.5">
                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-500">Category</label>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5 category-container">
                        <label class="flex items-center justify-center px-4 py-2.5 border border-gray-200 rounded-xl cursor-pointer hover:border-indigo-600 hover:bg-indigo-50/50 transition-all [&:has(input:checked)]:bg-indigo-50 [&:has(input:checked)]:border-indigo-600 [&:has(input:checked)]:text-indigo-700 text-sm font-medium text-gray-600">
                            <input type="radio" name="product_category" class="sr-only input-radio" value="collections" checked>
                            <span>Collections</span>
                        </label>
                        
                        <label class="flex items-center justify-center px-4 py-2.5 border border-gray-200 rounded-xl cursor-pointer hover:border-indigo-600 hover:bg-indigo-50/50 transition-all [&:has(input:checked)]:bg-indigo-50 [&:has(input:checked)]:border-indigo-600 [&:has(input:checked)]:text-indigo-700 text-sm font-medium text-gray-600">
                            <input type="radio" name="product_category" class="sr-only input-radio" value="men">
                            <span>Men</span>
                        </label>
                        
                        <label class="flex items-center justify-center px-4 py-2.5 border border-gray-200 rounded-xl cursor-pointer hover:border-indigo-600 hover:bg-indigo-50/50 transition-all [&:has(input:checked)]:bg-indigo-50 [&:has(input:checked)]:border-indigo-600 [&:has(input:checked)]:text-indigo-700 text-sm font-medium text-gray-600">
                            <input type="radio" name="product_category" class="sr-only input-radio" value="women">
                            <span>Women</span>
                        </label>
                
                        <label class="flex items-center justify-center px-4 py-2.5 border border-gray-200 rounded-xl cursor-pointer hover:border-indigo-600 hover:bg-indigo-50/50 transition-all [&:has(input:checked)]:bg-indigo-50 [&:has(input:checked)]:border-indigo-600 [&:has(input:checked)]:text-indigo-700 text-sm font-medium text-gray-600">
                            <input type="radio" name="product_category" class="sr-only input-radio" value="accesories">
                            <span>Accessories</span>
                        </label>
                    </div>
                </div>

                <div class="space-y-1.5">
                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-500">Product Images</label>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        
                        <div>
                            <input type="file" name="product_img" id="product_img" class="hidden">
                            <label for="product_img" class="flex flex-col items-center justify-center p-4 border-2 border-dashed border-gray-200 rounded-2xl cursor-pointer hover:border-indigo-500 hover:bg-indigo-50/30 transition-all group bg-gray-50/50 text-center h-28">
                                <svg class="w-6 h-6 text-gray-400 group-hover:text-indigo-600 mb-1 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                <span class="file-name text-xs text-gray-500 group-hover:text-indigo-600 font-medium line-clamp-1">Main Image</span>
                                <img class="preview hidden w-full h-full object-cover rounded-xl">
                            </label>
                        </div>

                        <div>
                            <input type="file" name="product_img1" id="product_img1" class="hidden">
                            <label for="product_img1" class="flex flex-col items-center justify-center p-4 border-2 border-dashed border-gray-200 rounded-2xl cursor-pointer hover:border-indigo-500 hover:bg-indigo-50/30 transition-all group bg-gray-50/50 text-center h-28">
                                <svg class="w-6 h-6 text-gray-400 group-hover:text-indigo-600 mb-1 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                <span class="file-name text-xs text-gray-500 group-hover:text-indigo-600 font-medium line-clamp-1">Sub Image 1</span>
                                <img class="preview hidden w-full h-full object-cover rounded-xl">
                            </label>
                        </div>

                        <div>
                            <input type="file" name="product_img2" id="product_img2" class="hidden">
                            <label for="product_img2" class="flex flex-col items-center justify-center p-4 border-2 border-dashed border-gray-200 rounded-2xl cursor-pointer hover:border-indigo-500 hover:bg-indigo-50/30 transition-all group bg-gray-50/50 text-center h-28">
                                <svg class="w-6 h-6 text-gray-400 group-hover:text-indigo-600 mb-1 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                <span class="file-name text-xs text-gray-500 group-hover:text-indigo-600 font-medium line-clamp-1">Sub Image 2</span>
                                <img class="preview hidden w-full h-full object-cover rounded-xl">
                            </label>
                        </div>

                    </div>
                </div>

                <div class="pt-3">
                    <button type="submit" id="addBtn" class="w-full py-3 px-4 bg-black text-white hover:bg-white hover:text-black font-semibold rounded-xl shadow-md shadow-black transition-all cursor-pointer">
                        Add Product
                    </button>
                </div>

            </form>
        </div>
    </div>

    <div id="form-voucher" style="display: none;" class="form"></div>
</div>