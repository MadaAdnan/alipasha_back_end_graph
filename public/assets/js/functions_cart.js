function addCart(product, productImg, userImg) {

    var productData = JSON.parse(product);

    let items = JSON.parse(localStorage.getItem("carts")) || [];
    let itemIndex = items.findIndex(item => item.product.id === productData.id);

    if (itemIndex !== -1) {

        // إذا كان العنصر موجودًا، قم بزيادة الكمية
        items[itemIndex] = {product: productData, qty: items[itemIndex].qty + 1};

    } else {
        // إذا لم يكن موجودًا، أضفه إلى المصفوفة
        items.push({product: productData, qty:1});
    }

// تحديث localStorage بالمصفوفة الجديدة
    localStorage.setItem("carts", JSON.stringify(items));

    let cartBadge=document.getElementById('cart-badge');
    cartBadge.innerText=getCountItemsInCart();
    console.log(productData)
}

function deleteFromCart(productId) {
    let items = JSON.parse(localStorage.getItem("carts")) || [];
    let itemIndex = items.findIndex(item => item.productId === productId);
    if (itemIndex !== -1) {
        items.splice(itemIndex, 1); // حذف العنصر من المصفوفة
        localStorage.setItem("carts", JSON.stringify(items)); // تحديث localStorage
    }

}

function getCountItemsInCart() {


    let items = JSON.parse(localStorage.getItem("carts")) || [];
    let cartBadge=document.getElementById('cart-badge');
    cartBadge.innerText=items.length;
    return items.length
}
